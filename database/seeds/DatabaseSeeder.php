/ database/seeds/DatabaseSeeder.php

<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::table('users')->delete();

        $users = array(
                [
                'name' => 'Sample Student',
                'email' => 'student@gmail.com',
                'username' => 'student1',
                'phone_no' => '07454456755',
                'active' =>'1',
                'gender'=>'male',
                'avatar' =>'https://api.adorable.io/avatars/40/abott@adorable.png',
                'dob' =>'1994-01-01',
                'password' => Hash::make('secret')
                ],
        );

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user)
        {
            User::create($user);
        }

        Model::reguard();
    }
}
