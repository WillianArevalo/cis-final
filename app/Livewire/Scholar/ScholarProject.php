<?php

namespace App\Livewire\Scholar;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Community;
use App\Models\Project;
use App\Models\Scholarship;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class ScholarProject extends Component
{
    use WithFileUploads;

    public ?int $projectId = null;
    public $project = null;
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
    public array $scholarshipsIds = [];

    public $communities = [];
    public $scholars = [];

    public function mount(): void
    {
        $user = auth()->user();
        $scholarship = $user?->scholarship;

        if (!$scholarship) {
            abort(403, 'No tienes una beca asignada.');
        }

        $this->project = $scholarship->project;
        $this->projectId = $this->project?->id;
        $this->name = $this->project?->name ?? '';
        $this->communityId = $this->project?->community_id ?? $scholarship->community_id;
        $this->accept = (bool) ($this->project?->accept ?? false);
        $this->currentDocument = $this->project?->document;
        $this->currentMap = $this->project?->map;
        $this->currentSchedule = $this->project?->schedule ?? '';
        $this->sentBy = $this->project?->sent_by ?? $user->id;
        $this->benefitedPopulation = $this->project?->benefited_population ?? '';
        $this->generalObjective = $this->project?->general_objective ?? '';
        $this->justification = $this->project?->justification ?? '';
        $this->location = $this->project?->location ?? '';
        $this->contextualization = $this->project?->contextualization ?? '';
        $this->descriptionActivities = $this->project?->description_activities ?? '';
        $this->projections = $this->project?->projections ?? '';
        $this->challenges = $this->project?->challenges ?? '';
        $this->specificObjectives = $this->project
            ? $this->project->specificObjetives()->pluck('specific_objective')->toArray()
            : [];

        if (count($this->specificObjectives) === 0) {
            $this->specificObjectives = [''];
        }

        $this->communities = Community::all();
        $this->scholars = Scholarship::whereNull('project_id')->get();
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

            $user = auth()->user();
            $scholarship = $user?->scholarship;
            $isUpdate = (bool) $this->projectId;

            if (!$scholarship) {
                abort(403, 'No tienes una beca asignada.');
            }

            $data = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'community_id' => $validated['communityId'] ?? null,
                'accept' => $isUpdate ? ($validated['accept'] ?? false) : false,
                'document' => $this->currentDocument,
                'map' => $this->currentMap,
                'sent_by' => $this->sentBy ?? $user->id,
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

                $documentName = "documento-proyecto" . '.' . $validated['document']->getClientOriginalExtension();
                $documentPath = $validated['document']->storeAs('projects/documents', $documentName, 'public');
                $data['document'] = $documentPath;
                $this->currentDocument = $documentPath;
            }

            if (!empty($validated['schedule'])) {
                if ($this->currentSchedule) {
                    Storage::disk('public')->delete($this->currentSchedule);
                }

                $scheduleName = "cronograma-proyecto" . '.' . $validated['schedule']->getClientOriginalExtension();
                $schedulePath = $validated['schedule']->storeAs('projects/schedules', $scheduleName, 'public');
                $data['schedule'] = $schedulePath;
                $this->currentSchedule = $schedulePath;
            }

            if (!empty($validated['map'])) {
                if ($this->currentMap) {
                    Storage::disk('public')->delete($this->currentMap);
                }

                $mapName = "mapa-proyecto" . '.' . $validated['map']->getClientOriginalExtension();
                $mapPath = $validated['map']->storeAs('projects/maps', $mapName, 'public');
                $data['map'] = $mapPath;
                $this->currentMap = $mapPath;
            }

            if ($isUpdate) {
                $project = Project::findOrFail($this->projectId);
                $project->update($data);
            } else {
                $project = Project::create($data);
                $scholarship->update([
                    'project_id' => $project->id,
                ]);
                $this->projectId = $project->id;
                $this->project = $project;
            }

            $objectives = collect($this->specificObjectives)
                ->map(fn($objective) => trim((string) $objective))
                ->filter()
                ->unique()
                ->values();

            $project->specificObjetives()->delete();

            if ($objectives->count() > 0) {
                $project->specificObjetives()->createMany(
                    $objectives->map(fn($objective) => [
                        'specific_objective' => $objective,
                    ])->all()
                );
            }

            DB::commit();
            Flux::toast(
                heading: $isUpdate ? 'Proyecto actualizado' : 'Proyecto creado',
                text: $isUpdate
                    ? 'El proyecto se actualizo exitosamente.'
                    : 'El proyecto fue creado y queda pendiente de aprobacion.',
                variant: 'success'
            );

            $this->redirect(route('scholar.project'), navigate: true);
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

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.project');
    }
}
