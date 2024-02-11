<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\AccountActivated;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;



class PushNotif extends Notification
{
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData(['data1' => 'value', 'data2' => 'value2'])
            ->setNotification(
                \NotificationChannels\Fcm\Resources\Notification::create()
                    ->setTitle('Share Novel')
                    ->setBody('Ada yang baru nih, cek sekarang!')
            );


    }
}
class notifcontroller extends Controller
{
    public function sendAccountActivatedNotification(Request $request)
    {
        // Temukan user yang akan menerima notifikasi
        $user = User::find($request->user_id); // Ubah sesuai dengan cara Anda menemukan user

        // Periksa apakah user ditemukan
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Kirim notifikasi menggunakan method notify() pada user
        $user->notify(new PushNotif());

        return response()->json(['message' => 'Account activated notification sent']);
    }
}
