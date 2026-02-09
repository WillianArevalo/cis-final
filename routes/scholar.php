<?php

use App\Livewire\Scholar\Home;
use App\Livewire\Scholar\Profile;
use App\Livewire\Scholar\ScholarProject;
use App\Livewire\Scholar\Reports\Index as ScholarReportsIndex;
use App\Livewire\Scholar\Reports\Create as ScholarReportsCreate;
use App\Livewire\Scholar\Reports\Edit as ScholarReportsEdit;
use App\Livewire\Scholar\Reports\Show as ScholarReportsShow;


use Illuminate\Support\Facades\Route;

Route::prefix('becado')->name('scholar.')->group(function () {
    Route::get('/inicio', Home::class)->name('home');
    Route::get('/reportes', ScholarReportsIndex::class)->name('reports.index');
    Route::get('/reportes/crear/{month}', ScholarReportsCreate::class)->name('reports.create');
    Route::get('/reportes/{reportId}/editar', ScholarReportsEdit::class)->name('reports.edit');
    Route::get('/reportes/{reportId}', ScholarReportsShow::class)->name('reports.show');
    Route::get('/perfil', Profile::class)->name('profile');
    Route::get('/proyecto', ScholarProject::class)->name('project');
});
