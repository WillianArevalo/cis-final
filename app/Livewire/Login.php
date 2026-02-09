<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]

class Login extends Component
{
    public string $identifier = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'identifier' => 'required|string',
            'password' => 'required',
        ];
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route($this->redirectRouteFor(auth()->user()->role)), navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate();
        $identifier = trim($this->identifier);
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('name', $identifier)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
        }

        if (!$isEmail) {
            if (filled($user->email)) {
                throw ValidationException::withMessages([
                    'identifier' => 'Debes iniciar sesión con tu correo.',
                ]);
            }

            session()->put('update_login_email.id', $user->id);
            session()->put('update_login_email.remember', $this->remember);
            $this->redirect(route('login.update-email'), navigate: true);
            return;
        }

        if ($user->two_factor_confirmed_at) {
            session()->put('two_factor_login.id', $user->id);
            session()->put('two_factor_login.remember', $this->remember);
            $this->redirect(route('two-factor.challenge'), navigate: true);
            return;
        }

        Auth::login($user, $this->remember);

        $this->redirect(route($this->redirectRouteFor($user->role)), navigate: true);
    }

    protected function redirectRouteFor(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'scholarship' => 'scholarship.dashboard',
            default => 'scholar.home',
        };
    }

    public function render()
    {
        return view('livewire.login');
    }
}
