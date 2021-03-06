@section('title', $title)

@section('actions')
    @can('create permissions')
        <button 
            type="button" 
            @click.prevent="$dispatch('filament-toggle-modal', { id: 'permission-create' })" 
            class="btn btn-small btn-add"
        >
            <x-heroicon-o-plus class="h-3 w-3 mr-2" />
            {{ __('filament::permissions.create') }}
        </button>
        @push('footer')
            <x-filament-modal 
                id="permission-create" 
                :title="__('filament::permissions.create')" 
                :esc-close="true" 
                :click-outside="true"
                class="sm:max-w-xl"
            >
                @livewire('filament::permission-create')
            </x-filament-modal>
        @endpush
    @endcan
@endsection

<div>

    <div class="mb-4 flex justify-between">
        <div class="flex-grow mr-6">
            <input 
                type="search"
                wire:model="search" 
                class="form-input input w-full" 
                placeholder="{{ __('Search') }}"
            >
        </div>
        <label class="flex-shrink-0 flex items-center">
            <span class="label mr-2">Per Page:</span>
            <select wire:model="perPage" class="form-select input">
                @foreach($this->pagingOptions as $option)
                    <option>{{ $option }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <table class="table-simple">
        <thead>                    
            <tr>
                <th>
                    <button class="flex" wire:click.prevent="sortBy('id')">
                        @include('filament::partials.sort-header', [
                            'field' => 'id',
                            'label' => __('ID'),
                        ])
                    </button>
                </th>
                <th>
                    <button class="flex" wire:click.prevent="sortBy('name')">
                        @include('filament::partials.sort-header', [
                            'field' => 'name',
                            'label' => __('Name'),
                        ])
                    </button>
                </th>
                <th>
                    <button class="flex" wire:click.prevent="sortBy('description')">
                        @include('filament::partials.sort-header', [
                            'field' => 'description',
                            'label' => __('Description'),
                        ])
                    </button>
                </th>
                <th>
                    <button class="flex" wire:click.prevent="sortBy('is_system')">
                        @include('filament::partials.sort-header', [
                            'field' => 'is_system',
                            'label' => __('Type'),
                        ])
                    </button>
                </th>
                <th>
                    <button class="flex" wire:click.prevent="sortBy('created_at')">
                        @include('filament::partials.sort-header', [
                            'field' => 'created_at',
                            'label' => __('filament::labels.created_at'),
                        ])
                    </button>
                </th>
                <th colspan="2">
                    <button class="flex" wire:click.prevent="sortBy('updated_at')">
                        @include('filament::partials.sort-header', [
                            'field' => 'updated_at',
                            'label' => __('filament::labels.updated_at'),
                        ])
                    </button>    
                </th>
            </tr> 
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td class="font-medium">{{ $permission->name }}</td>
                    <td>{{ $permission->description }}</td>
                    <td>       
                        <x-filament-pill>
                            @if ($permission->is_system)
                                {{ __('system') }}
                            @else
                                {{ __('custom') }}
                            @endif
                        </x-filament-pill>
                    </td>
                    <td>{{ $permission->created_at->fromNow() }}</td>
                    <td @istrue ($permission->is_system, ' colspan="2"')>{{ $permission->updated_at->fromNow() }}</td>
                    @isfalse ($permission->is_system)
                        <td class="text-right">
                            <x-filament-dropdown dropdown-class="origin-top-right right-0 w-48">
                                <x-slot name="button">
                                    <x-heroicon-o-dots-horizontal class="h-5 w-5" />
                                </x-slot>
                                @can('edit permissions')
                                    <button 
                                        type="button" 
                                        @click.prevent="open = false; $dispatch('filament-toggle-modal', { id: 'permission-edit-{{ $permission->id }}' })"
                                    >
                                        {{ __('Edit') }}
                                    </button>
                                @endcan
                                @can('delete permissions')
                                    <button 
                                        type="button" 
                                        @click.prevent="open = false; $dispatch('filament-toggle-modal', { id: 'permission-delete-{{ $permission->id }}' })"
                                        class="text-red-500"
                                    >
                                        {{ __('Delete') }}
                                    </button>
                                @endcan
                            </x-filament-dropdown>
                        </td>
                    @endisfalse
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="7">{{ __('No permissions found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="flex justify-between mt-6">
        {{ $permissions->links('filament::partials.pagination') }}
        @if (count($permissions))
            <p class="text-xs font-mono leading-5 text-gray-500 dark:text-gray-400">
                {{ __('filament::pagination.results', [
                    'firstItem' => $permissions->firstItem(),
                    'lastItem' => $permissions->lastItem(),
                    'total' => $permissions->total(),
                ]) }}
            </p>
        @endif
    </div>

</div>

@foreach ($customPermissions as $permission)
    @push('footer')
        <x-filament-modal 
            :id="'permission-edit-'.$permission->id" 
            :title="__('filament::permissions.edit')" 
            :esc-close="true" 
            :click-outside="true" 
            class="sm:max-w-xl"
        >
            @livewire('filament::permission-edit', ['permission' => $permission])
        </x-filament-modal> 
        <x-filament-modal 
            :id="'permission-delete-'.$permission->id" 
            :title="__('filament::permissions.delete')" 
            :esc-close="true" 
            :click-outside="true" 
            class="sm:max-w-md"
        >
            @livewire('filament::permission-delete', ['permission' => $permission])
        </x-filament-modal>
    @endpush
@endforeach
