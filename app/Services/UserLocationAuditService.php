<?php

namespace App\Services;

use App\Models\Location;
use App\Models\PickingModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserLocationAuditService
{
    public function logLocationCreate(
        Location $location,
    ) {
        try {

            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => 'Created',
                'auditable_type' => 'Location',
                'auditable_id' => $location->name,
                'old_values' => json_encode([]),
                'new_values' => json_encode([
                    'location_id' => $location->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id' => auth()->user()->organization_id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log purchase order product audit: ' . $e->getMessage());
            return false;
        }
    }



    public function logLocationUpdate(
        Location $location,
        array $oldValues
    ) {
        try {
            $newValues = $location->toArray();
            $changedOldValues = [];
            $changedNewValues = [];
            $trackableFields = [
                'name', 'nick_name', 'phone', 'email', 'address', 
                'city', 'state', 'country', 'pin', 'is_active'
            ];
            
            // Compare old and new values to find changes
            foreach ($trackableFields as $field) {
                if (isset($oldValues[$field]) && isset($newValues[$field]) && $oldValues[$field] !== $newValues[$field]) {
                    $changedOldValues[$field] = $oldValues[$field];
                    $changedNewValues[$field] = $newValues[$field];
                }
            }
            if (!empty($changedOldValues)) {
                DB::table('audits')->insert([
                    'user_id' => auth()->id(),
                    'event' => 'Updated',
                    'auditable_type' => 'Location',
                    'auditable_id' => $location->name,
                    'old_values' => json_encode($changedOldValues),
                    'new_values' => json_encode($changedNewValues),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'organization_id' => auth()->user()->organization_id,
                ]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to log location update audit: ' . $e->getMessage());
            return false;
        }
    }

    
    public function logLocationDelete(
        Location $location,
    ) {
        try {
            
                DB::table('audits')->insert([
                    'user_id' => auth()->id(),
                    'event' => 'Removed',
                    'auditable_type' => 'Location',
                    'auditable_id' => $location->name,
                    'old_values' => json_encode([]),
                    'new_values' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'organization_id' => auth()->user()->organization_id,
                ]);
                return true;
           
        } catch (\Exception $e) {
            Log::error('Failed to log location update audit: ' . $e->getMessage());
            return false;
        }
    }

    public function logUserCreate(
        User $user,
    ) {
        try {

            // Create a separate audit log entry for this specific product
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => 'Created',
                'auditable_type' => 'User',
                'auditable_id' => $user->name,
                'old_values' => json_encode([]),
                'new_values' => json_encode([
                    'user_id' => $user->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id' => auth()->user()->organization_id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log User audit: ' . $e->getMessage());
            return false;
        }
    }

    public function logUserEdit(
        User $user,
        array $oldValues
    ) {
        try {
            $newValues = $user->toArray();
            $changedOldValues = [];
            $changedNewValues = [];
            $trackableFields = [
                'name', 'email', 'phone', 'role_id','location_id'
            ];
            
            // Compare old and new values to find changes
            foreach ($trackableFields as $field) {
                if (isset($oldValues[$field]) && isset($newValues[$field]) && $oldValues[$field] !== $newValues[$field]) {
                    $changedOldValues[$field] = $oldValues[$field];
                    $changedNewValues[$field] = $newValues[$field];
                }
            }
            if (!empty($changedOldValues)) {
                DB::table('audits')->insert([
                    'user_id' => auth()->id(),
                    'event' => 'Updated',
                    'auditable_type' => 'User',
                    'auditable_id' => $user->name,
                    'old_values' => json_encode($changedOldValues),
                    'new_values' => json_encode($changedNewValues),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'organization_id' => auth()->user()->organization_id,
                ]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to log user update audit: ' . $e->getMessage());
            return false;
        }
    }
    public function logUserDelete(
        User $user,
    ) {
        try {
                DB::table('audits')->insert([
                    'user_id' => auth()->id(),
                    'event' => 'Removed',
                    'auditable_type' => 'User',
                    'auditable_id' => $user->name,
                    'old_values' => json_encode([
                        'user_id'=>$user->id
                    ]),
                    'new_values' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'organization_id' => auth()->user()->organization_id,
                ]);
                return true;
        } catch (\Exception $e) {
            Log::error('Failed to log user update audit: ' . $e->getMessage());
            return false;
        }
    }

}