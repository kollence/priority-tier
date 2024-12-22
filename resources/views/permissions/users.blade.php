
@extends('layouts.admin')

@section('title', 'Users with Permission: ' . $permission->name)
@section('content')
<h1 class="mt-4">{{ __('User with Permissions') }} {{$permission->name}} </h1>
<div class="card mb-4">
    <div class="card-body">
        @if(session('success'))
        <div class="bg-green border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{route('users.destroy', $user->id)}}" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this permission from the user?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No users found with this permission.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="col-md-6 d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>


@endsection