<?php

namespace App\Livewire;

use App\Models\Organization;
use Livewire\Component;

class ThemeSelector extends Component
{
    public $theme;

    public function mount()
    {
        $this->theme = auth()->user()->organzation->theme ?? 'red';
    }

    public function updateTheme($color)
    {
        $org = Organization::where('id',auth()->user()->organization_id)->first();
        $org->theme = $color;
        $org->save();
        $this->theme = $color;
        session(['theme' => $color]);
        return redirect()->route('organization.settings.general_settings'); 
    }

    public function render()
    {
        return view('livewire.theme-selector');
    }
}
