<?php

namespace App\Livewire\Scholar\Reports;

use App\Models\Report;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Report $report;
    public $project = null;

    public $date = null;
    public $assists = [];
    public $theme = '';
    public $numberParticipants = null;
    public $description = '';
    public $obstacles = '';

    public $newImages = [];
    public $removedImageIds = [];

    public function mount(int $reportId): void
    {
        $projectId = auth()->user()?->scholarship?->project_id;

        $this->report = Report::with([
            'project.scholarships',
            'images',
            'assists',
        ])
            ->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->findOrFail($reportId);

        if (!$this->report->is_editable) {
            abort(403, 'Este reporte no se puede editar.');
        }

        $this->project = $this->report->project;

        $this->date = $this->report->date
            ? $this->report->date->format('Y-m-d\TH:i')
            : now()->locale('es')->translatedFormat('Y-m-d\TH:i');
        $this->assists = $this->report->assists->pluck('scholarship_id')->toArray();
        $this->theme = $this->report->theme ?? '';
        $this->numberParticipants = $this->report->number_participants ?? 0;
        $this->description = $this->report->description ?? '';
        $this->obstacles = $this->report->obstacles ?? '';
    }

    public function updatedAssists($value): void
    {
        $this->numberParticipants = count($value ?? []);
    }

    public function removeExistingImage(int $imageId): void
    {
        if (!in_array($imageId, $this->removedImageIds, true)) {
            $this->removedImageIds[] = $imageId;
        }
    }

    public function removeNewImage(int $index): void
    {
        if (isset($this->newImages[$index])) {
            array_splice($this->newImages, $index, 1);
        }
    }

    public function save(): void
    {
        $this->validate([
            'date' => 'required|date',
            'theme' => 'required|string|max:255',
            'numberParticipants' => 'required|integer|min:0',
            'description' => 'required|string',
            'obstacles' => 'required|string',
            'assists' => 'array',
            'newImages' => 'array',
            'newImages.*' => 'image|max:5120',
            'removedImageIds' => 'array',
        ]);

        try {
            DB::beginTransaction();

            $this->report->update([
                'theme' => $this->theme,
                'number_participants' => $this->numberParticipants,
                'description' => $this->description,
                'obstacles' => $this->obstacles,
                'date' => $this->date,
            ]);

            $this->report->assists()->delete();
            foreach ($this->assists as $assist) {
                $this->report->assists()->create([
                    'scholarship_id' => $assist,
                ]);
            }

            if (!empty($this->removedImageIds)) {
                $imagesToRemove = $this->report->images()
                    ->whereIn('id', $this->removedImageIds)
                    ->get();

                foreach ($imagesToRemove as $image) {
                    if ($image->path && Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete();
                }
            }

            foreach ($this->newImages as $image) {
                $slugProjectName = Str::slug($this->project->name);

                $this->report->images()->create([
                    'path' => $image->store(
                        "{$slugProjectName}/{$this->report->month}",
                        'public'
                    ),
                ]);
            }

            DB::commit();

            Flux::toast(
                heading: 'Reporte actualizado',
                text: 'Tu reporte se actualizo correctamente.',
                variant: 'success'
            );

            $this->redirect(route('scholar.reports.show', $this->report->id), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error al actualizar reporte', [
                'user_id' => auth()->id(),
                'report_id' => $this->report->id,
                'error_message' => $e->getMessage(),
            ]);
            DB::rollBack();

            Flux::toast(
                heading: 'Error al actualizar reporte',
                text: 'Ocurrio un error al guardar los cambios. Por favor intenta de nuevo.',
                variant: 'danger'
            );
        }
    }

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.reports.edit');
    }
}
