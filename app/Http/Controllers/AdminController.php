<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.pages.dashboard.index');
    }

    public function radioAdjustments(){
        return view('admin.pages.radio.index');
    }
}
