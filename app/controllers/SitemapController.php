<?php
class SitemapController extends Controller {
    public function index() {
        $placeModel = $this->model('Place');
        $places = $placeModel->getAllApproved();

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticPages = [
            ['url' => BASE_URL,              'priority' => '1.0', 'freq' => 'daily'],
            ['url' => BASE_URL . '/city-map','priority' => '0.9', 'freq' => 'weekly'],
            ['url' => BASE_URL . '/trending','priority' => '0.8', 'freq' => 'daily'],
        ];
        foreach ($staticPages as $page) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($page['url']) . "</loc>\n";
            echo "    <changefreq>" . $page['freq'] . "</changefreq>\n";
            echo "    <priority>" . $page['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        // Place detail pages
        foreach ($places as $place) {
            if (empty($place->slug)) continue;
            $lastmod = !empty($place->updated_at) ? date('Y-m-d', strtotime($place->updated_at)) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars(BASE_URL . '/place/' . $place->slug) . "</loc>\n";
            echo "    <lastmod>" . $lastmod . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.7</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }
}
