<?php

namespace App\Livewire\Admin\Projects;

use App\Models\Community;
use App\Models\Project;
use App\Models\Scholarship;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $communityFilter = '';
    public $month = null;

    public $projectId = null;
    public bool $assigned = false;
    public $selectedScholarships = [];

    public $communities = [];
    public $scholarships = [];

    public $showDelete = false;
    public $deleteProjectId = null;

    public function mount()
    {
        $this->communities = Community::all();
        $this->scholarships = Scholarship::all();
        $this->month = now()->locale('es')->monthName;
    }

    public function assignScholars($projectId)
    {
        $this->assigned = true;
        $project = Project::findOrFail($projectId);
        $this->projectId = $projectId;
        $this->selectedScholarships  = $project->scholarships()->pluck('id')->toArray();
    }

    public function saveAssignedScholars()
    {
        try {
            DB::beginTransaction();
            $project = Project::findOrFail($this->projectId);
            $selectedScholarships = array_values(array_filter($this->selectedScholarships));

            Scholarship::where('project_id', $project->id)
                ->when(count($selectedScholarships) > 0, function ($query) use ($selectedScholarships) {
                    $query->whereNotIn('id', $selectedScholarships);
                })
                ->update(['project_id' => null]);

            if (count($selectedScholarships) > 0) {
                Scholarship::whereIn('id', $selectedScholarships)
                    ->update(['project_id' => $project->id]);
            }

            $this->assigned = false;
            DB::commit();
            Flux::toast(
                heading: 'Becados asignados',
                text: 'Los becados han sido asignados al proyecto social exitosamente.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Log::error('Error al asignar becados al proyecto social', [
                'project_id' => $this->projectId,
                'selected_scholarships' => $this->selectedScholarships,
                'error_message' => $e->getMessage(),
            ]);
            DB::rollBack();
            Flux::toast(
                heading: 'Error al asignar becados',
                text: 'Ocurrió un error al asignar los becados al proyecto social. Por favor, intenta nuevamente.',
                variant: 'danger',
            );
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCommunityFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($projectId)
    {
        $this->deleteProjectId = $projectId;
        $this->showDelete = true;
    }

    public function deleteProject()
    {
        try {
            DB::beginTransaction();
            $project = Project::findOrFail($this->deleteProjectId);
            $project->delete();

            $this->showDelete = false;
            $this->deleteProjectId = null;
            DB::commit();
            Flux::toast(
                heading: 'Proyecto eliminado',
                text: 'El proyecto social ha sido eliminado exitosamente.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Log::error('Error al eliminar el proyecto social', [
                'project_id' => $this->deleteProjectId,
                'error_message' => $e->getMessage(),
            ]);
            DB::rollBack();
            Flux::toast(
                heading: 'Error al eliminar el proyecto',
                text: 'Ocurrió un error al eliminar el proyecto social. Por favor, intenta nuevamente.',
                variant: 'danger',
            );
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $projects = Project::query()
            ->orderBy('created_at', 'desc')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('community', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->communityFilter, function ($query) {
                $query->where('community_id', $this->communityFilter);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.projects.index', [
            'projects' => $projects,
        ]);
    }
}
