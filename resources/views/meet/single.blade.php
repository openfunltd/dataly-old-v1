@extends('layouts.sbAdmin2')
@section('head-load')
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('會議')</h1>
    </div>
    @include('partials.meet.meet_data')
    @include('partials.meet.meet_note')
    @include('partials.meet.speeches')
    @include('partials.meet.gazette')
    @include('partials.meet.ivod')
    @include('partials.meet.written_i12n')
@endsection
@section('body-load')
@endsection
