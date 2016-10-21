<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $urlBuilder->setBaseUrl('//' . env('STATIC_DOMAIN'));

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
        return '//' . env('CDN_DOMAIN') . '/' . trim($path, '/');
    }
}

if (!function_exists('link_url')) {
    /**
     * Generate a url for the application.
     *
     * @param $url
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function link_url($url)
    {
        return url("link?target=" . urlencode($url));
    }
}

if (!function_exists('stackoverflow_tagged_url')) {
    /**
     * stackoverflow tagged url
     *
     * @param $tag
     * @return string
     */
    function stackoverflow_tagged_url($tag)
    {
        return 'http://stackoverflow.com/questions/tagged/' . $tag;
    }
}

if (!function_exists('badge_image_url')) {
    /**
     * badge image url
     *
     * @param $name
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function badge_image_url($name)
    {
        return cdn_asset('img/badges/' . strtolower($name) . '.png');
    }
}

if (!function_exists('real_ip')) {
    /**
     * real visitor ip
     *
     * @return string
     */
    function real_ip()
    {
        $ips = request()->header('cf-connecting-ip');
        if (isset($ips[0])) {
            return $ips[0];
        } else {
            return request()->ip();
        }
    }
}
