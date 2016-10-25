<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
	'use_cache'			=> 	false,
	'cache_key'			=> 	'laravel-sitemap.' . config('app.url'),
	'cache_duration'	=> 	3600,
	'escaping'			=> 	true,
	'use_limit_size'	=> 	false,
	'max_size'			=> 	null,
	'use_styles'		=> 	true,
	'styles_location'	=> 	null,
];
