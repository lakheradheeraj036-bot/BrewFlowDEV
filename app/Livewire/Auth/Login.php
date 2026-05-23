<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;
    public string $errorMessage = '';

    public function login(): void
    {
        $this->errorMessage = '';

        $validated = $this->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        
        $throttleKey = Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Too many login attempts. Please try again in {$seconds} seconds.";
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey);
            $this->errorMessage = 'These credentials do not match our records.';
            $this->password = '';
            return;
        }

        if (!auth()->user()->hasRole('super_admin')) {
            Auth::logout();
            $this->errorMessage = 'Access denied. Super Admin privileges required.';
            $this->password = '';
            return;
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();
        $this->redirect(route('super-admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth');
    }
}
