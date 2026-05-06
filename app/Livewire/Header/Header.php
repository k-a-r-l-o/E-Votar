<?php

namespace App\Livewire\Header;

use AllowDynamicProperties;
use App\Models\Election;
use Livewire\Component;

#[AllowDynamicProperties] class Header extends Component
{
    public $elections;
    public $selectedElection;

    public function mount(): void
    {
        $this->elections = Election::with('election_type')->orderBy('created_at', 'desc')->get();
        $this->selectedElection = session('selectedElection');

        // Default to the latest election if none selected
        if (!$this->selectedElection && $this->elections->count() > 0) {
            $this->selectedElection = $this->elections->first()->id;
            session(['selectedElection' => $this->selectedElection]);
        }
    }

    public function updatedSelectedElection($value): void
    {
        session(['selectedElection' => $value]);
        $this->dispatch('global-election-updated', electionId: $value);
    }

    public function render()
    {
        return view('evotar.livewire.header.header');
    }
}
