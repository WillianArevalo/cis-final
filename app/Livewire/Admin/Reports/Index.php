<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Project;
use App\Models\Report;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $project = null;

    public $showDelete = false;
    public $reportToDelete = null;

    public function mount($projectId)
    {
        $this->project = Project::findOrFail($projectId);
    }

    public function makeEditable($reportId)
    {
        try {
            DB::beginTransaction();
            $report = Report::findOrFail($reportId);
            $report->is_editable = true;
            $report->save();
            DB::commit();
            Flux::toast(
                heading: 'Reporte editable',
                text: 'El reporte ahora es editable.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'No se pudo hacer el reporte editable.',
                variant: 'danger',
            );
        }
    }

    public function makeNonEditable($reportId)
    {
        try {
            DB::beginTransaction();
            $report = Report::findOrFail($reportId);
            $report->is_editable = false;
            $report->save();
            DB::commit();
            Flux::toast(
                heading: 'Reporte no editable',
                text: 'El reporte ya no es editable.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'No se pudo quitar la editabilidad del reporte.',
                variant: 'danger',
            );
        }
    }

    public function confirmDelete($reportId)
    {
        $this->reportToDelete = $reportId;
        $this->showDelete = true;
    }

    public function deleteReport()
    {
        try {
            DB::beginTransaction();
            $report = Report::findOrFail($this->reportToDelete);

            if ($report->images()->count() > 0) {
                foreach ($report->images as $image) {
                    if ($image->path && Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                }
            }

            $report->delete();
            DB::commit();
            Flux::toast(
                heading: 'Reporte eliminado',
                text: 'El reporte ha sido eliminado exitosamente.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Flux::toast(
                heading: 'Error',
                text: 'No se pudo eliminar el reporte.',
                variant: 'danger',
            );
        } finally {
            $this->showDelete = false;
            $this->reportToDelete = null;
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.admin.reports.index', [
            'project' => $this->project,
        ]);
    }
}
