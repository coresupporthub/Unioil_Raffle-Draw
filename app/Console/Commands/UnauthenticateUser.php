<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UnauthenticateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unauthenticate-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unauthenticate all admin users after a day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
