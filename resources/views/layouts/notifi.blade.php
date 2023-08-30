<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <button type="button" class="btn btn-primary btn-sm position-relative rounded-pill">
            <i class="fas fa-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadNotifications }}
            </span>
        </button>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 400px; overflow-y: auto;">
        <span class="dropdown-item dropdown-header">{{ $unreadNotifications }}
            {{ trans('Notifications') }}</span>
        <div class="dropdown-divider"></div>
        @foreach ($notifications as $notification)
            <a href="{!! route('manager_remote.index') !!}" class="dropdown-item">
                <i class="fab fa-twitch"></i> {{ $notification->getName() }}
                {{ trans('Registered Remote') }}
                <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
    </div>
</li>
