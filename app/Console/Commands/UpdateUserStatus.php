<?php

// UpdateUserStatus.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Login;

class UpdateUserStatus extends Command
{
    protected $signature = 'users:update-status';
    protected $description = 'Update user statuses based on expiration date';

    public function handle()
    {
        $timeout = config('session.lifetime') * 60; // Konversi menit ke detik
        Login::where('is_online', true)
            ->update(['is_online' => false]);
    }
}
