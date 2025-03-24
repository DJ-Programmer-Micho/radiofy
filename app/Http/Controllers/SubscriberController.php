<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function dashboard(){
        return view('subscriber.pages.dashboard.index');
    }
    public function Radio(){
        return view('subscriber.pages.radio.index');
    }
    public function radioManage(Request $request){
        return view('subscriber.pages.radio-manage.index',['radio_id' => $request->radio_id]);
    }
    public function radioServer(Request $request){
        return view('subscriber.pages.radio-server.index',['radio_id' => $request->radio_id]);
    }
    public function RadioNewPlan(){
        return view('subscriber.pages.new-plan.index');
    }
    public function RadioMyPlan(){
        return view('subscriber.pages.my-plan.index');
    }
}
