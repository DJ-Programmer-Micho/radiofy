<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListenerController extends Controller
{
    public function home(){
        return view('listener.pages.home.index');
    }
    
    public function radioView(Request $request){
        return view('listener.pages.radio.radioIndex',['radio_id' => $request->radio]);
    }
}
