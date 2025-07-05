<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GrantAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grant-admin-access {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Donne les droits administrateur à un utilisateur via son email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("L'utilisateur avec l'email {$email} n'a pas été trouvé.");

            return;
        }

        $adminsCount = User::where('is_admin', true)->count();
        if ($adminsCount >= 2 && ! $user->is_admin) {
            $this->error('Il ne peut y avoir que 2 administrateurs au maximum.');

            return;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("L'utilisateur {$email} a maintenant les droits administrateur.");
    }
}
