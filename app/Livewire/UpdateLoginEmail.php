<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class UpdateLoginEmail extends Component
{
    public string $email = '';
    public string $userName = '';

    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route($this->redirectRouteFor(auth()->user()->role)), navigate: true);
            return;
        }

        $userId = session()->get('update_login_email.id');
        if (!$userId) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget(['update_login_email.id', 'update_login_email.remember']);
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $this->userName = $user->name;
    }

    public function save(): void
    {
        $this->validate();

        $userId = session()->get('update_login_email.id');
        if (!$userId) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget(['update_login_email.id', 'update_login_email.remember']);
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user->email = $this->email;
        $user->save();

        if ($user->two_factor_confirmed_at) {
            session()->put('two_factor_login.id', $user->id);
            session()->put('two_factor_login.remember', session()->get('update_login_email.remember', false));
            session()->forget(['update_login_email.id', 'update_login_email.remember']);
            $this->redirect(route('two-factor.challenge'), navigate: true);
            return;
        }

        Auth::login($user, session()->get('update_login_email.remember', false));
        session()->forget(['update_login_email.id', 'update_login_email.remember']);

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
        return view('livewire.update-login-email');
    }
}
