<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class getHoroscopesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:getHoroscopes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬取十二星座每日運勢資料';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $data = [];

        $target_url = "https://astro.click108.com.tw/";
        $url_html = Http::get($target_url)->body();

        $crawler = new Crawler($url_html);
        $crawler->filterXPath('//div[contains(@class, "STAR12_BOX")]/ul/li/a')->each(function(Crawler $node, $i) use (&$data, $now) {
            $name = $node->text();
            $constellation_url = $node->attr('href');
            $redirect_url = urldecode(explode("RedirectTo=", $constellation_url)[1]);
            $constellation_html = Http::get($redirect_url)->body();

            $constellation_crawler = new Crawler($constellation_html);

            $data[] = [
                "name"          => $name,
                "date"          => $now->format("Y-m-d"),
                "overall_score" => $constellation_crawler->filterXPath('//span[contains(@class, "txt_green")]')->text(),
                "overall"       => $constellation_crawler->filterXPath('//span[contains(@class, "txt_green")]')->parents()->nextAll()->text(),
                "love_score"    => $constellation_crawler->filterXPath('//span[contains(@class, "txt_pink")]')->text(),
                "love"          => $constellation_crawler->filterXPath('//span[contains(@class, "txt_pink")]')->parents()->nextAll()->text(),
                "career_score"  => $constellation_crawler->filterXPath('//span[contains(@class, "txt_blue")]')->text(),
                "career"        => $constellation_crawler->filterXPath('//span[contains(@class, "txt_blue")]')->parents()->nextAll()->text(),
                "wealth_score"  => $constellation_crawler->filterXPath('//span[contains(@class, "txt_orange")]')->text(),
                "wealth"        => $constellation_crawler->filterXPath('//span[contains(@class, "txt_orange")]')->parents()->nextAll()->text(),
                "created_at"    => $now,
                "updated_at"    => $now,
            ];
        });

        DB::table('horoscopes')->insert($data);

        return 0;
    }
}
