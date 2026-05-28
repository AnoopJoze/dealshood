<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Locality;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function home()
    {
        $now   = Carbon::now();
        $today = Carbon::today();

        /*
        |----------------------------------------------------------------------
        | Top-row stat cards
        |----------------------------------------------------------------------
        */
        $stats = [
            // Posts
            'posts_total'     => Post::count(),
            'posts_published' => Post::where('status', 'published')->count(),
            'posts_draft'     => Post::where('status', 'draft')->count(),
            'posts_archived'  => Post::where('status', 'archived')->count(),
            'posts_today'     => Post::whereDate('created_at', $today)->count(),
            'posts_featured'  => Post::where('is_featured', true)->count(),
            'posts_expired'   => Post::whereNotNull('expiry_date')
                                     ->where('expiry_date', '<', $now)->count(),

            // Users
            'users_total'     => User::count(),
            'users_active'    => User::where('status', 'Active')->count(),
            'users_today'     => User::whereDate('created_at', $today)->count(),

            // Taxonomy
            'categories_total'    => Category::count(),
            'subcategories_total' => Subcategory::count(),
            'localities_total'    => Locality::count(),
            'roles_total'         => Role::count(),

            // Engagement
            'total_views'   => Post::sum('views'),
        ];

        /*
        |----------------------------------------------------------------------
        | Posts chart — last 12 months (published count per month)
        |----------------------------------------------------------------------
        */
        $postsByMonth = collect(range(11, 0))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            return [
                'label'     => $month->format('M Y'),
                'published' => Post::where('status', 'published')
                                   ->whereYear('created_at',  $month->year)
                                   ->whereMonth('created_at', $month->month)
                                   ->count(),
                'draft'     => Post::where('status', 'draft')
                                   ->whereYear('created_at',  $month->year)
                                   ->whereMonth('created_at', $month->month)
                                   ->count(),
            ];
        });

        /*
        |----------------------------------------------------------------------
        | Users chart — last 12 months (registrations per month)
        |----------------------------------------------------------------------
        */
        $usersByMonth = collect(range(11, 0))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);
            return [
                'label' => $month->format('M Y'),
                'count' => User::whereYear('created_at',  $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
            ];
        });

        /*
        |----------------------------------------------------------------------
        | Posts by category (top 8 for bar chart)
        |----------------------------------------------------------------------
        */
        $postsByCategory = Category::withCount('subcategories')
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->limit(8)
            ->get();

        /*
        |----------------------------------------------------------------------
        | Recent posts (last 8)
        |----------------------------------------------------------------------
        */
        $recentPosts = Post::with(['category', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        /*
        |----------------------------------------------------------------------
        | Recent users (last 6)
        |----------------------------------------------------------------------
        */
        $recentUsers = User::with('roles')->latest()->limit(6)->get();

        /*
        |----------------------------------------------------------------------
        | Top localities by post count
        |----------------------------------------------------------------------
        */
        $topLocalities = Locality::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(6)
            ->get();

        /*
        |----------------------------------------------------------------------
        | Top categories by post count
        |----------------------------------------------------------------------
        */
        $topCategories = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'stats',
            'postsByMonth',
            'usersByMonth',
            'postsByCategory',
            'recentPosts',
            'recentUsers',
            'topLocalities',
            'topCategories'
        ));
    }
}