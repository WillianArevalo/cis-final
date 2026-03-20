<?php

namespace App\Livewire\Admin\Projects;

use App\Models\Community;
use App\Models\Project;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public int $projectId;
    public string $name = '';
    public ?int $communityId = null;
    public bool $accept = false;
    public $document = null;
    public ?string $currentDocument = null;
    public $map = null;
    public ?string $currentMap = null;
    public $schedule = null;
    public ?string $currentSchedule = null;
    public ?int $sentBy = null;
    public string $benefitedPopulation = '';
    public string $generalObjective = '';
    public string $justification = '';
    public string $location = '';
    public string $contextualization = '';
    public string $descriptionActivities = '';
    public string $projections = '';
    public string $challenges = '';
    public array $specificObjectives = [];

    public $communities = [];

    public function mount(int $projectId): void
    {
        $project = Project::findOrFail($projectId);

        $this->projectId = $project->id;
        $this->name = $project->name ?? '';
        $this->communityId = $project->community_id;
        $this->accept = (bool) $project->accept;
        $this->currentDocument = $project->document;
        $this->currentMap = $project->map;
        $this->currentSchedule = $project->schedule ?? '';
        $this->sentBy = $project->sent_by;
        $this->benefitedPopulation = $project->benefited_population ?? '';
        $this->generalObjective = $project->general_objective ?? '';
        $this->justification = $project->justification ?? '';
        $this->location = $project->location ?? '';
        $this->contextualization = $project->contextualization ?? '';
        $this->descriptionActivities = $project->description_activities ?? '';
        $this->projections = $project->projections ?? '';
        $this->challenges = $project->challenges ?? '';
        $this->specificObjectives = $project->specificObjetives()
            ->pluck('specific_objective')
            ->toArray();

        if (count($this->specificObjectives) === 0) {
            $this->specificObjectives = [''];
        }

        $this->communities = Community::all();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'communityId' => 'nullable|exists:communities,id',
            'accept' => 'boolean',
            'document' => 'nullable|file|mimes:pdf|max:5120',
            'map' => 'nullable|image|max:5120',
            'benefitedPopulation' => 'nullable|string',
            'generalObjective' => 'nullable|string',
            'justification' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'contextualization' => 'nullable|string',
            'descriptionActivities' => 'nullable|string',
            'projections' => 'nullable|string',
            'challenges' => 'nullable|string',
            'schedule' => 'nullable|file|mimes:pdf|max:5120',
            'specificObjectives' => 'array',
            'specificObjectives.*' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'communityId.exists' => 'La comunidad seleccionada no existe.',
            'document.mimes' => 'El documento debe ser un PDF.',
            'document.max' => 'El documento no puede exceder los 5MB.',
            'map.image' => 'El mapa debe ser una imagen valida.',
            'map.max' => 'El mapa no puede exceder los 5MB.',
            'location.max' => 'La ubicacion no puede exceder los 255 caracteres.',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'community_id' => $validated['communityId'] ?? null,
                'accept' => $validated['accept'] ?? false,
                'document' => $this->currentDocument,
                'map' => $this->currentMap,
                'sent_by' => $this->sentBy,
                'benefited_population' => $validated['benefitedPopulation'] ?? null,
                'general_objective' => $validated['generalObjective'] ?? null,
                'justification' => $validated['justification'] ?? null,
                'location' => $validated['location'] ?? null,
                'contextualization' => $validated['contextualization'] ?? null,
                'description_activities' => $validated['descriptionActivities'] ?? null,
                'projections' => $validated['projections'] ?? null,
                'challenges' => $validated['challenges'] ?? null,
                'schedule' => $this->currentSchedule,
            ];

            if (!empty($validated['document'])) {
                if ($this->currentDocument) {
                    Storage::disk('public')->delete($this->currentDocument);
                }

                $documentName = Str::slug($validated['name']) . '-document-' . now()->timestamp . '.' . $validated['document']->getClientOriginalExtension();
                $documentPath = $validated['document']->storeAs('projects/documents', $documentName, 'public');
                $data['document'] = $documentPath;
                $this->currentDocument = $documentPath;
            }

            if (!empty($validated['schedule'])) {
                if ($this->currentSchedule) {
                    Storage::disk('public')->delete($this->currentSchedule);
                }

                $scheduleName = Str::slug($validated['name']) . '-schedule-' . now()->timestamp . '.' . $validated['schedule']->getClientOriginalExtension();
                $schedulePath = $validated['schedule']->storeAs('projects/schedules', $scheduleName, 'public');
                $data['schedule'] = $schedulePath;
                $this->currentSchedule = $schedulePath;
            }

            if (!empty($validated['map'])) {
                if ($this->currentMap) {
                    Storage::disk('public')->delete($this->currentMap);
                }

                $mapName = Str::slug($validated['name']) . '-map-' . now()->timestamp . '.' . $validated['map']->getClientOriginalExtension();
                $mapPath = $validated['map']->storeAs('projects/maps', $mapName, 'public');
                $data['map'] = $mapPath;
                $this->currentMap = $mapPath;
            }

            $project = Project::findOrFail($this->projectId);
            $project->update($data);

            $objectives = collect($this->specificObjectives)
                ->map(fn ($objective) => trim((string) $objective))
                ->filter()
                ->unique()
                ->values();

            $project->specificObjetives()->delete();

            if ($objectives->count() > 0) {
                $project->specificObjetives()->createMany(
                    $objectives->map(fn ($objective) => [
                        'specific_objective' => $objective,
                    ])->all()
                );
            }

            DB::commit();
            Flux::toast(
                heading: 'Proyecto actualizado',
                text: 'El proyecto se actualizo exitosamente.',
                variant: 'success'
            );

            $this->redirect(route('admin.projects.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrio un error al actualizar el proyecto. Por favor, intenta nuevamente.',
                variant: 'danger'
            );
        }
    }

    public function removeDocument(): void
    {
        if ($this->currentDocument) {
            Storage::disk('public')->delete($this->currentDocument);
        }

        $this->currentDocument = null;
    }

    public function removeMap(): void
    {
        if ($this->currentMap) {
            Storage::disk('public')->delete($this->currentMap);
        }

        $this->currentMap = null;
    }

    public function removeSchedule(): void
    {
        if ($this->currentSchedule) {
            Storage::disk('public')->delete($this->currentSchedule);
        }

        $this->currentSchedule = null;
    }

    public function addSpecificObjective(): void
    {
        $this->specificObjectives[] = '';
    }

    public function removeSpecificObjective(int $index): void
    {
        unset($this->specificObjectives[$index]);
        $this->specificObjectives = array_values($this->specificObjectives);

        if (count($this->specificObjectives) === 0) {
            $this->specificObjectives = [''];
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.admin.projects.edit');
    }
}
