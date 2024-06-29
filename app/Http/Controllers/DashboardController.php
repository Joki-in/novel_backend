<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Isi;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $bookCount = Buku::count();
        $book18 = Buku::where('18+', 1)->count();
        return view('page.admin.dashboard', compact('userCount', 'bookCount', 'book18'));
    }
    public function indexUser()
    {
        $bookCount = Buku::where('penulis_id', Auth::id())->count();
        $isiCount = Isi::whereHas('buku', function ($query) {
            $query->where('penulis_id', Auth::id());
        })->count();

        return view('page.user.dashboard', compact('bookCount', 'isiCount'));
    }
}
