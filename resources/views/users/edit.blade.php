@extends('alpine::layouts.admin')

@section('title', $title)

@section('main')
    <div class="max-w-xl bg-white py-8 px-4 shadow rounded-md sm:px-10">
        @livewire('alpine::user-edit-form', ['user' => $user])
    </div>
@endsection