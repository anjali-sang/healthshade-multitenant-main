<?php

namespace App\Imports;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    public $current = 0;
    private $userId;
    private $organizationId;
    private $userRole;
    private $userOrganizationId;
    private $skippedUsers = [];

    public function __construct($user)
    {
        $this->organizationId = $user->organization_id;
        $this->userId = $user->user_id;
        $this->userRole = $user->role_id;
    }

    public function model(array $row)
    {
        $this->current++;

        // Skip header row
        if ($this->current == 0) {
            return null;
        }

        // Basic validation for required fields
        if (empty($row['name']) || empty($row['email']) || empty($row['role'])) {
            $this->addSkippedUser($row, 'Missing required fields');
            return null;
        }

        if (User::where('email',$row['email'])->where('is_active',true)->exists()) {
            $this->addSkippedUser($row, 'User already exists');
            return null;
        }

        // Only admin or staff must have an organization
        if (empty($row['organization']) && auth()->user()->role_id == 1 && in_array($row['role'], ['admin', 'staff'])) {
            $this->addSkippedUser($row, 'Organization is required for admin and staff');
            return null;
        }

        // Role validation
        if (!in_array($row['role'], ['superadmin', 'admin', 'staff'])) {
            $this->addSkippedUser($row, 'Invalid role');
            return null;
        }

        // Check permission to create superadmin
        if ($row['role'] === 'superadmin' && $this->userRole !== '1') {
            $this->addSkippedUser($row, 'Only superadmin can create superadmin');
            return null;
        }

        // Get authenticated user's organization
        $authUser = auth()->user();
        $authOrganization = $authUser->organization;

        // Check user limit for organization
        if (
            optional($authOrganization->subscription)->max_users <=
            User::where('organization_id', $authOrganization->id)->where('is_active', true)->count() &&
            $this->userRole === '2'
        ) {
            $this->addSkippedUser($row, 'Your organization has reached the maximum number of users');
            return null;
        }

        // Determine organization to assign
        $organization = null;
        if ($row['role'] != 'superadmin' && auth()->user()->role_id == 1) {
            $organization = Organization::where('name', $row['organization'])->first();
            logger('Organization ID Found: ' . $organization->id);
        } elseif ($row['role'] != 'superadmin' && auth()->user()->role_id != 1) {
            $organization = auth()->user()->organization;
        }
        // Map role to role_id
        $roleId = match ($row['role']) {
            'superadmin' => '1',
            'admin' => '2',
            default => '3', // staff
        };

        // Create the user first
        $user = new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make('Welcome1!'),
            'role_id' => $roleId,
            'organization_id' => $organization?->id,
            'created_by' => $this->userId,
        ]);

        $user->save(); 

        // Send notification
        $user->notify(new NewUserNotification($row['email'], 'Welcome1!'));
        // Return the saved user
        return $user;

    }

    /**
     * Helper to log skipped users
     */
    private function addSkippedUser(array $row, string $issue): void
    {
        $this->skippedUsers[] = [
            'name' => $row['name'] ?? 'N/A',
            'email' => $row['email'] ?? 'N/A',
            'issue' => $issue,
        ];
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'role' => 'required|in:superadmin,admin,staff',
        ];
    }

    public function getSkippedUsers(): array
    {
        return $this->skippedUsers;
    }

    public function downloadSkippedCsv()
    {
        $filename = 'skipped_users_' . time() . '.csv';
        $handle = fopen($filename, 'w+');

        // Add CSV headers

        $headers = ['name', 'email', 'issue'];
        $csv = implode(',', $headers) . "\n";

        // Add skipped users' data
        foreach ($this->skippedUsers as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'skipped_products.csv');
    }
}
