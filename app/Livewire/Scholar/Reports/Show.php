<?php

namespace App\Livewire\Scholar\Reports;

use App\Models\Report;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public ?Report $report = null;

    public function mount(int $reportId): void
    {
        $projectId = auth()->user()?->scholarship?->project_id;

        $this->report = Report::with([
            'project.community',
            'user',
            'images',
            'assists.scholarship',
        ])
            ->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->findOrFail($reportId);
    }

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.reports.show');
    }
}
