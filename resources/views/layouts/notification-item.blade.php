@switch($slug)
    @case('user-assign-role')
    @case('user-create')
    @case('user-update')
        <a class="dropdown-item notification-item @if (empty($notification->read_at)) notification-unread @endif" href="@if (empty($notifyInfo->url)) javascript:void(0); @else {{ route('readUserNotification', [
            'id'     => $notification->id,
            'url'    => $notifyInfo->url,
            'userId' => $info->id
        ]) }} @endif">
            @if (empty($by->image))
                <div class="member member-notification float-left">
                    <span class="avata-client member-initials member-initials-notification">{{ substr($by->name, 0, 1) . '' . substr($by->family_name, 0, 1) }}</span>
                </div>
            @else
                <img src="{{ $by->image }}" class="avata-notification float-left">
            @endif
            <div class="notification-title">
                {{ ucwords($by->name) . ' ' . ucwords($by->family_name) }}
                <span class="float-right">{{ ucfirst($time) }}</span>
            </div>
            <div class="notification-content">
                {!! $notifyInfo->content !!}
            </div>
        </a>
        @break

    @default
        @break
@endswitch