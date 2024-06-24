<div id="ivod-gazette" class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">公報發言紀錄</h6>
    </div>
    <div class="card-body">
        {{-- 發言片段 --}}
        <div class="row">
            <div class="col">
                <p class="text-lg text-primary">發言片段</p>
            </div>
            <div class="col">
                <p class="text-right">
                    lineno: {{ $gazette->lineno }}
                </p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm" width="100%" cellspacing="0">
                <tbody>
                    @foreach($gazette->blocks as $block_idx => $block)
                        <tr>
                            <th>發言片段: {{ $block_idx }}</th>
                        </tr>
                        @foreach($block as $line)
                            <tr><td>{{ $line }}</td></tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- agenda --}}
        <p class="text-lg text-primary">公報詮釋資料</p>
        <div class="table-responsive">
            <table class="table table-sm" width="100%" cellspacing="0">
                <tbody>
                    @foreach ($gazette->agenda as $key => $val)
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
    </div>
</div>
