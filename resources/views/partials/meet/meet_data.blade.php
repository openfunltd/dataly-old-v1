<div id="meetData" class="card shadow mb-4">
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
                        <p id="meetdata-{{ $data['date'] }}" class="text-lg text-primary">
                            日期：{{ $data['date']}}
                        </p>
                    </div>
                    <div class="col">
                        <p class="text-right">
                            本區資料來自
                            <a href="https://data.ly.gov.tw/getds.action?id=42">
                                立法院資料開放平台
                            </a>
                        </p>
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
