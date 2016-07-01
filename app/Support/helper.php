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
            Cache::put($key, $image, 365 * 24 * 60);
        }

        return $urlBuilder->getUrl("/image/$slug", $params);
    }

}
