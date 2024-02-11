<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;

class CreateBukuController extends Controller
{
    public function storeBuku(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string',
                'sinopsis' => 'required|string',
                'penulis_id' => 'required|exists:users,id',
                '18+' => 'required|integer',
                'genre' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $data = $request->only(['judul', 'sinopsis', 'penulis_id', 'genre', '18+']);

        try {
            $buku = Buku::create($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Data buku berhasil disimpan'], 201);
    }

    public function updateCover(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|exists:buku,id',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $idBuku = $request->input('id_buku');
        $buku = Buku::findOrFail($idBuku);


        if ($buku->cover) {

            $path = public_path('coverbuku/' . $buku->cover);
            if (file_exists($path)) {
                unlink($path);
            }
        }


        $file = $request->file('cover');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('coverbuku'), $fileName);


        $buku->cover = $fileName;
        $buku->save();

        return response()->json(['message' => 'Cover buku berhasil diperbarui'], 200);
    }
    public function createIsi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|exists:buku,id',
            'chapter' => 'required',
            'isi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        $isi = new Isi();
        $isi->id_buku = $request->input('id_buku');
        $isi->chapter = $request->input('chapter');
        $isi->isi = $request->input('isi');
        $isi->save();


        $likes = Like::where('buku_id', $request->id_buku)->get();
        $likeIds = $likes->pluck('id')->toArray();
        $userIds = [];
        $buku = Buku::where('id', $request->id_buku)->first();
        $judul = $buku->judul;

        $likedUsers = Like::whereIn('id', $likeIds)->with('user')->get();


        foreach ($likedUsers as $likedUser) {
            $user = $likedUser->user;
            $user->notify(new PushNotifIsi($judul));
            $userIds[] = $user->id;
        }

        return response()->json(['message' => 'Data isi buku berhasil dibuat', 'notification_sent' => true, 'user_ids_notified' => $userIds, "buku" => $buku->judul], 201);
    }
}
class PushNotifIsi extends Notification
{
    protected $judul;
    public function __construct($judul)
    {
        $this->judul = $judul;
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
                    ->setBody("Ada chapter baru di buku {$this->judul}, cek sekarang!")
            );


    }
}
