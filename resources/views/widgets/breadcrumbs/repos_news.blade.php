<ol class="breadcrumb">
    <li><a href="{{ l_url('/') }}">Home</a></li>
    <li><a href="{{ l_url('list/popular') }}">Repositories</a></li>
    @if($repos->category)
        <li><a href="{{ l_url('category', $repos->category->slug) }}">{{ trans("category.{$repos->category->slug}") }}</a></li>
    @endif
    <li><a href="{{ l_url('repos', $repos->slug) }}">{{ $repos->title }}</a></li>
    <li class="active">News</li>
</ol>
