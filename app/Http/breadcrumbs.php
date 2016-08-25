<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push(trans('front.home'), l_url('/'));
});

// Home > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('home');
    if (!empty($category->slug)) {
        $breadcrumbs->push(trans("category.{$category->slug}"), l_url('category', $category->slug));
    }
});

// Home > [Category] > [Repos]
Breadcrumbs::register('repos', function ($breadcrumbs, $repos) {
    $breadcrumbs->parent('category', $repos->category);
    $breadcrumbs->push($repos->title, l_url('repos', $repos->slug));
});
