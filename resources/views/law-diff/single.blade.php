@extends('layouts.sbAdmin2')
@section('head-load')
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('法案對照表')</h1>
    </div>
    @include('partials.law-diff.related_bill_list')
@endsection
@section('body-load')
@endsection
