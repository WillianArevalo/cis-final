<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginFallbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $identifier = trim($validated['identifier']);
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;
        $user = $isEmail
            ? User::where('email', $identifier)->first()
            : User::where('name', $identifier)->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['identifier' => 'Las credenciales proporcionadas son incorrectas.'])
                ->withInput($request->except('password'));
        }

        $remember = (bool) ($validated['remember'] ?? false);

        if (!$isEmail) {
            if (filled($user->email)) {
                return back()
                    ->withErrors(['identifier' => 'Debes iniciar sesion con tu correo.'])
                    ->withInput($request->except('password'));
            }

            session()->put('update_login_email.id', $user->id);
            session()->put('update_login_email.remember', $remember);

            return redirect()->route('login.update-email');
        }

        if ($user->two_factor_confirmed_at) {
            session()->put('two_factor_login.id', $user->id);
            session()->put('two_factor_login.remember', $remember);

            return redirect()->route('two-factor.challenge');
        }

        Auth::login($user, $remember);

        return redirect()->route($this->redirectRouteFor($user->role));
    }

    protected function redirectRouteFor(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'scholarship' => 'scholar.home',
            default => 'scholar.home',
        };
    }
}
