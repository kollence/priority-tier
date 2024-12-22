@extends('layouts.admin')
@section('title', 'Update Permission')
@section('content')
<h1 class="mt-4">Update Permission</h1>
<div class="card mb-4">
    <div class="card-body">
        @if(session('success'))
        <div class="bg-success border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-danger border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <p class="text-danger text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description', $permission->description) }}</textarea>
                @error('description')
                <p class="text-danger text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Permission
                </button>
            </div>
        </form>
    </div>
</div>


@endsection