<?php

namespace App\Http\Livewire\Subscriber\Promotion;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Promotion;
use App\Models\RadioPromotion;
use App\Models\RadioVerification;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;

class NewPromoLivewire extends Component
{
    public $radioSlug;
    public $sponserText;
    public $selectedPromotionId;
    public $promoSelected;
    public $availableRadios = [];

    // Pricing calculation properties
    public $calculatedDiscount = 0;
    public $calculatedFinalPrice = 0;
    public $calculatedVerificationId = null;

    // New properties for demographic filters (as arrays)
    public $targetGenders = [];     // e.g. [1] for Male only, or [1,2] for Male and Female
    public $targetAgeRanges = [];     // e.g. ["13-17", "18-25"]

    protected $rules = [
        'radioSlug'    => 'required|string',
        'sponserText'  => 'required|string|max:50',
        'targetGenders'=> 'required|array',
        'targetGenders.*' => 'integer|in:1,2,3', // exclude 0 ("All") if using checkboxes for specifics
        'targetAgeRanges' => 'required|array',
        'targetAgeRanges.*' => 'in:13-17,18-25,26-32,33-40,41-64,65+',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if (in_array($propertyName, ['radioSlug', 'selectedPromotionId'])) {
            $this->calculatePricing();
        }
    }

    public function selectPromotion($id)
    {
        $this->promoSelected = Promotion::where('id', $id)
            ->where('status', 1)
            ->first();
        $this->selectedPromotionId = $id;
        $this->calculatePricing();
    }

    public function calculatePricing()
    {
        $this->calculatedDiscount = 0;
        $this->calculatedFinalPrice = 0;
        $this->calculatedVerificationId = null;

        if ($this->promoSelected && $this->radioSlug && isset($this->availableRadios[$this->radioSlug])) {
            $selectedRadio = $this->availableRadios[$this->radioSlug];
            $modelClass = $selectedRadio['type'] === 'internal'
                ? RadioConfiguration::class
                : ExternalRadioConfiguration::class;
            $verif = RadioVerification::where('radioable_id', $selectedRadio['id'])
                ->where('radioable_type', $modelClass)
                ->latest()
                ->first();
            if ($verif) {
                // Ensure the field name is correct. Here it's assumed to be "ad_dicount" (if typo, update it)
                if ($verif->ad_dicount) {
                    $this->calculatedDiscount = $verif->ad_dicount;
                }
                $this->calculatedVerificationId = $verif->id;
            }
            $this->calculatedFinalPrice = max(
                $this->promoSelected->sell - ($this->promoSelected->sell * $this->calculatedDiscount / 100), 
                0
            );
        }
    }

    public function promoteRadio()
    {
        try {
            $validated = $this->validate([
                'radioSlug'           => 'required|string',
                'selectedPromotionId' => 'required|exists:promotions,id',
                'sponserText'         => 'required|string|max:50',
                'targetGenders'       => 'required|array',
                'targetGenders.*'     => 'integer|in:1,2,3',
                'targetAgeRanges'     => 'required|array',
                'targetAgeRanges.*'   => 'in:13-17,18-25,26-32,33-40,41-64,65+',
            ]);

            $radio = $this->availableRadios[$this->radioSlug] ?? null;
            if (!$radio) {
                $this->addError('radioSlug', __('Radio not found.'));
                return;
            }

            $radioModel = $radio['type'] === 'internal'
                ? RadioConfiguration::class
                : ExternalRadioConfiguration::class;

            $promotionDate = now();
            $promotion = Promotion::findOrFail($this->selectedPromotionId);
            $expireDate = now()->addDays($promotion->duration);

            $finalPrice = $this->calculatedFinalPrice;
            $discount  = $this->calculatedDiscount;

            RadioPromotion::create([
                'subscriber_id'    => auth()->guard('subscriber')->id(),
                'radioable_id'     => $radio['id'],
                'radioable_type'   => $radio['type'] === 'internal'
                                         ? RadioConfiguration::class
                                         : ExternalRadioConfiguration::class,
                'promotion_id'     => $promotion->id,
                'verification_id'  => $this->calculatedVerificationId,
                'promotion_text'   => $validated['sponserText'],
                'promotion_date'   => $promotionDate,
                'expire_date'      => $expireDate,
                'price'            => $finalPrice,
                'discount'         => $discount,
                'status'           => 1,
                'target_gender'    => $this->targetGenders,    // Storing as JSON array
                'target_age_range' => $this->targetAgeRanges,    // Storing as JSON array
            ]);

            $this->dispatchBrowserEvent('alert', [
                'type'    => 'success',
                'message' => __('Radio promotion activated successfully.')
            ]);

            $this->dispatchBrowserEvent('close-modal');
            return redirect()->route('subs-radios');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type'    => 'error',
                'message' => __('Err: ') . $e->getMessage()
            ]);
        }
    }

    public function closeModal()
    {
        $this->reset([
            'radioSlug', 'selectedPromotionId', 'promoSelected', 
            'calculatedDiscount', 'calculatedFinalPrice', 'calculatedVerificationId',
            'targetGenders', 'targetAgeRanges'
        ]);
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $promotions = Promotion::where('status', 1)->get();

        $internalRadios = RadioConfiguration::where('subscriber_id', auth()->guard('subscriber')->id())
            ->get(['id', 'radio_name', 'radio_name_slug']);

        $externalRadios = ExternalRadioConfiguration::where('subscriber_id', auth()->guard('subscriber')->id())
            ->get(['id', 'radio_name', 'radio_name_slug']);

        $this->availableRadios = collect($internalRadios)->map(function ($radio) {
            return [
                'id'   => $radio->id,
                'name' => $radio->radio_name,
                'slug' => $radio->radio_name_slug,
                'type' => 'internal',
            ];
        })->merge(
            collect($externalRadios)->map(function ($radio) {
                return [
                    'id'   => $radio->id,
                    'name' => $radio->radio_name,
                    'slug' => $radio->radio_name_slug,
                    'type' => 'external',
                ];
            })
        )->keyBy('slug');

        return view('subscriber.pages.radio-promotion.promoGrid', [
            'promotions'      => $promotions,
            'availableRadios' => $this->availableRadios,
            'calculatedDiscount'   => $this->calculatedDiscount,
            'calculatedFinalPrice' => $this->calculatedFinalPrice,
        ]);
    }
}
