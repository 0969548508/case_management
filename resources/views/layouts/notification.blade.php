<?php
    $notifications = App\Repositories\Notification\NotificationRepository::getAllNotificationByUserId(10);
    $countUnRead = App\Repositories\Notification\NotificationRepository::getAllNotificationUnReadByUserId(true);
    $total = count($notifications);
    $checkNow = true;
    $checkLast = true;
?>
<a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    <img src="{{ asset('images/notification.svg') }}" alt="bell icon" class="notification-bell">
    @if ($countUnRead > 0)
        <span class="badge badge-danger notification-circle"></span>
    @endif
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-notification">
    <div class="dropdown-header text-left notification-header">
        <strong class="mr-2">Notification</strong>
        @if ($countUnRead > 0)
            <span class="badge badge-pill badge-danger notification-header-number">{{ ($countUnRead > 99) ? '99+' : $countUnRead }}</span>
        @endif
        <a href="javascript:void(0);" class="notification-header-mark-as-read">Mark all as read</a>
        <!-- <a href="javascript:void(0);" class="notification-header-settings"></a> -->
    </div>
    <div class="scrollbar notification-scroll">
        @if ($total == 0)
            <a class="dropdown-item notification-new cursor-unset text-center pl-0" href="javascript:void(0);">There are no announcements</a>
        @else
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
                        <a class="dropdown-item notification-new cursor-unset" href="javascript:void(0);">
                            New
                        </a>
                    @endif
                    @include('layouts.notification-item')
                @else
                    @if ($checkLast == true)
                        <?php $checkLast = false; ?>
                        <a class="dropdown-item notification-new cursor-unset" href="javascript:void(0);">
                            Earlier
                        </a>
                    @endif
                    @include('layouts.notification-item')
                @endif
            @endforeach
        @endif
    </div>
    <div class="dropdown-header notification-view-all text-center">
        <a href="{{ route('showListNotification') }}"><strong>View all</strong></a>
    </div>
</div>