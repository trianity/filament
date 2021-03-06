<?php

namespace Filament\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Filament\Traits\HasForm;
use Filament\Contracts\User as UserContract;

class UserCreate extends Component
{    
    use AuthorizesRequests, HasForm;

    public function mount()
    {        
        $this->authorize('create', app(UserContract::class));
        $this->initForm();
    }

    public function success()
    {
        $user = app(UserContract::class)::create($this->model_data);    
        $user->syncMeta($this->model_meta);

        session()->flash('notification', [
            'type' => 'success',
            'message' => __('filament::notifications.created', ['item' => $user->name]),
        ]);

        return redirect()->route('filament.admin.users.index');
    }

    public function render()
    {        
        $groupedFields = $this->fields()->groupBy(function ($field, $key) {
            return $field->group;
        });
        
        return view('filament::livewire.tabbed-form', [
            'title' => __('filament::users.create'),
            'groupedFields' => $groupedFields,
        ]);
    }
}
