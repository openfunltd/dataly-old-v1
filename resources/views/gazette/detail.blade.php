@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<h1 class="h3 mb-0 text-gray-800">公報列表 :: {{ $gazette_id }}</h1>
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-sm nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>屆期</th>
                            <th>會期</th>
                            <th>會議日期</th>
                            <th style="max-width: 300px">會議名稱</th>
                            <th>公報頁次</th>
                            <th>連結</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($agendas as $agenda)
                    <tr>
                        <td>{{ $agenda->term }}</td>
                        <td>{{ $agenda->sessionPeriod }}</td>
                        <td>{{ implode(',', $agenda->meetingDate) }}</td>
                        <td style="max-width: 300px">{{ $agenda->subject }}</td>
                        <td>{{ $agenda->pageStart }} - {{ $agenda->pageEnd }}</td>
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
