<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddEmailSupperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'email'             => env('EMAIL_SUPER_ADMIN'),
            'name'              => 'Super Admin',
            'password'          => bcrypt(env('PASSWORD_DEFAULT_SUPER_ADMIN') . env('SALT')),
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        $result = User::create($settings);

        if ($result) {
            $result->assignRole('super admin');
        }
    }
}
