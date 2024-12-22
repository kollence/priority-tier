@extends('layouts.admin')
@section('title', 'Permissions Management')
@section('content')
<h1 class="mt-4">{{ __('Permissions Management') }}</h1>
<div class="card mb-4">
    <div class="card-body">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
            {{ __('Create Permission') }}
        </a>
        <a href="{{ route('permissions.assignForm') }}" class="btn btn-primary">
            {{ __('Assign Permissions') }}
        </a>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Users Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->description }}</td>
                    <td>
                        <a href="{{ route('permissions.showUsers', $permission) }}"
                            class="">
                            {{ $permission->users_count }}
                        </a>
                    </td>
                    <td>
                        <a href="{{route('permissions.edit', $permission->id)}}" class="btn btn-primary">Edit</a>
                        <form action="{{route('permissions.destroy', $permission->id)}}" method="post" class="d-inline"  onsubmit="return confirm('Are you sure you want to remove this permission from the user?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="col-md-6 d-flex justify-content-end">
            {{ $permissions->links() }}
            </div>
        </div>
    </div>
</div>


@endsection