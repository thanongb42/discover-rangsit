<?php
class DeliveryPlatforms
{
    public static function all(): array
    {
        return [
            'lineman' => [
                'name'     => 'LINE MAN',
                'color'    => '#06C755',
                'bg'       => 'bg-[#06C755]',
                'icon'     => 'fa-brands fa-line',
                'url_hint' => 'https://lineman.line.me/restaurant/...',
                'pattern'  => '/^https?:\/\/(lineman\.line\.me|lin\.ee)\//i',
            ],
            'grabfood' => [
                'name'     => 'GrabFood',
                'color'    => '#00B14F',
                'bg'       => 'bg-[#00B14F]',
                'icon'     => 'fas fa-utensils',
                'url_hint' => 'https://food.grab.com/th/th/restaurant/...',
                'pattern'  => '/^https?:\/\/food\.grab\.com\//i',
            ],
            'foodpanda' => [
                'name'     => 'foodpanda',
                'color'    => '#D70F64',
                'bg'       => 'bg-[#D70F64]',
                'icon'     => 'fas fa-paw',
                'url_hint' => 'https://www.foodpanda.co.th/restaurant/...',
                'pattern'  => '/^https?:\/\/(www\.)?foodpanda\.co\.th\//i',
            ],
            'robinhood' => [
                'name'     => 'Robinhood',
                'color'    => '#5B2C8C',
                'bg'       => 'bg-[#5B2C8C]',
                'icon'     => 'fas fa-hat-wizard',
                'url_hint' => 'https://robinhood.in.th/...',
                'pattern'  => '/^https?:\/\/(www\.)?robinhood\.(in\.th|co\.th)\//i',
            ],
            'shopeefood' => [
                'name'     => 'ShopeeFood',
                'color'    => '#EE4D2D',
                'bg'       => 'bg-[#EE4D2D]',
                'icon'     => 'fas fa-shopping-bag',
                'url_hint' => 'https://shopee.co.th/...',
                'pattern'  => '/^https?:\/\/(www\.)?shopee\.co\.th\//i',
            ],
            'lalamove' => [
                'name'     => 'Lalamove',
                'color'    => '#F16622',
                'bg'       => 'bg-[#F16622]',
                'icon'     => 'fas fa-truck',
                'url_hint' => 'https://www.lalamove.com/th/...',
                'pattern'  => '/^https?:\/\/(www\.)?lalamove\.com\//i',
            ],
        ];
    }

    public static function get(string $key): ?array
    {
        return self::all()[$key] ?? null;
    }

    public static function validateUrl(string $platform, string $url): bool
    {
        $cfg = self::get($platform);
        if (!$cfg) return false;
        if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
        return (bool) preg_match($cfg['pattern'], $url);
    }
}
