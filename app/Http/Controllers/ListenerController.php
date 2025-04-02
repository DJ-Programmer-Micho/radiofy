<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListenerController extends Controller
{
    // AUTH
    public function profile(){
        return view('listener.pages.profile.index');
    }

    public function library(){
        return view('listener.pages.library.index');
    }
    // NON - AUTH
    public function home(){
        return view('listener.pages.home.index');
    }

    public function genre(){
        return view('listener.pages.genre.index');
    }
    
    public function genreView(Request $request){
        return view('listener.pages.genreview.index',['genre_id' => $request->genreId]);
    }
    
    public function language(){
        return view('listener.pages.language.index');
    }

    public function LanguageView(Request $request){
        return view('listener.pages.languageview.index',['code' => $request->code]);
    }
    
    public function radioView(Request $request){
        return view('listener.pages.radio.radioIndex',['radio_id' => $request->slug]);
    }
}
