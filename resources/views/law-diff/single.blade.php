@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/law-diff/custom.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('法案對照表')</h1>
    </div>
    @include('partials.law-diff.related_bill_list')
    <div class="row">
        <div class="col-lg-2">
            @include('partials.law-diff.law_idx_list')
        </div>
        <div class="col-lg-10">
            @foreach ($diff_result as $law_idx => $diff)
                @include('partials.law-diff.diff_list')
            @endforeach
        </div>
    </div>
@endsection
@section('body-load')
@endsection
