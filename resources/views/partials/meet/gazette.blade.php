<div id="gazette" class="card shadow mb-4">
    <a href="#collapseCardGazette" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardMeetData">
        <h6 class="m-0 font-weight-bold text-primary">
            公報發言紀錄
        </h6>
    </a>
    <div class="collapse show" id="collapseCardGazette">
        <div class="card-body">
            @if (is_null($gazettes))
                <p>無資料</p>
            @else
                <?php $keys = array_keys($gazettes); ?>
                @foreach ($gazettes as $idx => $gazette)
                    @if ($idx == reset($keys))
                    <div class="row">
                        <div class="col">
                            <p id="gazette-{{ $idx }}" class="text-lg text-primary">{{ $gazette['content'] }}</p>
                        </div>
                        <div class="col">
                            <p class="text-right">
                                以下資料是從公報的「本期發言目錄」中利用文字處理抓取
                            </p>
                        </div>
                    </div>
                    @else
                        <p id="gazette-{{ $idx }}" class="text-lg text-primary">{{ $gazette['content'] }}</p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm" width="100%" cellspacing="0">
                            <tbody>
                                @foreach ($gazette as $key => $val)
                                <tr>
                                    <th scope="row" class="col-3">{{ $key }}</th>
                                    <td class="col-9">
                                        @if (is_string($val) && strpos($val, 'https://') === 0)
                                            <a href="{{ $val }}">{{ $val }}</a>
                                        @elseif (in_array($key, ['html_files', 'txt_files']))
                                            <?php $url_keys = array_keys($val); ?>
                                            @foreach ($val as $url_idx => $url)
                                                <a href="{{ $url }}">{{ $url }}</a>
                                                @if ($idx != end($url_keys))
                                                    <br>
                                                @endif
                                            @endforeach
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
                                <tr>
                                    <th scope="row" class="col-3">lysayit</th>
                                    <td>
                                        <?php $doc_keys = array_keys($gazette['agenda_lcidc_ids']); ?>
                                        @foreach ($gazette['agenda_lcidc_ids'] as $doc_idx => $doc_id)
                                            <a href="https://lysayit.openfun.app/?doc_id={{ $doc_id }}">{{ $doc_id }}</a>
                                            @if ($doc_idx != end($doc_keys))
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                            </tbody>
                        </table>
                    </div>
                    @if ($idx != end($keys))
                        <br>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
