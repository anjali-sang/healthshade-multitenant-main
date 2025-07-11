<?php

namespace App\Livewire\Admin\Users;
use App\Models\Location;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\PasswordChangeNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class Users extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $role_id = '';
    public $location_id = '';
    public $password = '';
    public $password_confirmation = '';
    public $userId='';
    public $is_active ='';
    public $is_deleted = false;
    public $organization_id = '';
    public $organizations = [];
    public $locations = [];

    public function mount(){
        $this->organizations = Organization::all(); 
    }

    public function createUser()
    {

        $this->validate([
            'name' => 'required|string|max:255|unique:users,name,NULL,id,is_active,true',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,is_active,true',
            'phone' => 'nullable|string|max:15',
            'role_id' => 'required|in:2,3', // Ensure role is valid
            'organization_id' => 'required|exists:organizations,id', // Organization required for Admin and Staff
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

        if($this->role_id == '1'){
            $this->organization_id = null;
            $this->location_id = null;
        }

        // Create the user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => 'avatar (8).png',
            'organization_id' => $this->organization_id,
            'phone' => $this->phone,
            'password' => Hash::make('Welcome1!'),
            'role_id' => $this->role_id,
            'is_deleted' => false,
            'location_id' => $this->location_id,
            'created_by' => auth()->user()->id,
            'is_active' => true
        ]);

        $this->reset();
        $this->dispatch('pg:eventRefresh-users-list-4geyva-table');
        $this->dispatch('close-modal', 'add-user-modal');
        session()->flash('success', 'User created successfully!');
    }

    #[On('edit-user')]
    public function startEdit($rowId)
    {
        $this->editing = true;
        $this->userId = $rowId;
        $user = User::findOrFail($rowId);
        $this->locations = Location::where('org_id',   $user->organization_id)->get();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role_id = $user->role_id;
        $this->location_id = $user->location_id;
        $this->organization_id = $user->organization_id;
        $this->is_active = $user->is_active;
        $this->dispatch('open-modal', 'edit-user-modal');
    }

    public function updateOrganizationId(){
        $this->locations = Location::where('org_id',   $this->organization_id)->get();
    }

    public function updateUserData()
    {
        // Validate input fields
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
            'role_id' => 'required|integer',
            'organization_id' => 'required|integer|exists:organizations,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'is_active' => 'required|boolean'
        ]);

        // Find the user
        $user = User::findOrFail($this->userId);

        // Debugging: Log user update
        \Log::info('Updating user:', ['id' => $this->userId, 'is_active' => $this->is_active]);

        // Update user data
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'organization_id' => $this->organization_id,
            'location_id' => $this->location_id,
            'is_active' => $this->is_active,
        ]);

        // Refresh table
        $this->dispatch('pg:eventRefresh-users-list-4geyva-table');

        // Flash success message
        session()->flash('user-update-success', 'User updated successfully!');

        // Close the edit modal
        $this->dispatch('close-modal', 'edit-user-modal');

        // Reset fields
        $this->reset();
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

        session()->flash('user-password-update-success', 'Password Updated successfully!');
        $user->notify(new PasswordChangeNotification($newPassword));
        // $user->notify(new PasswordUpdated($newPassword));

    }

    public function deleteUser()
    {
        $user = User::find($this->userId);

        if (!$user) {
            session()->flash('error', 'User not found!');
            return;
        }

        // Apply soft delete logic based on role
        if (auth()->user()->role_id == 1) { // Super Admin
            $user->update(['is_active' => false, 'is_deleted' => true]);
        } elseif (auth()->user()->role_id == 2) { // Other Admins
            $user->update(['is_active' => false]);
        }
        // Refresh UI and close modal
        $this->reset();
        $this->dispatch('close-modal', 'edit-user-modal');
        $this->dispatch('pg:eventRefresh-users-list-4geyva-table');

        session()->flash('success', 'User deleted successfully!');
    }

    public function render()
    {
        // Fetch locations for the logged-in user's organization
        

        // Fetch all organizations (assuming no need for 'is_active' column)
      

        return view('livewire.admin.users.users');
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'name', 'email', 'password', 'role', 'organization'
        ];

        $sampleData = [
            ['John Doe', 'john@example.com', 'password123', 'admin', 'ABC Corp'],
            ['Jane Smith', 'jane@example.com', 'password456', 'staff', 'XYZ Ltd'],
            ['Super Admin', 'superadmin@example.com', 'superpass789', 'super_admin', '']
        ];

        $csv = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'sample_users_import.csv');
    }

}
