<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $allUsers = User::all();
        $this->info("Switching Super admin status");
        foreach($allUsers as $user){
            if($user->name == 'Hazel'){
                $user->update([
                    'user_type' => 'Super Admin'
                ]);
            }

            if($user->name == "Faith Cipriano"){
                $user->update([
                    'user_type' => "Admin"
                ]);
            }
        }

        $this->info("Adding new user");
        $newUser = new User();
        $newUser->name = "Ashbel Beltran";
        $newUser->email = "aobeltran@unioil.com";
        $newUser->password = "12345678";
        $newUser->save();

        $this->info("User Update Done");
    }
}
