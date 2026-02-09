<?php

namespace App\Livewire\Admin\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Project $project;

    public function mount(int $projectId): void
    {
        $this->project = Project::with([
            'community',
            'scholarships',
            'sentBy',
            'specificObjetives',
        ])->findOrFail($projectId);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.admin.projects.show');
    }
}
