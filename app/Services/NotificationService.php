<?php

namespace App\Services;

use App\Models\notifications;
// use App\Repository\NotificationsRepo;
use Validator;

class NotificationService
{
    // public NotificationsRepo $notificationRepository;

    public function __construct()
    {
        // $this->notificationRepository=$notificationRepository;
    }

    // public function create($request)
    // {
    //     return $this->notificationRepository->create($notifi);
    // }

    public function display()
    {
        $userId = auth('api')->user()->getAuthIdentifier();
        $notifications = notifications::where('recever', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }
    public function update($request)
    {
        $notification = notifications::find($request->id);
        $notification->read = true;
        $notification->save();
        return $notification;
    }




}