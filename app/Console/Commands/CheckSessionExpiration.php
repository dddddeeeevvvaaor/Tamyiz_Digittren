<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Login; // Import your Login model
use Illuminate\Support\Facades\DB;

class CheckSessionExpiration extends Command
{
    protected $signature = 'session:check-expiration';
    protected $description = 'Check and update user online status based on session expiration';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lifetime = config('session.lifetime') * 60; // Session lifetime in seconds
        $threshold = now()->subSeconds($lifetime);

        // Assuming you are using database session driver
        $expiredSessions = DB::table('sessions')
            ->get();

        foreach ($expiredSessions as $session) {
            // Update is_online status for each user with an expired session
            Login::where('nama', $session->id_user) // Adjust this based on how you store user ID in sessions
                ->update(['is_online' => false]);
        }

        $this->info('Session expiration check complete.');
    }
}
