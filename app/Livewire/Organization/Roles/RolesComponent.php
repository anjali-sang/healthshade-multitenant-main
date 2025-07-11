<?php

namespace App\Livewire\Organization\Roles;

use App\Models\Permissions;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesComponent extends Component
{
    
    public $notifications = [];
    public $role_id;
    public $role_name;
    public $role_description;

    public $permissions = [
        // Organization Permissions
        'view_organization_data' => false,
        'edit_locations' => false,
        'add_locations' => false,
        'add_users' => false,
        'delete_locations' => false,
        'edit_users' => false,
        'delete_users' => false,
        'view_products_data' => false,
        'add_products' => false,
        'edit_products' => false,
        'delete_products' => false,
        'view_inventory_data' => false,
        'all_inventory' => false,
        'add_to_cart' => false,
        'view_purchase_data' => false,
        'all_purchase' => false,
        'receive_orders' => false,
        'view_picking_data' => false,
        'all_picking' => false,
        'pick_products' => false,
        'purchase_order_report' => false,
        'picking_report' => false,
        'audit_report' => false,
        'inventory_adjust_report' => false,
        'inventory_transfer_report' => false,
        'product_report' => false,
        'general_settings' => false,
        'categories_settings' => false,
        'inventory_adjustments' => false,
        'inventory_transfers' => false,
        'manufacturer_settings' => false,
        'roles_settings' => false,
        'view_cart' => false,
        'all_location_cart' => false,
        'approve_all_cart' => false,
        'approve_own_cart' => false,
        'view_patient' => false,
        'add_patient' => false,
        'edit_patient' => false,
        'delete_patient' => false,
    ];
    protected $rules = [
        'role_name' => 'required|string|max:100',
        'role_description' => 'required|string|max:255',
        'permissions.*' => 'boolean',
    ];
    protected $messages = [
        'role_name.required' => 'The role name is required.',
        'role_description.required' => 'The role description is required.',
    ];

    public function mount($role_id = null)
    {
        if ($role_id) {
            $this->role_id = $role_id;
            $this->loadRole();
        }
    }
    public function loadRole()
    {
        $role = Roles::findOrFail($this->role_id);
        $this->role_name = $role->role_name;
        $this->role_description = $role->role_description;

        // Load existing permissions
        $rolePermissions = $role->permissions->pluck('permission_name')->toArray();
        foreach ($this->permissions as $key => $value) {
            $this->permissions[$key] = in_array($key, $rolePermissions);
        }
    }
    public function updatePermissions()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            if ($this->role_id) {
                $role = Roles::findOrFail($this->role_id);
            }
            $permissionsToSync = [];
            
            foreach ($this->permissions as $permissionName => $isGranted) {
                if ($isGranted) {
                    $permission = Permissions::where('permission_name', $permissionName)->first();
                    if ($permission) {
                        $permissionsToSync[] = $permission->id;
                    }
                }
            }
            $role->permissions()->sync($permissionsToSync);
            DB::commit();
            $this->dispatch('close-modal', 'set-permissions-modal');
        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->addNotification('An error occurred while updating permissions.', 'error');
            DB::rollBack();
        }
    }


    public function createRole()
    {
        $this->validate([
            'role_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'role_name')->where(function ($query) {
                    return $query->where('organization_id', auth()->user()->organization_id);
                }),
            ],
            'role_description' => 'required|string|max:255',
        ]);
        if (strtolower($this->role_name) === 'admin') {
            $this->addNotification('The role name "admin" is reserved.', 'error');
            return;
        }

        Roles::create([
            'role_name' => $this->role_name,
            'role_description' => $this->role_description,
            'organization_id' => auth()->user()->organization_id,
            'is_active' => 1,
        ]);

        $this->addNotification('Role created successfully. Edit to adjust permissons !', 'success');
        $this->dispatch('pg:eventRefresh-roles-list-fgzyjv-table');
        $this->reset(['role_name', 'role_description']);
        $this->dispatch('close-modal', 'add-role-modal');
    }
    #[\Livewire\Attributes\On('roles-edit')]
    public function editRole($rowId)
    {
        $this->role_id = $rowId;
        $role = Roles::find($this->role_id);
        if ($role) {
            $this->role_name = $role->role_name;
            $this->role_description = $role->role_description;
        }
        $this->dispatch('open-modal', 'edit-role-modal');
    }
    public function updateRole()
    {
        $this->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'required|string|max:255',
        ]);

        $role = Roles::find($this->role_id);
        if ($role) {
            $role->update([
                'role_name' => $this->role_name,
                'role_description' => $this->role_description,
            ]);
            $this->addNotification('Role updated successfully.', 'success');
        } else {
            $this->addNotification('Role not found.', 'error');
        }
        $this->dispatch('close-modal', 'edit-role-modal');
        $this->dispatch('pg:eventRefresh-roles-list-fgzyjv-table');
        $this->reset(['role_name', 'role_description']);
    }
    public function deleteRole($rowId)
    {
        $role = Roles::find($rowId);
        if ($role) {
            $role->delete();
            $this->addNotification('Role deleted successfully.', 'success');
        } else {
            $this->addNotification('Role not found.', 'error');
        }
    }
    public function setPermissions()
    {
        $this->loadRole();
        $this->dispatch('open-modal', 'set-permissions-modal');
    }
    public function updatePermissons()
    {
        $this->dispatch('close-modal', 'set-permissions-modal');
    }
    public function addNotification($message, $type = 'success')
    {
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function render()
    {
        return view('livewire.organization.roles.roles-component');
    }
}
