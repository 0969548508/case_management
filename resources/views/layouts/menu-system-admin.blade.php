<li class="nav-item">
    <a class="nav-link" href="#">
        <img src="{{ asset('images/dashboard_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Dashboard
    </a>
</li>

@can('view users')
    <li class="nav-item">
        <a class="nav-link {{ request()->is('users') || request()->is('users/*') ? 'active' : '' }}" href="{{ route('showListUser') }}" href="{{ route('showListUser') }}">
            <img src="{{ asset('images/users_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Users
        </a>
    </li>
@endcan

@can('view matters')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('getListMatter') }}">
            <img src="{{ asset('images/cases_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Matters
        </a>
    </li>
@endcan

@can('view clients and client locations')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('showListClient') }}">
            <img src="{{ asset('images/clients_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Clients
        </a>
    </li>
@endcan

<li class="nav-item">
    <a class="nav-link" href="#">
        <img src="{{ asset('images/reports_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Reports
    </a>
</li>

@if(auth()->user()->hasAnyPermission('view roles', 'view rates', 'view offices', 'view types', 'allow to view audit log', 'allow setting'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle js-change-caret" href="#">
            <img src="{{ asset('images/settings_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Settings
            <i class="fa fa-caret-right mg-left-39"></i>
        </a>
        <ul class="nav-dropdown-items">
            @can('view roles')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('roles') || request()->is('roles/*') ? 'active' : '' }}" href="{{ route('showListRoles') }}">
                        Roles
                    </a>
                </li>
            @endcan

            @can('view rates')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('showListRate') }}">
                        Rates
                    </a>
                </li>
            @endcan

            @can('allow setting')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('policies.index') }}">
                        Password
                    </a>
                </li>
            @endcan

            @can('allow to view audit log')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('audit-log.index') }}">
                        Audit Logs
                    </a>
                </li>
            @endcan

            @can('view offices')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('getListOffice') }}">
                        Tenant Offices
                    </a>
                </li>
            @endcan

            @can('view types')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('showListType') }}">
                        Type -Subtype
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endif