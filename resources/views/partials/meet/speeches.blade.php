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
