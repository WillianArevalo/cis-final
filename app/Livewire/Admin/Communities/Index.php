<?php

namespace App\Livewire\Admin\Communities;

use App\Models\Community;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showEditor = false;
    public bool $showDeleteConfirm = false;

    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetEditor();
        $this->showEditor = true;
    }

    public function edit(int $communityId): void
    {
        $community = Community::findOrFail($communityId);

        $this->editingId = $community->id;
        $this->name = $community->name;

        $this->resetValidation();
        $this->showEditor = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            Community::findOrFail($this->editingId)->update($validated);
        } else {
            Community::create($validated);
        }

        $this->showEditor = false;
        $this->resetEditor();
    }

    public function confirmDelete(int $communityId): void
    {
        $this->resetValidation();
        $this->deletingId = $communityId;
        $this->showDeleteConfirm = true;
    }

    public function delete(): void
    {
        if (!$this->deletingId) {
            return;
        }

        Community::findOrFail($this->deletingId)->delete();

        $this->showDeleteConfirm = false;
        $this->deletingId = null;
    }

    private function resetEditor(): void
    {
        $this->reset([
            'editingId',
            'name',
        ]);

        $this->resetValidation();
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $communities = Community::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.communities.index', [
            'communities' => $communities,
        ]);
    }
}
