<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Anime;

class GenerateSitemap extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for KawaNime';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/anime-list'));

        foreach (Anime::with('episodes')->get() as $anime) {
            $sitemap->add(Url::create("/anime/{$anime->slug}"));

            foreach ($anime->episodes as $episode) {
                $sitemap->add(Url::create("/watch/{$episode->slug}"));
            }
        }

        $publicHtmlPath = env('PUBLIC_HTML_PATH', '');
        $sitemap->writeToFile(base_path("../public_html/{$publicHtmlPath}/kawanime-sitemap.xml"));

        $this->info('Sitemap generated!');
    }
}
