<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            User::ROLE_ADMIN => [
                'name' => 'Toir',
                'email' => 'info@useful.uz',
            ],
            User::ROLE_MODERATOR => [
                'name' => 'Moderator',
                'email' => 'moderator@useful.uz',
            ],
            User::ROLE_USER => [
                'name' => 'User',
                'email' => 'user@useful.uz',
            ],
        ];
        $collect = collect([
            'email_verified_at' => now(),
            //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password' => '$2y$10$N1en.a4a1UVu1suqU1RzV.RsWUP.jYU.B3Ee009Dsc0SDnGjZzuE.', // random1
            'remember_token' => Str::random(10),
        ]);

        foreach ($users as $role => $data) {
            // create user
            $rand = array_rand(User::$genders);
            $collect->put('gender', User::$genders[$rand]);
            $collect->put('status', User::STATUS_ACTIVE);
            $collect->put('birthday', date('1996.01.04'));

            $user = User::create($collect->merge($data)->all());
            $this->createUser($role, $user);
        }
    }

    public function createUser($role, User $user)
    {
        // create role
        Role::updateOrCreate(['name' => $role, 'guard_name' => 'api']);

        // permit role to a user
        $user->assignRole($role);
        $this->callWith(RolePermissionsSeeder::class, [$role]);
        if (app()->environment() !== 'prod' && $role != User::ROLE_USER) {
            $this->callWith(SurveySeeder::class, [$user->id]);
        }
    }
}
