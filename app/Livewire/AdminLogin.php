<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminLogin extends Component
{
    public $email = '';
    public $password = '';
    public $error = '';
    public $isLoading = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        // Redirect if already logged in as admin
        if (Auth::check() && Auth::user()->is_admin) {
            redirect()->route('admin.donations');
        }
    }

    public function login()
    {
        $this->isLoading = true;
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
            $user = Auth::user();
            
            if ($user->is_admin) {
                session()->regenerate();
                return redirect()->intended(route('admin.donations'));
            } else {
                Auth::logout();
                $this->error = 'Anda tidak memiliki akses admin.';
                $this->isLoading = false;
            }
        } else {
            $this->error = 'Email atau password salah.';
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.admin-login')->layout('components.layouts.guest');
    }
}
