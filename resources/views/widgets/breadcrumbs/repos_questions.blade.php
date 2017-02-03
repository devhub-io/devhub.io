<ol class="breadcrumb">
    <li><a href="{{ l_url('/') }}">Home</a></li>
    <li><a href="{{ l_url('list/popular') }}">Repositories</a></li>
    <li><a href="{{ l_url('category', $repos->category ? $repos->category->slug : '') }}">{{ $repos->category ? trans("category.{$repos->category->slug}") : '' }}</a></li>
    <li><a href="{{ l_url('repos', $repos->slug) }}">{{ $repos->title }}</a></li>
    <li class="active">Questions</li>
</ol>
