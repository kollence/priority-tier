@php
    $importTypes = config('import_types');
@endphp
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
        @can('restrict-users-route')
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
                @foreach($importTypes as $key => $type)
                    @can($type['permission_required'])
                        <!-- <option value="{{ $key }}">{{ $type['label'] }}</option> -->
                        @foreach($type['files'] as $file)
                            <a class="nav-link" href="#">{{$type['label']}} {{$file['label']}}</a>
                        @endforeach
                    @endcan
                @endforeach
                </nav>
            </div>
            <a class="nav-link" href="tables.html">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Imports
            </a>
            
        </div>
    </div>
</nav>