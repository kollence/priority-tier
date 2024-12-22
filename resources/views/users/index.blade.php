@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<h1 class="mt-4">{{ __('Users Management') }}</h1>
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
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            {{ __('Create User') }}
        </a>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @foreach($user->permissions as $permission)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{route('users.edit', $user->id)}}" class="btn btn-primary">Edit</a>
                        <form action="{{route('users.destroy', $user->id)}}" method="post" class="d-inline"  onsubmit="return confirm('Are you sure you want to remove this permission from the user?');">
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
            {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
<script>
</script>


@endsection