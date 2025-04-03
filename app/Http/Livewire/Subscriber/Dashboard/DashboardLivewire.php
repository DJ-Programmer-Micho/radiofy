<?php

namespace App\Http\Livewire\Subscriber\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\RadioConfiguration;
use App\Models\ExternalRadioConfiguration;
use Carbon\Carbon;

class DashboardLivewire extends Component
{
    public $year;
    /**
     * $month can be a number (1-12) or "all" to show yearly (monthly aggregated) data.
     */
    public $month;
    
    // Data arrays for the Listeners Chart.
    public $uniqueData = [];
    public $nonUniqueData = [];
    public $highestPeakData = [];
    
    // Labels for the chart – either day numbers or month names.
    public $labels = [];
    
    // Total counts for radios.
    public $totalInternalRadios = 0;
    public $totalExternalRadios = 0;
    
    // New counts for social stats.
    public $totalLikes = 0;
    public $totalFollowers = 0;
    public $totalListeners2 = 0;
    public $nonUniqueListeners2 = 0;
    
    public function mount()
    {
        // Default selections: current year and current month.
        $this->year = now()->year;
        $this->month = now()->month; // can be number or "all"
        
        $this->loadListenersData();
        $this->loadRadioCounts();
        $this->loadSocialCounts();
    }
    
    public function updatedYear()
    {
        $this->loadListenersData();
    }
    
    public function updatedMonth()
    {
        $this->loadListenersData();
    }
    
    protected function loadListenersData()
    {
        // Get the authenticated subscriber.
        $subscriber = auth()->guard('subscriber')->user();
        
        if ($this->month === 'all') {
            $this->labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $this->uniqueData = array_fill(0, 12, 0);
            $this->nonUniqueData = array_fill(0, 12, 0);
            $this->highestPeakData = array_fill(0, 12, 0);
            
            $results = DB::table('listener_radios')
                ->join('radio_configurations', 'listener_radios.radioable_id', '=', 'radio_configurations.id')
                ->select(DB::raw('MONTH(listener_radios.created_at) as period, COUNT(*) as non_unique, COUNT(DISTINCT listener_radios.listener_id) as unique_listeners'))
                ->where('radio_configurations.subscriber_id', $subscriber->id)
                ->whereYear('listener_radios.created_at', $this->year)
                ->groupBy('period')
                ->orderBy('period')
                ->get();
                
            foreach ($results as $result) {
                $index = $result->period - 1;
                $this->uniqueData[$index] = $result->unique_listeners;
                // Optionally subtract unique listeners if you want to show additional plays.
                $this->nonUniqueData[$index] = $result->non_unique - $result->unique_listeners;
                $this->highestPeakData[$index] = round($result->non_unique * 1.1);
            }
        } else {
            $daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;
            $this->labels = range(1, $daysInMonth);
            $this->uniqueData = array_fill(0, $daysInMonth, 0);
            $this->nonUniqueData = array_fill(0, $daysInMonth, 0);
            $this->highestPeakData = array_fill(0, $daysInMonth, 0);
            
            $results = DB::table('listener_radios')
                ->join('radio_configurations', 'listener_radios.radioable_id', '=', 'radio_configurations.id')
                ->select(DB::raw('DAY(listener_radios.created_at) as period, COUNT(*) as non_unique, COUNT(DISTINCT listener_radios.listener_id) as unique_listeners'))
                ->where('radio_configurations.subscriber_id', $subscriber->id)
                ->whereYear('listener_radios.created_at', $this->year)
                ->whereMonth('listener_radios.created_at', $this->month)
                ->groupBy('period')
                ->orderBy('period')
                ->get();
                
            foreach ($results as $result) {
                $index = $result->period - 1;
                $this->uniqueData[$index] = $result->unique_listeners;
                $this->nonUniqueData[$index] = $result->non_unique - $result->unique_listeners;
                $this->highestPeakData[$index] = round($result->non_unique * 1.1);
            }
        }
        
        // Emit an event with the updated data so that the JavaScript chart can update.
        $this->emit('listenersDataUpdated', [
            'labels' => $this->labels,
            'uniqueData' => $this->uniqueData,
            'nonUniqueData' => $this->nonUniqueData,
            'highestPeakData' => $this->highestPeakData
        ]);
    }
    
    protected function loadRadioCounts()
    {
        $subscriber = auth()->guard('subscriber')->user();
        
        $this->totalInternalRadios = RadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)
            ->count();
            
        $this->totalExternalRadios = ExternalRadioConfiguration::where('subscriber_id', $subscriber->id)
            ->where('status', 1)
            ->count();
    }
    
    protected function loadSocialCounts()
    {
        $subscriber = auth()->guard('subscriber')->user();
        
        // Total Likes for the subscriber's internal radios.
        $this->totalLikes = DB::table('likes')
            ->join('radio_configurations', 'likes.radioable_id', '=', 'radio_configurations.id')
            ->where('radio_configurations.subscriber_id', $subscriber->id)
            ->where('likes.radioable_type', 'internal')
            ->count();
        
        // Total Followers for the subscriber's internal radios.
        $this->totalFollowers = DB::table('follows')
            ->join('radio_configurations', 'follows.radioable_id', '=', 'radio_configurations.id')
            ->where('radio_configurations.subscriber_id', $subscriber->id)
            ->where('follows.radioable_type', 'internal')
            ->count();
        
        // Total Plays (listener_radios) for the subscriber's internal radios.
        $totalPlays = DB::table('listener_radios')
            ->join('radio_configurations', 'listener_radios.radioable_id', '=', 'radio_configurations.id')
            ->where('radio_configurations.subscriber_id', $subscriber->id)
            ->count();
        
        // Unique Listeners: count distinct listener_id in listener_radios for these radios.
        $uniquePlays = DB::table('listener_radios')
            ->join('radio_configurations', 'listener_radios.radioable_id', '=', 'radio_configurations.id')
            ->where('radio_configurations.subscriber_id', $subscriber->id)
            ->distinct('listener_radios.listener_id')
            ->count('listener_radios.listener_id');
        
        // Set the social counts.
        $this->totalListeners2 = $uniquePlays;
        $this->nonUniqueListeners2 = $totalPlays - $uniquePlays;
    }
    
    public function render()
    {
        return view('subscriber.pages.dashboard.container', [
            'labels' => $this->labels,
            'uniqueData' => $this->uniqueData,
            'nonUniqueData' => $this->nonUniqueData,
            'highestPeakData' => $this->highestPeakData,
            'totalInternalRadios' => $this->totalInternalRadios,
            'totalExternalRadios' => $this->totalExternalRadios,
            'totalLikes' => $this->totalLikes,
            'totalFollowers' => $this->totalFollowers,
            'totalListeners2' => $this->totalListeners2,
            'nonUniqueListeners2' => $this->nonUniqueListeners2,
        ]);
    }
}
