<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

class Profile extends Component
{
    public $user = null;
    public $name = null;
    public $email = null;
    public $password = null;
    public $currentPassword = null;
    public $confirmPassword = null;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'currentPassword' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'confirmPassword' => 'nullable|string|min:8|same:password',
        ]);
        try {

            if ($this->password) {
                if (!Hash::check($this->currentPassword, $this->user->password)) {
                    $this->addError('currentPassword', 'La contraseña actual es incorrecta.');
                    return;
                }
                $this->user->password = Hash::make($this->password);
            }
            DB::beginTransaction();
            $this->user->name = $this->name;
            $this->user->save();
            DB::commit();
            Flux::toast(
                heading: 'Perfil actualizado',
                text: 'Tu perfil ha sido actualizado exitosamente.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error al actualizar perfil',
                text: 'Ocurrió un error al actualizar tu perfil. Por favor intenta de nuevo.',
                variant: 'danger'
            );
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.admin.profile');
    }
}
