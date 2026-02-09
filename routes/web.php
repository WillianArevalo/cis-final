<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Communities\Index as CommunitiesIndex;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\Projects\Index as ProjectsIndex;
use App\Livewire\Admin\Projects\Create as ProjectsCreate;
use App\Livewire\Admin\Projects\Show as ProjectsShow;
use App\Livewire\Admin\Projects\Edit as ProjectsEdit;
use App\Livewire\Admin\Scholars\Index as ScholarsIndex;
use App\Livewire\Admin\Reports\Index as ReportsIndex;
use App\Livewire\Admin\Reports\Show as ReportsShow;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Users\Index as UsersIndex;

use App\Livewire\Login;
use App\Livewire\UpdateLoginEmail;

Route::get('/', Login::class)->name('home');
Route::get('/login', Login::class)->name('login');
Route::get('/login/actualizar-correo', UpdateLoginEmail::class)->name('login.update-email');

Route::middleware(["auth", "role:admin"])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/usuarios', UsersIndex::class)->name('users.index');
        Route::get('/comunidades', CommunitiesIndex::class)->name('communities.index');
        Route::get('/proyectos', ProjectsIndex::class)->name('projects.index');
        Route::get('/proyectos/crear', ProjectsCreate::class)->name('projects.create');
        Route::get('/proyectos/editar/{projectId}', ProjectsEdit::class)->name('projects.edit');
        Route::get('/proyectos/detalle/{projectId}', ProjectsShow::class)->name('projects.show');
        Route::get('/becados', ScholarsIndex::class)->name('scholars.index');
        Route::get('/configuracion', ScholarsIndex::class)->name('settings.index');

        Route::get('/reportes/detalle/{reportId}', ReportsShow::class)->name('reports.show');
        Route::get('/reportes/{projectId?}', ReportsIndex::class)->name('reports.index');

        Route::get('/perfil', Profile::class)->name('profile');
        Route::get('/configuracion', Settings::class)->name('settings');
    });
});

require __DIR__ . '/scholar.php';
