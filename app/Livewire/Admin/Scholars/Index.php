<?php

namespace App\Livewire\Admin\Scholars;

use App\Models\Community;
use App\Models\Project;
use App\Models\Scholarship;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = "";
    public $perPage = 10;

    public $name = "";
    public $currentPhoto = "";
    public $photo = "";
    public $institution = "";
    public $academicLevel = "";
    public $career = "";
    public $studyLevel = "";
    public $communityId = null;
    public $projectId = null;
    public $type = "";
    public $phone = "";

    public bool $showEditor = false;
    public bool $showDeleteConfirm = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public bool $showBulkDelete = false;
    public $scholar = null;

    public $communities = [];
    public $projects = [];

    public $selected = [];
    public $selectAll = false;

    public function mount()
    {
        $this->communities = Community::all();
        $this->projects = Project::all();
    }

    public function create(): void
    {
        $this->resetEditor();
        $this->scholar = null;
        $this->showEditor = true;
    }

    public function edit(int $scholarId): void
    {
        $scholar = Scholarship::findOrFail($scholarId);
        $this->scholar = $scholar;

        $this->editingId = $scholar->id;
        $this->name = $scholar->name;
        $this->currentPhoto = $scholar->photo;
        $this->photo = "";
        $this->institution = $scholar->institution;
        $this->academicLevel = $scholar->academic_level;
        $this->career = $scholar->career;
        $this->studyLevel = $scholar->study_level;
        $this->communityId = $scholar->community_id;
        $this->projectId = $scholar->project_id ?? "";
        $this->type = $scholar->type;
        $this->phone = $scholar->phone;

        $this->resetValidation();
        $this->showEditor = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'institution' => 'nullable|string|max:255',
            'academicLevel' => 'nullable|string|max:255',
            'career' => 'nullable|string|max:255',
            'studyLevel' => 'nullable|string|max:255',
            'communityId' => 'nullable|exists:communities,id',
            'projectId' => 'nullable|exists:projects,id',
            'type' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'photo.image' => 'La foto debe ser una imagen válida.',
            'photo.max' => 'La foto no puede exceder los 255 caracteres.',
            'institution.string' => 'La institución debe ser una cadena de texto.',
            'institution.max' => 'La institución no puede exceder los 255 caracteres.',
            'academicLevel.string' => 'El nivel académico debe ser una cadena de texto.',
            'academicLevel.max' => 'El nivel académico no puede exceder los 255 caracteres.',
            'career.string' => 'La carrera debe ser una cadena de texto.',
            'career.max' => 'La carrera no puede exceder los 255 caracteres.',
            'studyLevel.string' => 'El nivel educativo debe ser una cadena de texto.',
            'studyLevel.max' => 'El nivel educativo no puede exceder los 255 caracteres.',
            'communityId.exists' => 'La comunidad seleccionada no existe.',
            'projectId.exists' => 'El proyecto seleccionado no existe.',
            'type.string' => 'El tipo debe ser una cadena de texto.',
            'type.max' => 'El tipo no puede exceder los 255 caracteres.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no puede exceder los 20 caracteres.',
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'photo' => $validated['photo'] ?? $this->currentPhoto,
                'institution' => $validated['institution'] ?? null,
                'academic_level' => $validated['academicLevel'] ?? null,
                'career' => $validated['career'] ?? null,
                'study_level' => $validated['studyLevel'] ?? null,
                'community_id' => $validated['communityId'] ?? null,
                'project_id' => $validated['projectId'] ?? null,
                'type' => $validated['type'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ];

            DB::beginTransaction();
            if ($this->editingId) {
                if (!empty($validated['photo'])) {
                    $fileName = $validated["name"] . '.' . $validated["photo"]->getClientOriginalExtension();
                    $photoPath = $validated['photo']->storeAs('scholars', $fileName, 'public');
                    $data['photo'] = $photoPath;
                }

                Scholarship::findOrFail($this->editingId)->update($data);
            } else {
                Scholarship::create($data);
            }

            $this->showEditor = false;
            $this->resetEditor();
            DB::commit();
            Flux::toast(
                heading: $this->editingId ? 'Becario actualizado' : 'Becario creado',
                text: $this->editingId ? 'El becario ha sido actualizado exitosamente.' : 'El becario ha sido creado exitosamente.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrió un error al guardar el becario. Por favor, intenta nuevamente.',
                variant: 'danger'
            );
        }
    }

    public function confirmDelete(int $scholarId): void
    {
        $this->deletingId = $scholarId;
        $this->showDeleteConfirm = true;
    }

    public function delete(): void
    {
        if (!$this->deletingId) {
            return;
        }

        try {
            DB::beginTransaction();
            $scholar = Scholarship::findOrFail($this->deletingId);
            $scholar->delete();
            $this->showDeleteConfirm = false;
            $this->deletingId = null;
            DB::commit();
            Flux::toast(
                heading: 'Becario eliminado',
                text: 'El becario ha sido eliminado exitosamente.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrió un error al eliminar el becario. Por favor, intenta nuevamente.',
                variant: 'danger'
            );
        }
    }

    public function resetEditor(): void
    {
        $this->editingId = null;
        $this->name = "";
        $this->photo = "";
        $this->institution = "";
        $this->academicLevel = "";
        $this->career = "";
        $this->studyLevel = "";
        $this->communityId = null;
        $this->projectId = null;
        $this->type = "";
        $this->phone = "";
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selected = Scholarship::query()
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('institution', 'like', '%' . $this->search . '%')
                ->orWhereHas('community', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->pluck('id')
                ->take($this->perPage)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        $total = Scholarship::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('institution', 'like', '%' . $this->search . '%')
            ->orWhereHas('community', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->count();

        $this->selectAll = count($this->selected) === $total;
    }

    public function deleteBulk(): void
    {
        if (empty($this->selected)) {
            return;
        }

        try {
            DB::beginTransaction();

            $scholars = Scholarship::whereIn('id', $this->selected)->get();
            foreach ($scholars as $scholar) {
                $scholar->delete();
            }

            Scholarship::whereIn('id', $this->selected)->delete();
            $this->showBulkDelete = false;
            $this->selected = [];
            $this->selectAll = false;
            DB::commit();
            Flux::toast("delete-bulk")->close();
            Flux::toast(
                heading: 'Becarios eliminados',
                text: 'Los becarios seleccionados han sido eliminados exitosamente.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            Log::error("Error al eliminar becarios seleccionados: " . $e->getMessage(), ['exception' => $e]);
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrió un error al eliminar los becarios seleccionados. Por favor, intenta nuevamente.',
                variant: 'danger'
            );
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $scholars = Scholarship::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('institution', 'like', '%' . $this->search . '%')
            ->orWhereHas('community', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.scholars.index', compact('scholars'));
    }
}
