<?php

namespace App\Http\Livewire\Subscriber\Verify;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\RadioVerification;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use App\Models\Verification;

class NewVerifyLivewire extends Component
{
    public $radioSlug;
    public $verificationId;
    public $verificationSelected;
    public $durationSelect = 'monthly';
    public $selectedRadioId;
    public $availableRadios;

    protected $rules = [
        'radioSlug' => 'required|string',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function selectVerification($id)
    {
        $this->verificationSelected = Verification::where('id', $id)
            ->where('status', 1)
            ->first();
    }

    public function verifyRadio()
    {
        $this->validate();
    
        $radio = $this->availableRadios[$this->radioSlug] ?? null;
    
        if (!$radio) {
            $this->addError('radioSlug', __('Radio not found.'));
            return;
        }
    
        $radioModel = $radio['type'] === 'internal'
            ? RadioConfiguration::class
            : ExternalRadioConfiguration::class;
    
        $expireDate = $this->durationSelect === 'monthly'
            ? Carbon::now()->addMonth()
            : Carbon::now()->addYear();
    
        // Create the verification record
        RadioVerification::create([
            'subscriber_id'     => auth()->guard('subscriber')->id(),
            'radioable_id'      => $radio['id'],
            'radioable_type'    => $radioModel,
            'verification_id'   => $this->verificationSelected->id,
            'ad_dicount'   => $this->discount_ad,
            'payment_frequency' => $this->durationSelect,
            'verified_date'     => Carbon::now(),
            'renew_date'        => $expireDate,
            'expire_date'       => $expireDate,
        ]);
    
        // Update the radio record to mark it as verified
        if ($radio['type'] === 'internal') {
            $radioRecord = RadioConfiguration::find($radio['id']);
        } else {
            $radioRecord = ExternalRadioConfiguration::find($radio['id']);
        }
    
        if ($radioRecord) {
            $radioRecord->update(['verified' => 1]);
        }
    
        $this->dispatchBrowserEvent('alert', [
            'type'    => 'success',
            'message' => __('Radio verified successfully.')
        ]);
    
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('subs-radios');
    }
    

    public function closeModal()
    {
        $this->reset(['radioSlug', 'verificationSelected']);
        $this->dispatchBrowserEvent('close-modal');
    }

    public function changeDuration($duration)
    {
        $this->durationSelect = $duration;
    }

    public function render()
    {
        $verifications = Verification::where('status', 1)->get();

        $internalRadios = RadioConfiguration::where('subscriber_id', auth()->guard('subscriber')->id())
            ->get(['id', 'radio_name', 'radio_name_slug']);

        $externalRadios = ExternalRadioConfiguration::where('subscriber_id', auth()->guard('subscriber')->id())
            ->get(['id', 'radio_name', 'radio_name_slug']);

        $this->availableRadios = collect($internalRadios)->map(function ($radio) {
            return [
                'id' => $radio->id,
                'name' => $radio->radio_name,
                'slug' => $radio->radio_name_slug,
                'type' => 'internal',
            ];
        })->merge(
            collect($externalRadios)->map(function ($radio) {
                return [
                    'id' => $radio->id,
                    'name' => $radio->radio_name,
                    'slug' => $radio->radio_name_slug,
                    'type' => 'external',
                ];
            })
        )->keyBy('slug');

        return view('subscriber.pages.radio-verify.verifyGrid', [
            'verifications' => $verifications,
            'availableRadios' => $this->availableRadios,
        ]);
    }
}
