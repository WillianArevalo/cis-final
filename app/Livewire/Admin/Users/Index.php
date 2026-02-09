<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showEditor = false;
    public bool $showDeleteConfirm = false;

    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public ?string $email = null;
    public string $password = '';
    public string $password_confirmation = '';
    public bool $emailVerified = false;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules(): array
    {
        $passwordRule = $this->editingId ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed';

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'password' => $passwordRule,
            'emailVerified' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'El correo debe ser una dirección de correo válida.',
        'email.unique' => 'El correo ya está en uso por otro usuario.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetEditor();
        $this->showEditor = true;
    }

    public function edit(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->emailVerified = (bool) $user->email_verified_at;
        $this->password = '';
        $this->password_confirmation = '';

        $this->resetValidation();
        $this->showEditor = true;
    }

    public function save(): void
    {
        $validated = $this->validate();
        try {

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'email_verified_at' => $this->emailVerified ? now() : null,
            ];

            if (!empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            DB::beginTransaction();
            if ($this->editingId) {
                User::findOrFail($this->editingId)->update($data);
            } else {
                User::create($data);
            }
            DB::commit();
            $this->showEditor = false;
            $this->resetEditor();

            Flux::toast(
                heading: 'Éxito',
                text: 'Usuario guardado correctamente.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            Log::error("Error al guardar usuario: " . $e->getMessage(), ['exception' => $e]);
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrió un error al guardar el usuario.',
                variant: 'danger'
            );
        }
    }

    public function confirmDelete(int $userId): void
    {
        $this->resetValidation();
        $this->deletingId = $userId;
        $this->showDeleteConfirm = true;
    }

    public function delete(): void
    {
        if (!$this->deletingId) {
            return;
        }

        if (auth()->id() === $this->deletingId) {
            $this->addError('delete', 'No puedes eliminar tu propio usuario.');
            return;
        }

        User::findOrFail($this->deletingId)->delete();

        $this->showDeleteConfirm = false;
        $this->deletingId = null;
    }

    private function resetEditor(): void
    {
        $this->reset([
            'editingId',
            'name',
            'email',
            'password',
            'password_confirmation',
            'emailVerified',
        ]);

        $this->resetValidation();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }
}
