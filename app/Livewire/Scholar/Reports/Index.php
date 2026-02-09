<?php

namespace App\Livewire\Scholar\Reports;

use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $scholarship = null;
    public ?Project $project = null;
    public array $cards = [];

    public function mount(): void
    {
        $this->scholarship = auth()->user()?->scholarship;
        $this->project = $this->scholarship?->project;

        if (!$this->project) {
            return;
        }

        $now = now()->locale('es');
        $currentMonthIndex = (int) $now->month;
        $reports = $this->project->reports()->get()
            ->keyBy(fn ($report) => Str::lower($report->month));

        $this->cards = [];

        for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++) {
            $monthName = now()
                ->setDate($now->year, $monthIndex, 1)
                ->locale('es')
                ->monthName;
            $monthKey = Str::lower($monthName);
            $report = $reports->get($monthKey);

            $status = 'future';
            if ($monthIndex < $currentMonthIndex) {
                $status = $report ? 'sent' : 'overdue';
            } elseif ($monthIndex === $currentMonthIndex) {
                $status = $report ? 'sent' : 'pending';
            } else {
                $status = 'future';
            }

            $dateText = null;
            if ($report && $report->date) {
                $dateText = $report->date->locale('es')->translatedFormat('d \d\e F \d\e Y h:i a');
            }

            $this->cards[] = [
                'month' => Str::upper($monthName),
                'monthRaw' => $monthName,
                'status' => $status,
                'report' => $report,
                'dateText' => $dateText,
            ];
        }
    }

    #[Layout('components.layouts.scholar')]
    public function render()
    {
        return view('livewire.scholar.reports.index');
    }
}
