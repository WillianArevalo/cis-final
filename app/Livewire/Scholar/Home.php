<?php

namespace App\Livewire\Scholar;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    public $scholarship = null;
    public $project = null;
    public $month = null;
    public $reportOfTheMonth = null;

    public function mount()
    {
        $this->scholarship = auth()->user()->scholarship;
        $this->project = $this->scholarship?->project;

        $this->month = now()->locale('es')->monthName;
        if ($this->project) {

            $this->reportOfTheMonth = $this->project->reports()->where("month", $this->month)->first();
        }
    }

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.home');
    }
}
