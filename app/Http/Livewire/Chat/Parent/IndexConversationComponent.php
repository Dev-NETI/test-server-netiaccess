<?php

namespace App\Http\Livewire\Chat\Parent;

use Livewire\Component;

class IndexConversationComponent extends Component
{
    public function render()
    {
        return view('livewire.chat.parent.index-conversation-component')->layout('layouts.trainee.tbase');
    }
}
