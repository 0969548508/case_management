<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Notification\NotificationRepository as NotificationRepository;
use App\Models\Notification;
use Auth;
use Log;

class NotificationController extends Controller
{
    /**
     * @var notification
    */
    protected $notification;

	public function __construct(NotificationRepository $notification)
	{
        $this->notification = $notification;
    }

	public function showListNotification()
	{
		$notifications = $this->notification->getAllNotificationByUserId(20);

		return view('notification.browse', [
			'notifications' => $notifications
		]);
	}

	public function readUserNotification(Request $request, $id, $url, $userId)
	{
		$this->notification->markAsRead($id);

		return redirect()->route($url, $userId);
	}

	public function loadListNotification(Request $request)
	{
		$notifications = $this->notification->getAllNotificationByUserId(20, $request->offset, $request->start_date);
		$checkNow = false;
		$checkLast = false;

		$view = view('notification.notification', [
			'notifications' => $notifications,
			'checkNow'      => $checkNow,
			'checkLast'     => $checkLast
		])->render();

        return response()->json(['html' => $view]);
	}

	public function markAllAsRead()
	{
		auth()->user()->unreadNotifications->markAsRead();

		return response()->json(['success' => 'Mark all as read successfully']);
	}
}
