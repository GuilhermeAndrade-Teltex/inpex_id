<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log the access of a user to a page.
     *
     * @param
     * @return void
     */
    public function insertLog($module_id, $module, $description): void
    {
        $user = User::findOrFail(Auth::id());
        $user_name = $user->name;

        Audit::create([
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => Auth::id(),
            'module_id' => $module_id,
            'module' => $module,
            'action' => 'insert',
            'description' => $user_name . $description
        ]);
    }

    public function editLog($module_id, $module, $old_values, $new_values): void
    {
        $old = array();
        $new = array();
        foreach ($old_values as $old_key => $old_value) {
            foreach ($new_values as $new_key => $new_value) {
                if ($old_key == $new_key && $new_value != $old_value) {
                    array_push($old, $old_value);
                    array_push($new, $new_value);
                }
            }
        }

        $old = json_encode($old, true);
        $new = json_encode($new, true);
        Audit::create([
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => Auth::id(),
            'module_id' => $module_id,
            'module' => $module,
            'action' => 'edit',
            'old_value' => $old,
            'new_value' => $new
        ]);
    }

    public function destroyLog($module_id, $module, $description): void
    {
        $user = User::findOrFail(Auth::id());
        $user_name = $user->name;

        Audit::create([
            'created_at' => now(),
            'created_by' => Auth::id(),
            'module_id' => $module_id,
            'module' => $module,
            'action' => 'delete',
            'description' => $user_name . $description
        ]);
    }
}