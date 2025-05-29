<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Make a user an admin by their email';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            $user = User::where('email', $email)->firstOrFail();
            
            if ($user->is_admin) {
                $this->error("User {$email} is already an admin!");
                return 1;
            }

            $user->is_admin = true;
            $user->save();

            $this->info("Successfully made {$email} an admin!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
} 