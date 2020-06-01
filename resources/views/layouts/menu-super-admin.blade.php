@can('view users')
    <li class="nav-item">
        <a class="nav-link {{ request()->is('users') || request()->is('users/*') ? 'active' : '' }}" href="{{ route('showListUser') }}" href="{{ route('showListUser') }}">
            <img src="{{ asset('images/users_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Users
        </a>
    </li>
@endcan

<li class="nav-item">
    <a class="nav-link" href="{{ route('showCreateTenant') }}">
        <img src="{{ asset('images/cases_icon.png') }}" class="fas fa-lg fa-tachometer-alt nav-icon">Tenant
    </a>
</li>

@if(auth()->user()->hasAnyPermission('view roles', 'allow to view audit log', 'allow setting'))
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

            @can('allow setting')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('policies.index') }}">
                        Password
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link" href="{{ route('audit-log.index') }}">
                    Audit Logs
                </a>
            </li>
        </ul>
    </li>
@endif