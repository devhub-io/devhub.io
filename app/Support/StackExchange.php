<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Support;

use GuzzleHttp\Client;

class StackExchange
{
    public function __construct($token)
    {
        $this->client = new Client();
        $this->access_token = $token;
        $this->key = \Config::get('services.stackexchange.key');
    }

    public function faq($tag)
    {
        $tag = urlencode($tag);
        $res = $this->client->request('GET', "https://api.stackexchange.com/2.2/tags/$tag/faq", [
            'query' => [
                'pagesize' => 10,
                'site' => 'stackoverflow',
                'access_token' => $this->access_token,
                'key' => $this->key,
            ]
        ]);

        if ($res->getStatusCode() == '200') {
            return \GuzzleHttp\json_decode($res->getBody(), true);
        } else {
            return [];
        }
    }
}
