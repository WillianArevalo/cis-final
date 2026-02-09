<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public function logout(): void
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }
};
?>

<div>
    <form method="POST" wire:submit.prevent="logout" class="w-full">
        @csrf
        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            Cerrar sesión
        </flux:menu.item>
    </form>
</div>
