<?php

namespace App\Console\Commands;
 
use App\Models\Category;
use App\Models\Locality;
use App\Models\Post;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
 
class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';
 
    public function handle(): void
    {
        $sitemap = Sitemap::create();
 
        // ── Static pages ──────────────────────────────
        $sitemap->add(
            Url::create('/')
               ->setChangeFrequency('daily')
               ->setPriority(1.0)
               ->setLastModificationDate(now())
        );
 
        $sitemap->add(
            Url::create('/browse')
               ->setChangeFrequency('daily')
               ->setPriority(0.9)
        );
 
        // ── Categories ────────────────────────────────
        Category::all()->each(function ($cat) use ($sitemap) {
            $sitemap->add(
                Url::create(route('posts.listing', ['category_id' => $cat->slug], false))
                   ->setChangeFrequency('daily')
                   ->setPriority(0.8)
                   ->setLastModificationDate($cat->updated_at)
            );
        });
 
        // ── Localities ────────────────────────────────
        Locality::all()->each(function ($loc) use ($sitemap) {
            $sitemap->add(
                Url::create(route('posts.listing', ['locality_id' => $loc->slug], false))
                   ->setChangeFrequency('daily')
                   ->setPriority(0.7)
            );
        });
 
        // ── Published posts ───────────────────────────
        Post::where('status', 'published')
            ->with(['locality', 'category', 'subcategory'])
            ->select(['id', 'slug', 'locality_id', 'category_id', 'subcategory_id', 'updated_at'])
            ->cursor()
            ->each(function ($post) use ($sitemap) {

                // Some posts may not have all params — skip if missing
                if (!$post->locality || !$post->category) return;

                $sitemap->add(
                    Url::create($post->url)
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.6)
                    ->setLastModificationDate($post->updated_at)
                );
            });
 
        // Write to public/sitemap.xml
        $sitemap->writeToFile(public_path('sitemap.xml'));
 
        $this->info('Sitemap generated: ' . public_path('sitemap.xml'));
    }
}