<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function profile(){
        return view('subscriber.pages.profile.index');
    }
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
    public function externalRadio(){
        return view('subscriber.pages.e-radio.index');
    }
    public function externalRadioManage(Request $request){
        return view('subscriber.pages.e-radio-manage.index',['radio_id' => $request->radio_id]);
    }
    public function RadioNewPlan(){
        return view('subscriber.pages.new-plan.index');
    }
    public function RadioMyPlan(){
        return view('subscriber.pages.my-plan.index');
    }
    public function RadioNewVerify(){
        return view('subscriber.pages.radio-verify.index');
    }
    public function RadioMyVerify(){
        return view('subscriber.pages.my-verify.index');
    }
    public function RadioNewPromo(){
        return view('subscriber.pages.radio-promotion.index');
    }
    public function RadioMyPromo(){
        return view('subscriber.pages.my-promotion.index');
    }
    public function softButt(){
        return view('subscriber.pages.soft-butt.index');
    }
    public function softZara(){
        return view('subscriber.pages.soft-zara.index');
    }
    public function support(){
        return view('subscriber.pages.support.index');
    }
}
