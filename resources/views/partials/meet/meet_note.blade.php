<div id="meetNode" class="card shadow mb-4">
    <a href="#collapseCardMeetNote" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardMeetNote">
        <h6 class="m-0 font-weight-bold text-primary">
            議事錄
        </h6>
    </a>
    <div class="collapse show" id="collapseCardMeetNote">
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
