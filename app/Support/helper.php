<?php

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