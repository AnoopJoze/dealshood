<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        
    $totalPosts       = Post::count();
    $publishedPosts   = Post::where('status', 'published')->count();
    $draftPosts       = Post::where('status', 'draft')->count();
    $featuredPosts    = Post::where('is_featured', 1)->count();

    $totalUsers       = User::count();
    $activeUsers      = User::where('status', 'active')->count();

    $totalViews       = Post::sum('views');

    $recentPosts      = Post::latest()->take(5)->get();

    /*
    |--------------------------------------------------------------------------
    | Monthly Posts Chart
    |--------------------------------------------------------------------------
    */
    $monthlyPosts = Post::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $chartLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    $chartData = [];

    for ($i = 1; $i <= 12; $i++) {
        $chartData[] = $monthlyPosts[$i] ?? 0;
    }

    return view('dashboard', compact(
        'totalPosts',
        'publishedPosts',
        'draftPosts',
        'featuredPosts',
        'totalUsers',
        'activeUsers',
        'totalViews',
        'recentPosts',
        'chartLabels',
        'chartData'
    ));
    }
}
