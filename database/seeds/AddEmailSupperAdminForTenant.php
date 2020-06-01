<?php

use Illuminate\Database\Seeder;
use App\Jobs\SendEmailJob;
use App\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddEmailSupperAdminForTenant extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getDataTenant = tenancy()->getTenant();
        $dataTenant = $getDataTenant->data;

        $generatedPassword = $this->generatePassword();
        $data['password'] = Hash::make($generatedPassword . env('SALT'));

        $settings = [
            'email'             => $dataTenant['email'],
            'name'              => $dataTenant['manager'],
            'password'          => bcrypt($generatedPassword . env('SALT')),
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        $result = User::create($settings);
        if ($result) {
            if (!empty($data['password'])) {
                $job = new SendEmailJob(array('email' => $dataTenant['email'], 'password' => $generatedPassword));
                dispatch($job);
            }
            $result->assignRole('system admin');
        }
    }

    public function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890~!@#$%^&*';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
