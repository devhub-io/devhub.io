<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $ip = request()->header('cf-connecting-ip');

        return empty($ip) ? request()->ip() : $ip;
    }
}
