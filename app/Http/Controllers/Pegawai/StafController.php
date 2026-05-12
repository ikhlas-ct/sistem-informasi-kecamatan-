<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StafController extends Controller
{
    public function dashboard()
    {



        $user = Auth::user();
        $pegawai = $user->pegawai;

        return view('pages.pegawai.dashboard',compact('user','pegawai'));
    }
}
