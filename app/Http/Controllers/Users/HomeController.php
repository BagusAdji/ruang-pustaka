<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('users.home.index', [
            'total_buku' => Buku::count(),
            'buku' =>Buku::where('jumlah', '>', 0)->paginate(4),
        ]);
    }
}
