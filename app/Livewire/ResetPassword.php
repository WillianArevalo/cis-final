<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ResetPassword extends Component
{
    public string $token;
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $tokenValid = true;

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function mount(string $token): void
    {
        if (Auth::check()) {
            $this->redirect(route($this->redirectRouteFor(auth()->user()->role)), navigate: true);
            return;
        }

        $this->token = $token;
        $this->email = (string) request()->query('email', '');
        $this->tokenValid = $this->email !== '' && $this->tokenIsValid();
    }

    public function resetPassword(): void
    {
        $this->validate();

        if ($this->email === '') {
            $this->tokenValid = false;
            $this->addError('password', 'El enlace de restablecimiento no es valido o ya expiro.');
            return;
        }

        if (!$this->tokenIsValid()) {
            $this->tokenValid = false;
            $this->addError('password', 'El enlace de restablecimiento no es valido o ya expiro.');
            return;
        }

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('password', __($status));
            return;
        }

        Flux::toast(
            heading: 'Contrasena actualizada',
            text: 'Ya puedes iniciar sesion con tu nueva contrasena.',
            variant: 'success'
        );

        $this->redirect(route('login'), navigate: true);
    }

    protected function tokenIsValid(): bool
    {
        if ($this->email === '') {
            return false;
        }

        $broker = Password::broker();
        $user = $broker->getUser(['email' => $this->email]);

        if (!$user) {
            return false;
        }

        return $broker->tokenExists($user, $this->token);
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
        return view('livewire.reset-password');
    }
}
