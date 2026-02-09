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

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public ?int $communityId = null;
    public bool $accept = false;
    public $document = null;
    public $map = null;
    public $schedule = null;
    public ?int $sentBy = null;
    public string $benefitedPopulation = '';
    public string $generalObjective = '';
    public string $justification = '';
    public string $location = '';
    public string $contextualization = '';
    public string $descriptionActivities = '';
    public string $projections = '';
    public string $challenges = '';
    public array $specificObjectives = [''];

    public $communities = [];

    public function mount(): void
    {
        $this->communities = Community::all();
        $this->sentBy = auth()->id();
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
                'document' => null,
                'map' => null,
                'sent_by' => $this->sentBy,
                'benefited_population' => $validated['benefitedPopulation'] ?? null,
                'general_objective' => $validated['generalObjective'] ?? null,
                'justification' => $validated['justification'] ?? null,
                'location' => $validated['location'] ?? null,
                'contextualization' => $validated['contextualization'] ?? null,
                'description_activities' => $validated['descriptionActivities'] ?? null,
                'projections' => $validated['projections'] ?? null,
                'challenges' => $validated['challenges'] ?? null,
                'schedule' => null,
            ];

            if (!empty($validated['document'])) {
                $documentName = Str::slug($validated['name']) . '-document-' . now()->timestamp . '.' . $validated['document']->getClientOriginalExtension();
                $data['document'] = $validated['document']->storeAs('projects/documents', $documentName, 'public');
            }

            if (!empty($validated['schedule'])) {
                $scheduleName = Str::slug($validated['name']) . '-schedule-' . now()->timestamp . '.' . $validated['schedule']->getClientOriginalExtension();
                $data['schedule'] = $validated['schedule']->storeAs('projects/schedules', $scheduleName, 'public');
            }

            if (!empty($validated['map'])) {
                $mapName = Str::slug($validated['name']) . '-map-' . now()->timestamp . '.' . $validated['map']->getClientOriginalExtension();
                $data['map'] = $validated['map']->storeAs('projects/maps', $mapName, 'public');
            }

            $project = Project::create($data);

            $objectives = collect($this->specificObjectives)
                ->map(fn ($objective) => trim((string) $objective))
                ->filter()
                ->unique()
                ->values();

            if ($objectives->count() > 0) {
                $project->specificObjetives()->createMany(
                    $objectives->map(fn ($objective) => [
                        'specific_objective' => $objective,
                    ])->all()
                );
            }

            DB::commit();
            Flux::toast(
                heading: 'Proyecto creado',
                text: 'El proyecto se creo exitosamente.',
                variant: 'success'
            );

            $this->redirect(route('admin.projects.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'Ocurrio un error al crear el proyecto. Por favor, intenta nuevamente.',
                variant: 'danger'
            );
        }
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
        return view('livewire.admin.projects.create');
    }
}
