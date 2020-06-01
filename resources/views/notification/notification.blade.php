@foreach($notifications as $notification)
    <?php
        $time = App\Repositories\Notification\NotificationRepository::timeElapsedString($notification->created_at);
        $notifyInfo = json_decode($notification->data);
        $slug = $notifyInfo->data->slug;
        $by = $notifyInfo->data->by;
        $info = $notifyInfo->data->info;
    ?>
    @if ($time == 'now')
        @if ($checkNow == true)
            <?php $checkNow = false; ?>
            <a class="dropdown-item page-notification-new cursor-unset pl-0" href="javascript:void(0);">
                New
            </a>
        @endif
        @include('notification.notification-item')
    @else
        @if ($checkLast == true)
            <?php $checkLast = false; ?>
            <a class="dropdown-item page-notification-new cursor-unset pl-0" href="javascript:void(0);">
                Earlier
            </a>
        @endif
        @include('notification.notification-item')
    @endif
@endforeach