<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create
                            {--name= : Nama admin}
                            {--email= : Email untuk login}
                            {--password= : Kata sandi (minimal 8 karakter)}';

    protected $description = 'Buat akun admin baru, atau reset kata sandi admin yang emailnya sudah ada';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Nama admin', 'Administrator SISFO');
        $email = $this->option('email') ?: $this->ask('Email (dipakai untuk login)');
        $password = $this->option('password') ?: $this->secret('Kata sandi (minimal 8 karakter)');

        $validator = Validator::make(
            compact('name', 'email', 'password'),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => $password]);

        $this->info($user->wasRecentlyCreated
            ? "Akun admin {$email} berhasil dibuat."
            : "Akun {$email} sudah ada, nama dan kata sandi diperbarui.");

        return self::SUCCESS;
    }
}
