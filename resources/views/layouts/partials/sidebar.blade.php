@php
    $importTypes = config('import_types');
@endphp
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
        @can('manage-users')
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#usersManagement" aria-expanded="false" aria-controls="usersManagement">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                User Management
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="usersManagement" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{route('users.index')}}">Users</a>
                    <a class="nav-link" href="{{route('permissions.index')}}">Permissions</a>
                </nav>
            </div>
        @endcan
            <a class="nav-link" href="{{route('data-import.index')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Data Import
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#importedData" aria-expanded="false" aria-controls="importedData">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Imported Data
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="importedData" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                <!-- ■ If a user doesn't have permissions to import any import types, this tab should not be visible to that user. -->
                @foreach($importTypes as $key1 => $type)
                    @can($type['permission_required'])
                        @foreach($type['files'] as $key2 => $file)
                            <a class="nav-link {{ request()->is("data-import/{$key1}/{$key2}") ? 'active' : '' }}" href="{{ route('data-import.show', [$key1,$key2]) }}">{{$type['label']}} {{$file['label']}}</a>
                        @endforeach
                    @endcan
                @endforeach
                </nav>
            </div>
            <a class="nav-link" href="{{  route('imports.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Imports
            </a>
            
        </div>
    </div>
</nav>