<ol class="breadcrumb">
    <li><a href="{{ l_url('/') }}">Home</a></li>
    <li><a href="{{ l_url('list/popular') }}">Repositories</a></li>
    <li><a href="{{ l_url('category', $repos->category->slug) }}">{{ trans("category.{$repos->category->slug}") }}</a></li>
    <li class="active">{{ $repos->title }}</li>
</ol>
