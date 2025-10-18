<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email;

    public $password;

    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Only allow admin
            if (Auth::user()->user_type !== 'admin') {
                Auth::logout();
                $this->addError('login', 'You are not authorized to access this page.');

                return;
            }

            session()->regenerate();

            return redirect()->route('dashboard');
        } else {
            $this->addError('login', 'Invalid credentials.');
        }
    }

    // ✅ Logout method
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/'); // redirect to login page
    }

    public function render()
    {
        return view('livewire.auth.login-component')->layout('layouts.guest');
    }
}
