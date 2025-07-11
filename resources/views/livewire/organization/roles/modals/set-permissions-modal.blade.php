    <x-modal name="set-permissions-modal" width="w-100" height="h-auto" maxWidth="6xl">
        <div class="flex justify-between items-center">
            <header class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Set Permissions') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Assign various permissions to your users here.') }}
                    </p>
                </div>
            </header>
            <div class="flex justify-between items-center p-4">
                <h1>
                    Role : {{ $role_name }}
                </h1>
            </div>
        </div>
        <form wire:submit.prevent="updatePermissions">
            <!-- Permission Sections -->
            <div class="p-4 space-y-6">
                <!-- Organization Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Organization Permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- View Organization Data -->
                            <div
                                class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                <div class="flex items-center h-6">
                                    <input wire:model="permissions.view_organization_data" id="view_organization_data"
                                        type="checkbox"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        x-model="permissions.view_organization_data"
                                        x-bind:checked="permissions.add_locations || permissions.edit_locations || permissions.delete_locations || permissions.add_users || permissions.edit_users || permissions.delete_users">
                                </div>
                                <div>
                                    <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View Organization
                                        Data</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Users can view organization data
                                        like users, locations, etc.</p>
                                </div>
                            </div>

                            <!-- Location Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Add Locations -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.add_locations" id="add_locations"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.add_locations">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Add
                                                Locations</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can add locations
                                                in the organization.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Locations -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.edit_locations" id="edit_locations"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.edit_locations"
                                                x-on:change="permissions.view_organization_data = permissions.add_locations || permissions.edit_locations || permissions.delete_locations || permissions.add_users || permissions.edit_users || permissions.delete_users">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Edit
                                                Locations</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can edit all
                                                locations in the organization.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Locations -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.delete_locations" id="delete_locations"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                x-model="permissions.delete_locations"
                                                x-on:change="permissions.view_organization_data = permissions.add_locations || permissions.edit_locations || permissions.delete_locations || permissions.add_users || permissions.edit_users || permissions.delete_users">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-red-600 dark:text-red-600">Delete
                                                Locations</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can remove
                                                locations from the organization.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Add Users -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.add_users" id="add_users" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.add_users"
                                                x-on:change="permissions.view_organization_data = permissions.add_users || permissions.edit_users || permissions.delete_users || permissions.add_locations || permissions.edit_locations || permissions.delete_locations">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Add Users
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can add new users
                                                to the organization.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Users -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.edit_users" id="edit_users" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.edit_users"
                                                x-on:change="permissions.view_organization_data = permissions.add_users || permissions.edit_users || permissions.delete_users || permissions.add_locations || permissions.edit_locations || permissions.delete_locations">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Edit Users
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can edit other
                                                users' information.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Users -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.delete_users" id="delete_users"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                x-model="permissions.delete_users"
                                                x-on:change="permissions.view_organization_data = permissions.add_users || permissions.edit_users || permissions.delete_users || permissions.add_locations || permissions.edit_locations || permissions.delete_locations">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-red-600 dark:text-red-600">Delete Users
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can remove other
                                                users from the organization.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Products Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Products Permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- View products -->
                            <div
                                class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                <div class="flex items-center h-6">
                                    <input wire:model="permissions.view_products_data" id="view_products_data"
                                        type="checkbox"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        x-model="permissions.view_products_data">
                                </div>
                                <div>
                                    <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View Products</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Users can view products in the
                                        master catalog.</p>
                                </div>
                            </div>

                            <!-- Products Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Add Product -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.add_products" id="add_products"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.add_products">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Add
                                                Products</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can add products
                                                to the catalog.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Product -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.edit_products" id="edit_products"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.edit_products">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Edit
                                                Products</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can edit products
                                                in the catalog.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Product -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.delete_products" id="delete_products"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                x-model="permissions.delete_products">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-red-600 dark:text-red-600">Delete
                                                Products</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can delete
                                                products from the catalog.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inventory Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Inventory Permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- Inventory Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- View Inventory -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.view_inventory_data" id="view_inventory_data"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.view_inventory_data">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View
                                                Inventory Page</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Inventory
                                                Page.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- All Invenotry -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.all_inventory" id="all_inventory"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.all_inventory">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View All
                                                Inventory</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view inventory
                                                from all locations.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cart Inventory -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.add_to_cart" id="add_to_cart" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.add_to_cart">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Add to Cart
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can add products
                                                in cart locations.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Purchase order Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Purchase order
                            permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- Inventory Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- View Inventory -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.view_purchase_data" id="view_purchase_data"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.view_purchase_data">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View
                                                Purchase Orders</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view recently
                                                placed purchase orders.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <!-- All Purchase -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.all_purchase" id="all_purchase"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.all_purchase">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View All
                                                Purchase orders</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view purchase
                                                orders
                                                from all locations.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cart Inventory -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.receive_orders" id="receive_orders"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.receive_orders">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Receive
                                                Purchase orders
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can receive
                                                purchase orders.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Picking order Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Picking permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- Inventory Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- View Inventory -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.view_picking_data" id="view_picking_data"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.view_picking_data">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View
                                                Picking Data</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view picking
                                                page.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <!-- All Picking -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.all_picking" id="all_picking" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.all_picking">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View All
                                                Pickings</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view picking
                                                data from all locations.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pick  -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.pick_products" id="pick_products"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.pick_products">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Pick
                                                Products
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can Pick Products.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Report Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Report permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <!-- Inventory Permissions -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Purchase report -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.purchase_order_report"
                                                id="purchase_order_report" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.purchase_order_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Purchase
                                                Order report</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Purchase
                                                Order report.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Picking report -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.picking_report" id="picking_report"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.picking_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Picking
                                                report</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Picking
                                                reports.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Audit report  -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.audit_report" id="audit_report"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.audit_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Aduit
                                                report
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Audit
                                                report.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Inv Adjust report  -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.inventory_adjust_report"
                                                id="inventory_adjust_report" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.inventory_adjust_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Inventory
                                                adjust report
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Inventory
                                                adjust report.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Inv transfer report  -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.inventory_transfer_report"
                                                id="inventory_transfer_report" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.inventory_transfer_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Inventory
                                                transfer report
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Inventory
                                                tranfer report.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- product report  -->
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.product_report" id="product_report"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.product_report">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Products
                                                report
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can view Products
                                                report.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Settings Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Setting permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.general_settings" id="general_settings"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.general_settings">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">General
                                                settings</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and
                                                modify general settings.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.categories_settings" id="categories_settings"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.categories_settings">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Categories
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and
                                                modify categories.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.inventory_adjustments"
                                                id="inventory_adjustments" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.inventory_adjustments">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Inventory
                                                Adjusments
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can make inventory
                                                Adjusments.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.inventory_transfers" id="inventory_transfers"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.inventory_transfers">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Inventory
                                                Transfer
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can transfer
                                                Inventory.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.manufacturer_settings"
                                                id="manufacturer_settings" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.manufacturer_settings">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">
                                                Manufacturer
                                                settings
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and
                                                modify manufacturer.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.roles_settings" id="roles_settings"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                x-model="permissions.roles_settings">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-red-600 dark:text-red-600">Roles</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and
                                                modify Roles.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cart Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Cart permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.view_cart" id="view_cart" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.view_cart">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View Cart
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access carts
                                                page.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.all_location_cart" id="all_location_cart"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.all_location_cart">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">All carts
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and
                                                modify cart for all the locations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.approve_all_cart" id="approve_all_cart"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.approve_all_cart">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Approve all
                                                Carts
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can approve cart
                                                for all locations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.approve_own_cart" id="approve_own_cart"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.approve_own_cart">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Approve
                                                cart
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can approve
                                                Purchase orders.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Patient Permissions Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-md" x-data="{ expanded: true }">
                    <button type="button" @click="expanded = !expanded"
                        class="flex justify-between items-center w-full p-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">Patients permissions</span>
                        <svg x-show="expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="!expanded" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-data="{ permissions: @entangle('permissions') }">
                        <div x-show="expanded" class="p-4 space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.view_patient" id="view_patient"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.view_patient">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">View
                                                Patient</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access patient
                                                page.
                                            <p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.add_patient" id="add_patient" type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.add_patient">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Add Patient
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can access and add
                                                new patients.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.edit_patient" id="edit_patient"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.edit_patient">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Edit
                                                Patient
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can edit pateints
                                                information.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div
                                        class="flex items-start justify-start py-2 border-b border-gray-100 dark:border-gray-700 gap-3">
                                        <div class="flex items-center h-6">
                                            <input wire:model="permissions.delete_patient" id="delete_patient"
                                                type="checkbox"
                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                x-model="permissions.delete_patient">
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">Delete
                                                Patient
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Users can delete
                                                patients.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex items-center justify-end gap-4 p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'set-permissions-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="min-w-24 flex justify-center items-center" x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.updatePermissions().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading">{{ __('Update') }}</span>
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
            </div>
        </form>
    </x-modal>