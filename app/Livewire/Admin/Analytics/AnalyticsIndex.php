<?php

namespace App\Livewire\Admin\Analytics;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class AnalyticsIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'visitors'; // 'visitors' or 'downloads'

    #[Url]
    public string $period = 'all'; // 'today' | 'week' | 'month' | 'all'

    public function updatingTab(): void
    {
        $this->resetPage();
    }

    public function updatingPeriod(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Pick the model class based on the active tab.
        $modelClass = $this->tab === 'downloads'
            ? \App\Models\ResumeDownloadLog::class
            : \App\Models\VisitorLog::class;

        // The "activity" timestamp differs per table (one row per unique visitor).
        $dateColumn = $this->tab === 'downloads' ? 'last_downloaded_at' : 'last_seen_at';

        // Period thresholds.
        $thresholds = [
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
        ];

        // Stat-card counts — always computed for all 4 periods, regardless of selection.
        $counts = [
            'today' => $modelClass::query()->where($dateColumn, '>=', $thresholds['today'])->count(),
            'week' => $modelClass::query()->where($dateColumn, '>=', $thresholds['week'])->count(),
            'month' => $modelClass::query()->where($dateColumn, '>=', $thresholds['month'])->count(),
            'all' => $modelClass::query()->count(),
        ];

        // Period-filtered base query used for both the table and the top-N panels.
        $periodQuery = function () use ($modelClass, $thresholds, $dateColumn) {
            $q = $modelClass::query();
            if ($this->period !== 'all' && isset($thresholds[$this->period])) {
                $q->where($dateColumn, '>=', $thresholds[$this->period]);
            }

            return $q;
        };

        // Paginated activity list — most recently active first, 10 per page.
        $rows = $periodQuery()->orderByDesc($dateColumn)->paginate(10);

        // Top countries within the period filter.
        $topCountries = $periodQuery()
            ->whereNotNull('country')
            ->selectRaw('country, country_code, COUNT(*) as total')
            ->groupBy('country', 'country_code')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get();

        // Top referrers within the period filter.
        $topReferrers = $periodQuery()
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as total')
            ->groupBy('referrer')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get();

        // Top browsers within the period filter.
        $topBrowsers = $periodQuery()
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get();

        return view('livewire.admin.analytics.index', [
            'rows' => $rows,
            'counts' => $counts,
            'topCountries' => $topCountries,
            'topReferrers' => $topReferrers,
            'topBrowsers' => $topBrowsers,
        ]);
    }
}
