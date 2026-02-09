<?php

namespace App\Livewire\Scholar\Reports;

use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $month = null;
    public $scholarship = null;
    public $project = null;

    public $date = null;
    public $assists = [];
    public $theme = '';
    public $numberParticipants = null;
    public $description = '';
    public $obstacles = '';
    public $images = [];

    public function mount($month)
    {
        $this->month = $month;
        $this->scholarship = auth()->user()?->scholarship;
        $this->project = $this->scholarship?->project;

        if (now()->locale('es')->month((int)$month)->isAfter(now())) {
            abort(403, 'No puedes enviar un reporte para un mes futuro.');
        }

        if (!$this->project) {
            abort(403, 'No tienes un proyecto asignado.');
        }

        if (!$this->project->accept) {
            abort(403, 'Tu proyecto aún no ha sido aceptado por el comité.');
        }

        $existingReport = $this->project->reports()->where('month', $month)->first();
        if ($existingReport) {
            abort(403, 'Ya has enviado un reporte para este mes.');
        }

        $this->date = now()->locale('es')->translatedFormat('Y-m-d\TH:i');
    }

    public function updatedAssists($value)
    {
        $this->numberParticipants = count($value);
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            array_splice($this->images, $index, 1);
        }
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'theme' => 'required|string|max:255',
            'numberParticipants' => 'required|integer|min:0',
            'description' => 'required|string',
            'obstacles' => 'required|string',
            'images.*' => 'required|image|max:5120', // 5MB max per image
        ]);

        try {
            DB::beginTransaction();
            $report = $this->project->reports()->create([
                'month' => $this->month,
                'theme' => $this->theme,
                'number_participants' => $this->numberParticipants,
                'description' => $this->description,
                'obstacles' => $this->obstacles,
                'sent_by' => auth()->id(),
                'date' => $this->date,
            ]);

            foreach ($this->assists as $assist) {
                $report->assists()->create([
                    'scholarship_id' => $assist,
                ]);
            }

            foreach ($this->images as $image) {
                $slugProjectName = Str::slug($this->project->name);

                $report->images()->create([
                    'path' => $image->store(
                        "{$slugProjectName}/{$this->month}",
                        'public'
                    ),
                ]);
            }
            DB::commit();

            Flux::toast(
                heading: 'Reporte enviado',
                text: 'Tu reporte ha sido enviado exitosamente.',
                variant: 'success'
            );

            $this->redirect(route('scholar.reports.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error al enviar reporte', [
                'user_id' => auth()->id(),
                'project_id' => $this->project?->id,
                'month' => $this->month,
                'error_message' => $e->getMessage(),
            ]);
            DB::rollBack();
            Flux::toast(
                heading: 'Error al enviar reporte',
                text: 'Ocurrió un error al enviar tu reporte. Por favor intenta de nuevo.',
                variant: 'danger'
            );
        }
    }

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.reports.create');
    }
}
