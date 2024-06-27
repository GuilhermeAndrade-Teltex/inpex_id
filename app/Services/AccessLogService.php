<?php

namespace App\Services;

use App\Models\AccessLog;
use Illuminate\Support\Facades\Auth;

class AccessLogService
{
    /**
     * Log the access of a user to a page.
     *
     * @param string $page
     * @return void
     */
    public function logAccess(string $page): void
    {
        AccessLog::create([
            'user_id' => Auth::id(),
            'page' => $page,
            'accessed_at' => now(),
        ]);
    }
}