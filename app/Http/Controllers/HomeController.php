<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Locality;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Services\AnalyticsService;

class HomeController extends Controller
{
    public function home(AnalyticsService $analytics)
    {
        $now   = Carbon::now();
        $today = Carbon::today();
        $isAuthor = auth()->user()->hasRole('author');
        $userId   = auth()->id();

        // Base post scope — authors only see their own
        $postScope = fn($q) => $isAuthor ? $q->where('user_id', $userId) : $q;

        $stats = [
            'posts_total'     => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->count(),
            'posts_published' => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->where('status','published')->count(),
            'posts_draft'     => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->where('status','draft')->count(),
            'posts_archived'  => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->where('status','archived')->count(),
            'posts_today'     => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->whereDate('created_at',$today)->count(),
            'posts_featured'  => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->where('is_featured',true)->count(),
            'posts_expired'   => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->whereNotNull('expiry_date')->where('expiry_date','<',$now)->count(),
            'total_views'     => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))->sum('views'),

            'users_total'         => $isAuthor ? null : User::count(),
            'users_active'        => $isAuthor ? null : User::where('status','Active')->count(),
            'users_today'         => $isAuthor ? null : User::whereDate('created_at',$today)->count(),
            'categories_total'    => Category::count(),
            'subcategories_total' => Subcategory::count(),
            'localities_total'    => Locality::count(),
            'roles_total'         => $isAuthor ? null : Role::count(),
        ];

        $postsByMonth = collect(range(11,0))->map(function($i) use ($isAuthor, $userId) {
            $month = Carbon::now()->subMonths($i);
            return [
                'label'     => $month->format('M Y'),
                'published' => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))
                                   ->where('status','published')
                                   ->whereYear('created_at',$month->year)
                                   ->whereMonth('created_at',$month->month)->count(),
                'draft'     => Post::when($isAuthor, fn($q)=>$q->where('user_id',$userId))
                                   ->where('status','draft')
                                   ->whereYear('created_at',$month->year)
                                   ->whereMonth('created_at',$month->month)->count(),
            ];
        });

        $recentPosts = Post::with(['category','user'])
            ->when($isAuthor, fn($q)=>$q->where('user_id',$userId))
            ->latest()->limit(8)->get();

        $postsByCategory = Category::withCount(['posts' => fn($q)=>$q->where('status','published')])
            ->orderByDesc('posts_count')->limit(8)->get();

        $topLocalities = Locality::withCount('posts')->orderByDesc('posts_count')->limit(6)->get();
        $topCategories = Category::withCount(['posts' => fn($q)=>$q->where('status','published')])
            ->orderByDesc('posts_count')->limit(6)->get();

        $recentUsers = $isAuthor ? collect() : User::with('roles')->latest()->limit(6)->get();
        $usersByMonth = $isAuthor ? collect() : collect(range(11,0))->map(function($i) {
            $month = Carbon::now()->subMonths($i);
            return ['label'=>$month->format('M Y'), 'count'=>User::whereYear('created_at',$month->year)->whereMonth('created_at',$month->month)->count()];
        });

        // Google Analytics — only fetch for non-authors (admin-only), skip errors gracefully
        $gaTotals = null;
        $gaChartData = collect();
        if (!$isAuthor) {
            try {
                $gaTotals = $analytics->getTotals();
                $gaChartData = collect($analytics->getSummary());
            } catch (\Throwable $e) {
                \Log::warning('GA fetch failed: '.$e->getMessage());
            }
        }

        return view('dashboard', compact(
            'stats','postsByMonth','usersByMonth','postsByCategory',
            'recentPosts','recentUsers','topLocalities','topCategories',
            'gaTotals','gaChartData'
        ));
    }
}
