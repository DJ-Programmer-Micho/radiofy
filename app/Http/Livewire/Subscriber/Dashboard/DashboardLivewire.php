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
    public $totalListeners = 0;
    
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
        if ($this->month === 'all') {
            $this->labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $this->uniqueData = array_fill(0, 12, 0);
            $this->nonUniqueData = array_fill(0, 12, 0);
            $this->highestPeakData = array_fill(0, 12, 0);
            
            $results = DB::table('listener_radios')
                ->select(DB::raw('MONTH(created_at) as period, COUNT(*) as non_unique, COUNT(DISTINCT listener_id) as unique_listeners'))
                ->whereYear('created_at', $this->year)
                ->groupBy('period')
                ->orderBy('period')
                ->get();
                
            foreach ($results as $result) {
                $index = $result->period - 1;
                $this->uniqueData[$index] = $result->unique_listeners;
                // For non-unique, you can subtract the unique listeners if needed:
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
                ->select(DB::raw('DAY(created_at) as period, COUNT(*) as non_unique, COUNT(DISTINCT listener_id) as unique_listeners'))
                ->whereYear('created_at', $this->year)
                ->whereMonth('created_at', $this->month)
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
        
        // Emit an event with the updated data so JS can update the charts.
        $this->emit('listenersDataUpdated', [
            'labels' => $this->labels,
            'uniqueData' => $this->uniqueData,
            'nonUniqueData' => $this->nonUniqueData,
            'highestPeakData' => $this->highestPeakData
        ]);
    }
    
    protected function loadRadioCounts()
    {
        $this->totalInternalRadios = RadioConfiguration::where('status', 1)->count();
        $this->totalExternalRadios = ExternalRadioConfiguration::where('status', 1)->count();
    }
    
    public $totalListeners2 = 0;
    public $nonUniqueListeners2 = 0;
    protected function loadSocialCounts()
    {
        // Total Likes: count all rows in likes table.
        $this->totalLikes = DB::table('likes')->count();
        
        // Total Followers: count all rows in follows table.
        $this->totalFollowers = DB::table('follows')->count();
        
        // Total Plays: count all rows in listener_radios table.
        $totalPlays = DB::table('listener_radios')->count();
        
        // Unique Listeners: count distinct listener_id in listener_radios table.
        $uniquePlays = DB::table('listener_radios')->distinct('listener_id')->count('listener_id');
        
        // Total Listeners is set to the number of unique listener IDs.
        $this->totalListeners2 = $uniquePlays;
        
        // Non-Unique Listeners: total plays minus unique plays.
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
