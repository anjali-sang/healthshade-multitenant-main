<?php

namespace App\Livewire\Organization;

use App\Models\Location;
use App\Models\Roles;
use App\Models\User;
use App\Notifications\NewUserNotification;
use App\Notifications\PasswordChangeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UsersComponent extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $role_id = '3';
    public $location_id = '';
    public $password = '';
    public $password_confirmation = '';
    public $userId = '';
    public $notifications = [];

    public function createUser()
    {
        // Validate input fields
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('add_users') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to add users!', 'error');
            return;
        }
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'role_id' => 'required',
            'location_id' => 'required',
        ]);

        // Add custom validation logic for 'name' uniqueness based on 'is_active'
        if (
            User::where('name', $this->name)
                ->where('is_active', true)
                ->exists()
        ) {
            $this->addError('name', 'The name must be unique.');
            return;
        }

        if (
            User::where('email', $this->email)
                ->where('is_active', true)
                ->exists()
        ) {
            $this->addError('email', 'The email is already taken.');
            return;
        }
        // Create the location
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => 'avatar (8).png',
            'organization_id' => auth()->user()->organization_id,
            'phone' => $this->phone,
            'password' => Hash::make('Welcome1!'),
            'role_id' => $this->role_id,
            'is_active' => true,
            'is_deleted' => false,
            'location_id' => $this->location_id,
            'created_by' => auth()->user()->id,
        ]);
        $password = 'Welcome1!';
        $email = $this->email;
        $auditService = app(\App\Services\UserLocationAuditService::class);
        $auditService->logUserCreate(
            $user
        );
        // Optional: Clear form fields after saving
        // Optional: Provide feedback to the user
        $this->dispatch('pg:eventRefresh-user-list-vv6gjo-table');
        $this->dispatch('close-modal', 'add-user-modal');
        $this->addNotification('User created successfully and email is sent with all Information !', 'success');
        $user->notify(new NewUserNotification($email, $password));
        $this->reset();
    }

    #[On('edit-user')]
    public function startEdit($rowId)
    {
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('edit_users') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to edit users!', 'error');
            return;
        }
        $this->editing = true;
        $this->userId = $rowId;
        $user = User::findOrFail($rowId);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role_id = $user->role_id;
        $this->location_id = $user->location_id;

        $location = Location::find($user->location_id);
        if ($location) {
            $this->location_name = $location->name;
            $this->location_address = $location->address;
        }

        // Handle any additional logic, such as setting default values for missing data
        // For example, setting a default phone number or role if missing
        // if (!$this->phone) {
        //     $this->phone = 'Not provided';
        // }
        // Dispatch an event to open the modal for editing
        $this->dispatch('open-modal', 'edit-user-modal');
    }

    public function updateUserData()
    {

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'phone' => 'nullable|string|max:15',
            'role_id' => 'required',
        ]);

        if (
            User::where('name', $this->name)
                ->where('is_active', true)
                ->where('id', '!=', $this->userId)
                ->exists()
        ) {
            $this->addError('name', 'The name must be unique.');
            return;
        }

        if (
            User::where('email', $this->email)
                ->where('is_active', true)
                ->where('id', '!=', $this->userId)
                ->exists()
        ) {
            $this->addError('email', 'The email is already taken.');
            return;
        }

        // if (auth()->user()->role_id !== 'admin') {
        //     session()->flash('user-update-error', 'Unauthorized to edit other Users data');
        //     return;
        // }

        $user = User::findOrFail($this->userId);
        $oldValues = $user->toArray();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'location_id' => $this->location_id,
        ]);
        $auditService = app(\App\Services\UserLocationAuditService::class);
        $auditService->logUserEdit(
            $user,
            $oldValues
        );
        // $this->reset();
        // Optional: Provide feedback to the user
        $this->dispatch('pg:eventRefresh-user-list-vv6gjo-table');
        // $this->dispatch('close-modal', 'edit-user-modal');
        session()->flash('user-update-success', 'User Updated successfully!');
    }

    public function resetPassword()
    {

        $this->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);
        $user = User::findOrFail($this->userId);
        $newPassword = $this->password;
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->addNotification('Password updated !', 'success');
        $this->dispatch('close-modal', 'edit-user-modal');
        $user->notify(new PasswordChangeNotification($newPassword));
        $this->reset();
    }

    public function deleteUser()
    {
        $loggeduser = auth()->user();
        $role = $loggeduser->role;
        if (!$role?->hasPermission('delete_users') && $loggeduser->role_id> 2) {
            $this->addNotification('You don\'t have permission to delete users!', 'error');
            return;
        }
        $user = User::findOrFail($this->userId);
        $user->is_active = false;
        $user->save();
        $auditService = app(\App\Services\UserLocationAuditService::class);
        $auditService->logUserDelete(
            $user
        );
        $this->dispatch("close-modal", "edit-user-modal");
        session()->flash('success', 'User Deleted successfully!');
        $this->dispatch('pg:eventRefresh-user-list-wsidtt-table');
    }

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);

        // Limit to a maximum of 3-5 notifications if needed
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'name',
            'email',
            'phone_number',
            'role',
            // 'location',
        ];

        $sampleData = [
            [
                'John doe 1',
                'johndoe1@gmail.com',
                '1234567890',
                'admin',
                // 'location1',
            ],
            [
                'John doe 2',
                'johndoe2@gmail.com',
                '1234567890',
                'staff',
                // 'location2',
            ],
        ];
        $csv = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csv .= implode(',', $row) . "\n";
        }
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'sample_users_import.csv');
    }

    public function render()
    {
        $roles  = Roles::where('organization_id',auth()->user()->organization_id)->where('is_active',true)->get();
        $locations = Location::where('is_active', true)->where('org_id', auth()->user()->organization_id)->get();
        return view('livewire.organization.users-component', compact('locations','roles'));
    }
}