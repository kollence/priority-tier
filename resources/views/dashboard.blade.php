@extends('layouts.admin')
@section('title', 'User')
@section('content')
<h1 class="mt-4">{{ __('Dashboard (profile/index)') }}</h1>
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
    <div class="card-body">
        <table id="datatables">
            <thead>
                <tr>
                    <th>Name</th><td>{{$user->name}}</td>
                </tr>
                <tr>
                    <th>Email</th><td>{{$user->email}}</td>
                <tr>
                    <th>Permissions</th><td>
                        @foreach($user->permissions as $permission)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    
                    
                    
                </tr>
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="col-md-6 d-flex justify-content-end">
            </div>
        </div>
    </div>
</div>
<script>
</script>


@endsection