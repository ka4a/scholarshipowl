<?php namespace App\Http\Controllers\Index;

use App\Entity\Scholarship;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Roumen\Sitemap\Sitemap;

class SitemapController extends BaseController
{
    const CACHE_KEY_SITEMAP = "SITEMAP";

    public function sitemapAction(){
        $lastMonth = new Carbon("last month");

        /** @var Sitemap $sitemap */
        $sitemap = \App::make("sitemap");
        $sitemap->setCache(self::CACHE_KEY_SITEMAP, 60 * 24);

        if (!$sitemap->isCached()) {
            // Add static pages
            $sitemap->add(\URL::to("/"), $lastMonth, "1.0", "monthly");
            $sitemap->add(\URL::to("/about-us"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/help"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/faq"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/whoweare"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/whatwedo"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/additional-services"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/contact"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/register"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/privacy"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/terms"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/what-people-say-about-scholarshipowl"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/advertise-with-us"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/partners"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/ebook"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/list-your-scholarship"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/partnerships"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/press"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/winners"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/awards/scholarship-winners"), $lastMonth, "0.8", "monthly");
            $sitemap->add(\URL::to("/promotion-rules"), $lastMonth, "0.8", "monthly");

            $scholarships = \EntityManager::getRepository(Scholarship::class)
                ->matching(Criteria::create()
                    ->where(Criteria::expr()->andX(
                            Criteria::expr()->eq('isActive', true)
                        ))
                    ->orderBy(array('expirationDate' => Criteria::ASC)));

            /** @var Scholarship $scholarship */
            foreach ($scholarships as $scholarship) {
                $sitemap->add($scholarship->getPublicUrl(), $lastMonth, "0.8", "monthly");
            }
        }

        return $sitemap->render("xml");
    }

}
