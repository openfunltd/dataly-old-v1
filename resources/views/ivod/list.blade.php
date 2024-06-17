@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<h1 class="h3 mb-0 text-gray-800">IVOD 列表 :: {{ $date }}</h1>
    <a href="{{ route('ivods.datelist') }}" class="btn btn-primary">選其他日期</a>
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-sm nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>會議</th>
                            <th>時間</th>
                            <th>事由</th>
                            <th>關聯法律</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($meets as $meet)
                        <tr>
                            <td>
                                <a href="#{{ $meet->meet->id }}">
                                    {{ $meet->meet->title }}
                                </a>
                            </td>
                            <td>{{ date('H:i', strtotime($meet->meet->{'會議時間'})) }}</td>
                            <td style="word-wrap: break; align: left; max-width: 500px">{!! $meet->meet->{'會議名稱'} !!}</td>
                            <td>{!! $meet->meet->{'關聯法律'} !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

    @foreach ($meets as $meet)
    <div class="card shadow mb-4" id="{{ $meet->meet->id }}">
        <div class="card-header py-1">
            <h1 class="h3 mb-0 text-gray-800" id="#{{ $meet->meet->id }}">{{ $meet->meet->title }}</h1>
            <div>
                {!! $meet->meet->{'會議名稱'} !!}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-sm nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>委員名稱</th>
                            <th>發言時間</th>
                            <th>影片長度</th>
                            <th>IVOD連結</th>
                            <th>AI逐字稿</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($meet->ivods as $ivod)
                        <tr>
                            <td>
                                <a href="{{ route('ivod', $ivod->id) }}">
                                    {{ $ivod->{'委員名稱'} }}
                                </a>
                            </td>
                            <td>
                                {{ $ivod->{'委員發言時間'} }}
                            </td>
                            <td>
                                {{ $ivod->{'影片長度'} }}
                            </td>
                            <td>
                                <a href="{{ $ivod->url }}" target="_blank">IVOD</a>
                            </td>
                            <td>
                                @if (in_array('ai-transcript', $ivod->features))
                                有
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
    <!-- DataTales -->
@endsection
@section('body-load')
    <script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/meets/datatables.js') }}"></script>
@endsection
