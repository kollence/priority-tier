@extends('layouts.admin')
@section('title', 'Assign Permissions')
@section('content')
<h1 class="mt-4">{{ __('Assign Permissions') }}</h1>
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
        <form method="POST" action="{{ route('permissions.assign') }}">
            @csrf

            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Select User</label>
                <select name="user_id" id="user_id"
                    class="block shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Select a user</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Permissions</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($permissions as $permission)
                    <div class="flex items-start space-x-3">
                        <input type="checkbox"
                            name="permissions[]"
                            id="permission_{{ $permission->id }}"
                            value="{{ $permission->id }}"
                            class="mt-1"
                            {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                        <label for="permission_{{ $permission->id }}" class="text-sm">
                            <span class="font-medium block">{{ $permission->name }}</span>
                            <span class="text-gray-500 text-xs">{{ $permission->description }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('permissions')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                @error('permissions.*')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('permissions.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Assign Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Select the user and fetch their permissions
        $('#user_id').on('change', function() {
            const userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: '{{ route("permissions.userPermissions") }}', // Adjust the route as necessary
                    type: 'POST',
                    data: { 
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // console.log(response);
                        $('input[name="permissions[]"]').prop('checked', false); // Uncheck all checkboxes first
                        response.forEach(function(permissionId) {
                            $('#permission_' + permissionId.permission_id).prop('checked', true); // Check the user's permissions
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching permissions:', xhr);
                    }
                });
            } else {
                $('input[name="permissions[]"]').prop('checked', false); // Uncheck all checkboxes if no user is selected
            }
        });
    });
</script>


@endsection