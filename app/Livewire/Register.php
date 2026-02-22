<?php

namespace App\Livewire;

use App\Models\Community;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $institution = '';
    public string $studyLevel = '';
    public string $academicLevel = '';
    public string $communityId = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public $communities = [];

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route($this->redirectRouteFor(auth()->user()->role)), navigate: true);
            return;
        }

        $this->communities = Community::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'studyLevel' => 'nullable|string|max:255',
            'academicLevel' => 'nullable|string|max:255',
            'communityId' => 'required|exists:communities,id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'passwordConfirmation' => 'required|same:password',
        ];
    }

    public function register(): void
    {
        $validated = $this->validate();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => trim($validated['email']),
                'password' => $validated['password'],
                'role' => 'scholarship',
            ]);

            Scholarship::create([
                'name' => trim($validated['name']),
                'institution' => $validated['institution'],
                'academic_level' => $validated['academicLevel'] ?: null,
                'study_level' => $validated['studyLevel'] ?: null,
                'community_id' => (int) $validated['communityId'],
                'type' => 'new_entry',
                'user_id' => $user->id,
            ]);

            return $user;
        });

        Auth::login($user);

        $this->redirect(route($this->redirectRouteFor($user->role)), navigate: true);
    }

    protected function redirectRouteFor(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            default => 'scholar.home',
        };
    }

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.register');
    }
}
