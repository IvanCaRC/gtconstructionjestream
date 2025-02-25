<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        // Acceder a las notificaciones no leídas del usuario autenticado
        $notification = auth()->user()->unreadNotifications->find($id);

        if ($notification) {
            $notification->markAsRead();
            // $notification->delete(); // Eliminar la notificación después de marcarla como leída
        }

        return redirect($request->input('redirect_to'));
    }

    public function getNotifications()
    {
        // Obtener hasta 5 notificaciones no leídas del usuario autenticado
        return auth()->user()->unreadNotifications->take(5);
    }
}
