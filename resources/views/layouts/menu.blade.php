<li class="nav-item {{ Request::is('doadboard*') ? 'active' : '' }}">
    <a href="{!! route('home') !!}" class="nav-link">
        <i class="fas fa-home"></i>
        <p>
            {{ trans('Home') }}
        </p>
    </a>
</li>

<li class="nav-item {{ Request::is('doadboard*') ? 'active' : '' }}">
    <a href="#" class="nav-link">
        <i class="	far fa-address-card"></i>
        <p>
            {{ trans('Account Manager') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{!! route('users.index') !!}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p> {{ trans('Account information') }}</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item {{ Request::is('doadboard*') ? 'active' : '' }}">
    <a href="{!! route('manager_staff.index') !!}" class="nav-link">
        <i class="fas fa-user-friends"></i>
        <p>
            {{ trans('Manager Staff') }}
        </p>
    </a>
</li>

<li class="nav-item {{ Request::is('doadboard*') ? 'active' : '' }}">
    <a href="{!! route('settings.index') !!}" class="nav-link">
        <i class="fas fa-cog"></i>
        <p>
            {{ trans('Setting') }}
        </p>
    </a>
</li>
