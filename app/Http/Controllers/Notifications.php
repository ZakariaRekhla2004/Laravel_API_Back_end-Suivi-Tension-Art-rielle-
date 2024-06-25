<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class Notifications extends Controller
{
    public NotificationService $notif;
    public function __construct(NotificationService $notif)
    {
        $this->middleware('jwt');
        $this->notif = $notif;
    }

    public function getNotifications()
    {
        $notifactions = $this->notif->display();
        return response()->json([
            'notification' => $notifactions
        ], 200);
    }
    public function updateNotification(Request $request)
    {
        $notifactions = $this->notif->update($request);
        
        return response()->json($notifactions,200);
    }



}
