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
     * @param $id
     * @param $params
     * @return string
     */
    function image_url($id, $params)
    {
        $signKey = env('GLIDE_KEY');
        $urlBuilder = UrlBuilderFactory::create('/', $signKey);

        $key = "goods:image:$id";
        if (!Cache::has($key)) {
            $image = Image::find($id);
            Cache::put($key, $image, 365 * 24 * 60);
            Cache::put("goods:image:{$image->slug}", $image, 365 * 24 * 60);
        } else {
            $image = Cache::get($key);
        }

        return $urlBuilder->getUrl("/image/{$image->slug}", $params);
    }

}
