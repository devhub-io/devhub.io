@php
    $json = json_decode($package->json, true)
@endphp

{!! $json['version_history'] !!}
