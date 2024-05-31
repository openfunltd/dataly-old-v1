<?php
$week_data = array('日', '一', '二', '三', '四', '五', '六');
?>
@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<h1 class="h3 mb-0 text-gray-800">IVOD 每日列表 :: 第 {{ $term }} 屆第 {{ $sessionPeriod }} 會期</h1>
    @include('ivod.term-choice')
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-sm nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>日期</th>
                            <th>星期</th>
                            <th>IVod數量</th>
                            <th>前往</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($dates as $date_count)
                        <tr>
                        <td>
                            {{ date('Y-m-d', $date_count['date']) }}
                        </td>
                        <td>
                            {{ $week_data[date('w', $date_count['date'])] }}
                        </td>
                        <td>
                            {{ $date_count['count'] }}
                        </td>
                        <td>
                            <a href="{{ route('ivods.date', ['date' => date('Y-m-d', $date_count['date'])]) }}">列表</a>
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
