<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign "user" role to users without roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::doesntHave('roles')->get();

        foreach ($users as $user) {
            $user->assignRole('user');
        }

        $this->info('Users roles fixed');
        return self::SUCCESS;
    }
}
