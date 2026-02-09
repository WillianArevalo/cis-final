<?php

namespace App\Livewire\Admin;

use App\Models\Community;
use App\Models\Project;
use App\Models\Report;
use App\Models\Scholarship;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public string $currentMonth = '';
    public array $stats = [];
    public $projects = [];

    public function mount(): void
    {
        $this->currentMonth = now()->locale('es')->monthName;
        $monthKey = Str::lower($this->currentMonth);

        $projects = Project::with([
            'community',
            'scholarships',
            'reports' => function ($query) use ($monthKey) {
                $query->where('month', $monthKey);
            },
        ])->orderBy('name')->get();

        $projectsWithReport = $projects->filter(function ($project) {
            return $project->reports->isNotEmpty();
        })->count();

        $this->stats = [
            'projects' => $projects->count(),
            'communities' => Community::count(),
            'scholarships' => Scholarship::count(),
            'reportsThisMonth' => Report::where('month', $monthKey)->count(),
            'projectsWithReport' => $projectsWithReport,
            'projectsWithoutReport' => $projects->count() - $projectsWithReport,
        ];

        $this->projects = $projects;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
