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
    public function radioGenres(){
        return view('admin.pages.genre.index');
    }
    public function radioLanguages(){
        return view('admin.pages.language.index');
    }
    public function radioPlan(){
        return view('admin.pages.plan.index');
    }
    public function radioVerify(){
        return view('admin.pages.verify.index');
    }
    public function radioPromotion(){
        return view('admin.pages.promotion.index');
    }
}
