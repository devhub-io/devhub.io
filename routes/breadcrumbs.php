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

// Home > [repositories]
Breadcrumbs::register('repositories', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Repositories', l_url('list/popular'));
});

// Home > [repositories] > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('repositories');
    if (!empty($category->slug)) {
        $breadcrumbs->push(trans("category.{$category->slug}"), l_url('category', $category->slug));
    }
});

// Home > [repositories] > [Category] > [Repos]
Breadcrumbs::register('repos', function ($breadcrumbs, $repos) {
    $breadcrumbs->parent('category', $repos->category);
    $breadcrumbs->push($repos->title, l_url('repos', $repos->slug));
});

// Home > [Developers]
Breadcrumbs::register('developers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Developers', l_url('developers'));
});

// Home > [Developers] > [Developer]
Breadcrumbs::register('developer', function ($breadcrumbs, $developer) {
    $breadcrumbs->parent('developers');
    if (!empty($developer->login)) {
        $breadcrumbs->push("$developer->name ($developer->login)", l_url('developer', $developer->login));
    }
});
