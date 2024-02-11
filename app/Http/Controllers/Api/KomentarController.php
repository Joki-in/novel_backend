<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Models\User;
use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class PushNotifKomentar extends Notification
{
    protected $komentar;
    protected $namaPengguna;

    public function __construct($komentar, $namaPengguna)
    {
        $this->komentar = $komentar;
        $this->namaPengguna = $namaPengguna;
    }


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
                    ->setBody("{$this->namaPengguna} berkomentar: {$this->komentar->komentar}")
            );
    }
}


class KomentarController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Buat data Komentar baru
            $komentar = Komentar::create([
                'id_buku' => $request->id_buku,
                'id_user' => $request->id_user,
                'komentar' => $request->komentar,
            ]);

            $buku = Buku::where('id', $request->id_buku)->first();

            // Temukan user yang akan menerima notifikasi
            $user = User::find($buku->penulis_id);

            // Periksa apakah user ditemukan
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $namaPenggunas = User::where('id', $request->id_user)->first();
            $namaPengguna = $namaPenggunas->name;

            // Kirim notifikasi menggunakan method notify() pada user
            $user->notify(new PushNotifKomentar($komentar, $namaPengguna));

            return response()->json([
                'message' => 'Komentar created successfully',
                'komentar' => $komentar,
                'buku' => $buku,
                "nama" => $namaPengguna
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json(['message' => 'Failed to process the request', 'error' => $e->getMessage()], 500); // 500 Internal Server Error// 500 Internal Server Error
        }
    }
}