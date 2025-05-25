<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:role {user_id} {role_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user by user ID and role name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleName = $this->argument('role_name');

        $user = User::find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} not found.");
            return Command::FAILURE;
        }

        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            $this->error("Role '{$roleName}' not found.");
            return Command::FAILURE;
        }

        $user->assignRole($roleName);

        $this->info("Role '{$roleName}' assigned to user with ID {$userId}.");

        return Command::SUCCESS;
    }
}
