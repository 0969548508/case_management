<?php

namespace App\Repositories\Notification;

use App\Repositories\Repository;
use App\Models\Notification;
use Carbon\Carbon;
use Auth;
use Log;

class NotificationRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Notification::class;
    }

    public static function getAllNotificationByUserId($take = null, $offer = 0, $startDate = null)
    {
        $notifications = Notification::where('notifiable_id', auth()->user()->id);
        if (!empty($startDate)) {
            $notifications = $notifications->whereDate('created_at', '<', $startDate);
        }

        $notifications = $notifications->orderBy('created_at', 'DESC')
                                        ->offset($offer);
        if (!empty($take)) {
            $notifications = $notifications->take($take);
        }

        // $sql = str_replace_array('?', $notifications->getBindings(), $notifications->toSql());
        // Log::debug(' ----------- sql : '. print_r($sql, true));
        $notifications = $notifications->get();

        return $notifications;
    }

    public static function getAllNotificationUnReadByUserId($count = false)
    {
        $notifications = Notification::where(['notifiable_id' => auth()->user()->id, 'read_at' => null])->orderBy('created_at', 'DESC');
        if ($count == false) {
            $notifications = $notifications->get();
        } else {
            $notifications = $notifications->count();
        }

        return $notifications;
    }

    public static function timeElapsedString($datetime, $full = false) {
        $now = Carbon::now();
        $ago = $datetime;
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);

        if (empty($string) || ( $string && (strpos(implode(', ', $string), 'second') || strpos(implode(', ', $string), 'seconds'))))
            return 'now';

        return implode(', ', $string) . ' ago';
    }

    public function markAsRead($notifyId = null)
    {
        if (!empty($notifyId)) {
            auth()->user()->notifications()->find($notifyId)->markAsRead();
        }
    }
}