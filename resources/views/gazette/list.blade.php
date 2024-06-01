@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<h1 class="h3 mb-0 text-gray-800">公報列表 :: {{ $year }}</h1>
<div>
    年份：
    @foreach ($gazette_stat->comYears as $comYear)
    <a href="{{ route('gazettes.year', ['year' => 1911 + $comYear->year ]) }}" class="btn btn-primary btn-sm">{{ $comYear->year + 1911}}</a>
    @endforeach
</div>
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-sm nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>comYear</th>
                            <th>comVolume</th>
                            <th>comBookId</th>
                            <th>出版日期</th>
                            <th>連結</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($gazettes as $gazette)
                        <tr>
                            <td> {{ $gazette->comYear }} </td>
                            <td> {{ $gazette->comVolume }} </td>
                            <td> {{ $gazette->comBookId }} </td>
                            <td> {{ $gazette->published_at }} </td>
                            <td>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

    <!-- DataTales -->
@endsection
@section('body-load')
    <script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/meets/datatables.js') }}"></script>
@endsection
