@extends('layouts.sbAdmin2')
@section('head-load')
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('會議')</h1>
</div>
<div class="card shadow mb-4">
    <a href="#collapseCardMeetData" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardMeetData">
        <h6 class="m-0 font-weight-bold text-primary">
            會議資料
        </h6>
    </a>
    <div class="collapse show" id="collapseCardMeetData">
        <div class="card-body">
            <?php $keys = array_keys($meet_data); ?>
            @foreach ($meet_data as $idx => $data)
                @if ($idx == reset($keys))
                <div class="row">
                    <div class="col">
                        <p id="meetdata-{{ $data['date'] }}" class="text-lg text-primary">日期：{{ $data['date']}}</p>
                    </div>
                    <div class="col">
                        <p class="text-right">本區資料來自<a href="https://data.ly.gov.tw/getds.action?id=42">立法院資料開放平台</a></p>
                    </div>
                </div>
                @else
                    <p id="meetdata-{{ $data['date'] }}" class="text-lg text-primary">日期：{{ $data['date']}}</p>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm" width="100%" cellspacing="0">
                        <tbody>
                            @foreach ($data as $key => $val)
                            <tr>
                                <th scpoe="row" class="col-3">{{ $key }}</th>
                                <td class="col-9">
                                    @if (is_string($val) && strpos($val, 'https://') === 0)
                                        <a href="{{ $val }}">{{ $val }}</a>
                                    @elseif (is_array($val))
                                        {{ json_encode($val, JSON_UNESCAPED_UNICODE) }}
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
</div>
<div id="sectionMeetNode" class="card shadow mb-4">
    <a href="#collapseCardSectionMeetNote" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardSectionMeetNote">
        <h6 class="m-0 font-weight-bold text-primary">
            議事錄
        </h6>
    </a>
    <div class="collapse show" id="collapseCardSectionMeetNote">
        <div class="card-body">
            @if (is_null($section_meet_note))
                <p>無資料</p>
            @else
                <p class="text-right">
                    委員會議事錄是從<a href="{{ $section_meet_note['ppg_url'] }}">會議紀錄</a>抓取，院會議事錄是從公報抓取
                </p>
                <div class="table-responsive">
                    <table class="table table-sm" width="100%" cellspacing="0">
                        <tbody>
                            @foreach ($section_meet_note as $key => $val)
                            <tr>
                                <th scpoe="row" class="col-3">{{ $key }}</th>
                                <td class="col-9">
                                    @if (is_string($val) && strpos($val, 'https://') === 0)
                                        <a href="{{ $val }}">{{ $val }}</a>
                                    @elseif (is_array($val))
                                        {{ json_encode($val, JSON_UNESCAPED_UNICODE) }}
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
            @endif
        </div>
    </div>
</div>
<div id="speeches" class="card shadow mb-4">
    <a href="#collapseCardSpeeches" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardSpeeches">
        <h6 class="m-0 font-weight-bold text-primary">
            Open Data 發言紀錄
        </h6>
    </a>
    <div class="collapse show" id="collapseCardSpeeches">
        <div class="card-body">
            @if (is_null($speeches))
                <p>無資料</p>
            @else
                <?php $idxs = array_keys($speeches); ?>
                @foreach ($speeches as $idx => $data)
                    @if ($idx == reset($idxs))
                    <div class="row">
                        <div class="col">
                            <p id="speeches-{{ $data['smeetingDate'] }}" class="text-lg text-primary">
                                日期：{{ $data['smeetingDate']}}
                            </p>
                        </div>
                        <div class="col">
                            <p class="text-right">
                                以下資料來自立法院資料開放平台
                                <a href="https://data.ly.gov.tw/getds.action?id=221">
                                    院會發言名單
                                </a> 和
                                <a href="https://data.ly.gov.tw/getds.action?id=223">
                                    委員會登記發言名單
                                </a>
                            </p>
                        </div>
                    </div>
                    @else
                        <p id="speeches-{{ $data['smeetingDate'] }}" class="text-lg text-primary">
                            日期：{{ $data['smeetingDate']}}
                        </p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm" width="100%" cellspacing="0">
                            <tbody>
                                @foreach ($data as $key => $val)
                                <tr>
                                    <th scpoe="row" class="col-3">{{ $key }}</th>
                                    <td class="col-9">
                                        @if (is_string($val) && strpos($val, 'https://') === 0)
                                            <a href="{{ $val }}">{{ $val }}</a>
                                        @elseif (is_array($val))
                                            {{ json_encode($val, JSON_UNESCAPED_UNICODE) }}
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
                    @if ($idx != end($idxs))
                        <br>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
@section('body-load')
@endsection
