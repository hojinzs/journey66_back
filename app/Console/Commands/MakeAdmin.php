<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operation:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make admin account';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('user name');
        $email = $this->ask('user email');
        $password = $this->secret('user password');
        $passwordConfirmation = $this->secret('type password again');

        $validation = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation
        ],[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails()){
            throw new \Error($validation->errors());
        }

        try {
            $user = User::forceCreate([
                'name' => $name,
                'email' => $email,
                'role' => 'admin',
                'password' => Hash::make($password),
            ]);
        }
        catch (\Exception $exception)
        {
            throw new \Error($exception);
        }

        $this->info('new Admin User '.$user->name.' Register Complete');
    }
}
