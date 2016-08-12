<?php

// Home
use App\Entities\Category;
use App\Entities\Repos;

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push(trans('front.home'), l_url('/'));
});

// Home > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans("category.{$category->slug}"), l_url('category', $category->slug));
});

// Home > [Category] > [Repos]
Breadcrumbs::register('repos', function ($breadcrumbs, $repos) {
    $breadcrumbs->parent('category', $repos->category);
    $breadcrumbs->push($repos->title, l_url('repos', $repos->slug));
});
