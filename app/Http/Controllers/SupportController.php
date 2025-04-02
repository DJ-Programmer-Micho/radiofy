<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TelegramSupport;
use Illuminate\Support\Facades\Validator;

use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Notification;

class SupportController extends Controller
{
    public $location;
    public $guestIdentifier;
    public $deviceIdentifier;

    public function contactUsApp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
            'phone' => 'nullable|regex:/^[\+0-9\-\s]{7,20}$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        if(auth()->guard('subscriber')->check()) {
            $id = auth()->guard('subscriber')->user()->id;
            $user_type = "Subscriber";
            $tele_id = (string) env('TELEGRAM_GROUP_ID_SUPPORT_SUBSCRIBER');
            $number = $request->input('phone') ?? auth()->guard('subscriber')->user()->subscriber_profile->phone_number;
            
        }elseif (auth()->guard('listener')->check()) {
            $id = auth()->guard('listener')->user()->id;
            $user_type = "Listener";
            $tele_id = (string) env('TELEGRAM_GROUP_ID_SUPPORT_LISTENER');
            $number = $request->input('phone') ?? auth()->guard('listener')->user()->listener_profile->phone_number;
            
        } else {
            $id = 'Visiter ';
            $user_type = "Guest";
            $tele_id = (string) env('TELEGRAM_GROUP_ID_SUPPORT_GUEST');
            $number = $request->input('phone') ?? null;
        }

        try {

            $this->guestIdentifier = $_SERVER['REMOTE_ADDR'];
            try {
                $this->location = Location::get($this->guestIdentifier);
            } catch (\Exception $e) {

            }
            $this->deviceIdentifier = $_SERVER['HTTP_USER_AGENT'];
        }
        catch (\Exception $e) {
            
        }
        try {
            Notification::route('toTelegram', null)
                ->notify(new TelegramSupport(
                    $id,
                    $user_type,
                    $request->input('name'),
                    $request->input('email'),
                    $request->input('subject'),
                    $request->input('message'),
                    $number,
                    $this->location,
                    $this->guestIdentifier,
                    $this->deviceIdentifier,
                    $tele_id
                ));
    
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'message' => __('Message sent successfully'),
            ]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => __('An error occurred while sending the notification.'),
            ]);
        }
    }
}
