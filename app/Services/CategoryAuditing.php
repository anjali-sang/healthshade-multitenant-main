<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CategoryAuditing
{
    public function logCreation(Category $category)
    {
        $this->logAction('created', $category);
    }

    public function logUpdate(Category $oldCategory, Category $newCategory, string $event = 'updated')
    {
        try {
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => $event,
                'auditable_type' => 'Category',
                'auditable_id' => $newCategory->category_name,
                'old_values' => json_encode([
                    'name' => $oldCategory->category_name,
                    'description' => $oldCategory->category_description,
                ]),
                'new_values' => json_encode([
                    'name' => $newCategory->category_name,
                    'description' => $newCategory->category_description,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id' => auth()->user()?->organization_id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log category update audit: ' . $e->getMessage());
            return false;
        }
    }


    public function logDeletion(Category $category, string $event, string $message)
    {
        try {
            DB::table('audits')->insert([
                'user_id' => auth()->id(),
                'event' => $event,
                'auditable_type' => 'Category',
                'auditable_id' => $category->category_name,
                'old_values' => json_encode([
                    'name' => $category->category_name,
                    'message' => $message,
                ]),
                'new_values' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id' => auth()->user()?->organization_id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log category deletion audit: ' . $e->getMessage());
            return false;
        }
    }


    protected function logAction(string $action, Category $category)
    {
        $user = Auth::user();
        Log::info("Category {$action}", [
            'category_id' => $category->id,
            'name' => $category->name,
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'timestamp' => now(),
        ]);
    }
}
