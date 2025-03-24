<?php

namespace App\Http\Livewire\Subscriber\Plan;

use Carbon\Carbon;
use App\Models\Plan;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\RadioSubscription;
use App\Models\RadioConfiguration;
use Illuminate\Support\Facades\DB;
use App\Models\RadioConfigurationProfile;

class NewPlanLivewire extends Component
{
    public $radioName;
    public $serverSource;
    public $serverPassword;
    public $planId;
    public $planSelected;

    public $durationSelect = 'monthly';

    public function mount(){
        
    }

    protected $rules = [
        'radioName'      => 'required|string|min:3|unique:radio_configurations,radio_name',
        'serverSource'   => 'required|string|min:3',
        'serverPassword' => 'required|string|min:3',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules);
    }

    public function selectPlan($id){
        $this->planSelected = Plan::where('id', $id)->where('status', 1)->first();
    }

    public function addRadio()
    {
        // Validate all inputs
        $this->validate();

        $radio = RadioConfiguration::create([
            'subscriber_id'    => auth()->guard('subscriber')->id(),
            'plan_id'          => $this->planSelected->id,
            'genre_id'         => null,
            'radio_name'       => $this->radioName,
            'radio_name_slug'  => Str::slug($this->radioName),
            'source'           => $this->serverSource."@mradiofy",
            'source_password'  => $this->serverPassword,
            'fallback_mount'   => '/fallback',
            'status'           => 1,
        ]);

        RadioConfigurationProfile::create([
            'radio_id'      => $radio->id,
        ]);

        $expireDate = $this->durationSelect === 'monthly'
            ? Carbon::now()->addMonth()
            : Carbon::now()->addYear();

        RadioSubscription::insert([
            'subscriber_id'          => auth()->guard('subscriber')->id(),
            'radio_configuration_id' => $radio->id, 
            'plan_id'                => $this->planSelected->id,               
            'payment_frequency'      => $this->durationSelect,
            'subscribed_date'        => Carbon::now(),
            'renew_date'             => $expireDate,
            'expire_date'            => $expireDate,
        ]);

        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('Radio added successfully.')
        ]);

        // Dispatch an event to close the modal on the front-end
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('subs-radios');
    }

    public function closeModal()
    {
        $this->reset(['radioName', 'serverSource', 'serverPassword', 'planSelected']);
        $this->dispatchBrowserEvent('close-modal');
    }
    public function changeDuration($duration) {
        $this->durationSelect = $duration;
    }
    public function render()
    {
        $plans_64 = Plan::where('bitrate', '64')->where('status', 1)->get();
        $plans_96 = Plan::where('bitrate', '96')->where('status', 1)->get();
        $plans_128 = Plan::where('bitrate', '128')->where('status', 1)->get();
        $plans_256 = Plan::where('bitrate', '256')->where('status', 1)->get();
        $plans_320 = Plan::where('bitrate', '320')->where('status', 1)->get();

        $subscriberPlanIds = RadioConfiguration::where('subscriber_id', auth()->guard('subscriber')->id())
        ->pluck('plan_id')
        ->toArray();
        
        return view('subscriber.pages.new-plan.planGrid', [
            'plans_64' => $plans_64,
            'plans_96' => $plans_96,
            'plans_128' => $plans_128,
            'plans_256' => $plans_256,
            'plans_320' => $plans_320,
            'subscriberPlanIds' => $subscriberPlanIds,
        ]);
    }
    
}
