<?php

use App\Entities\Image;
use League\Glide\Urls\UrlBuilderFactory;

if (!function_exists('l_url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool $secure
     * @return Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function l_url($path, $parameters = [], $secure = null)
    {
        $url = url($path, $parameters, $secure);

        return Localization::localizeURL($url);
    }
}


if (!function_exists('image_url')) {
    /**
     * @param $slug
     * @param $params
     * @return string
     */
    function image_url($slug, $params)
    {
        $signKey = env('GLIDE_KEY');
        $urlBuilder = UrlBuilderFactory::create('/', $signKey);

        $key = "goods:image:$slug";
        if (!Cache::has($key)) {
            $image = Image::where('slug', $slug)->first();
            if ($image) {
                Cache::put("goods:image:{$image->id}", $image, 365 * 24 * 60);
                Cache::put("goods:image:{$image->slug}", $image, 365 * 24 * 60);
            } else {
                return '';
            }
        } else {
            $image = Cache::get($key);
        }

        $urlBuilder->setBaseUrl('//' . getenv('STATIC_DOMAIN'));

        return $urlBuilder->getUrl("/image/{$image->slug}", $params);
    }

}

if (!function_exists('cdn_asset')) {
    /**
     * Generate a url for the application.
     *
     * @param  string $path
     * @return Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function cdn_asset($path)
    {
        return '//' . getenv('CDN_DOMAIN') . '/' . trim($path, '/');
    }
}
