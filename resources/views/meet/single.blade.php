@extends('layouts.sbAdmin2')
@section('head-load')
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('會議')</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">會議資料</h6>
            </div>
            <div class="col-auto d-none d-md-inline">
                <span>本區資料來自 <a href="https://data.ly.gov.tw/getds.action?id=42">立法院資料開放平台</a></span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php $keys = array_keys($meet_data); ?>
        @foreach ($meet_data as $idx => $data)
            <p class="text-lg text-primary">日期：{{ $data['date']}}</p>
            <div class="table-responsive">
                <table class="table table-sm" width="100%" cellspacing="0">
                    <tbody>
                        @foreach ($data as $key => $val)
                        <tr>
                            <th scpoe="row" class="col-3">{{ $key }}</th>
                            <td class="col-9">
                                @if (is_string($val) && strpos($val, 'https://') === 0)
                                    <a href="{{ $val }}">{{ $val }}</a>
                                @elseif (is_array($val) && empty($val))
                                    Empty Array
                                @elseif (is_null($val) || $val == 'null')
                                    Null
                                @else
                                    {{ $val }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($idx != end($keys))
                <br>
            @endif
        @endforeach
    </div>
</div>
@endsection
@section('body-load')
@endsection
