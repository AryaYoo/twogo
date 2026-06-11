<?php

namespace App\Livewire;

use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileGamification extends Component
{
    public $user;
    public $levelInfo;
    public $tierUp = null;

    public function mount($user = null)
    {
        $this->user = $user ?? Auth::user();
        $this->loadLevelInfo();

        // Check for tier up flash
        if (session()->has('tier_up')) {
            $this->tierUp = session()->get('tier_up');
        }
    }

    public function loadLevelInfo()
    {
        $this->user->refresh();
        $this->levelInfo = GamificationService::getLevelInfo($this->user->xp);
    }

    public function dismissTierUp()
    {
        $this->tierUp = null;
    }

    public function render()
    {
        return view('livewire.profile-gamification');
    }
}
