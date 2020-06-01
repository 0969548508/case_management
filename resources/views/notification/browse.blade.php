@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="title mb-0">Notification</h1>
    <a href="javascript:void(0);" class="page-notification-header-mark-as-read">Mark all as read</a>
    <!-- <a href="javascript:void(0);" class="page-notification-header-settings"></a> -->
</div>
<?php
    $total = count($notifications);
    $checkNow = true;
    $checkLast = true;
?>

@if ($total == 0)
    <a class="dropdown-item page-notification-new cursor-unset text-center pl-0" href="javascript:void(0);">There are no announcements</a>
@else
    <div id="page-notifications-list" class="notification-scroll">
        <div id="page-notifications-biding">
            @include('notification.notification')
        </div>
    </div>
@endif
@endsection

@section('javascript')
    <script>
        @if ($total > 0)
            var notifyOffset = 0;
            var maxOffset = 500;

            $('body').addClass('overflow-y-hidden');

            $('#page-notifications-list').unbind('scroll'); // To prevent scroll event applied twice
            $('#page-notifications-list').scroll(function() {
                if($('#page-notifications-list').scrollTop() + $('#page-notifications-list').height() >= $('#page-notifications-biding').height()) {

                    // To load more notifications
                    let countNotifications = document.getElementsByClassName('page-notification-item').length;
                    notifyOffset = countNotifications;
                    if (notifyOffset < maxOffset) {
                        loadNotifications();
                    }
                }
            });

            function loadNotifications() {
                var data = {
                    'offset'     : notifyOffset,
                    'start_date' : "{{ $notifications[$total-1]->created_at }}"
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('loadListNotification') }}",
                    method: 'get',
                    data: data,
                    success: function(response) {
                        $("#page-notifications-biding").append(response.html);
                    }
                });
            }

            $('.page-notification-header-mark-as-read').click(function(){
                $("span.notification-header-number").hide();
                $("span.notification-circle").hide();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('markAllAsRead') }}",
                    method: 'get',
                    data: '',
                    success: function(response) {
                        $("div#page-notifications-list").find(".page-notification-item").removeClass("page-notification-unread");
                        $("div.dropdown-menu-notification .notification-scroll").find(".notification-item").removeClass("notification-unread");
                    }
                });
            });
        @endif
    </script>
@endsection
