<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ForgotPassword extends Component
{
    public string $email = '';
    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route($this->redirectRouteFor(auth()->user()->role)), navigate: true);
        }
    }

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_THROTTLED) {
            $this->addError('email', 'Espera un momento antes de solicitar otro enlace.');
            return;
        }

        $this->sent = true;

        Flux::toast(
            heading: 'Solicitud enviada',
            text: 'Si el correo esta registrado, enviaremos un enlace para cambiar tu contrasena.',
            variant: 'success'
        );
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
        return view('livewire.forgot-password');
    }
}
