<div id="ivods" class="card shadow mb-4">
    <a href="#collapseCardIvods" class="d-block card-header py-3" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardIvods">
        <h6 class="m-0 font-weight-bold text-primary">
            iVOD 記錄
        </h6>
    </a>
    <div class="collapse show" id="collapseCardIvods">
        <div class="card-body">
            @if (isset($ivods) && ! empty($ivods))
                <p class="text-right">以下資料是從<a href="https://ivod.ly.gov.tw/">立法院iVOD</a>抓取</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <td class="text-center">ID</td>
                                <td class="text-center">日期</td>
                                <td class="text-center">委員名稱</td>
                                <td class="text-center">委員發言時間</td>
                                <td class="text-center">影片長度</td>
                                <td class="text-center">連結</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ivods as $ivod)
                                <tr>
                                    <td class="text-center">{{ $ivod['id'] }}</td>
                                    <td class="text-center">{{ substr($ivod['會議時間'], 0, 10) }}</td>
                                    <td class="text-center">{{ $ivod['委員名稱']}}</td>
                                    <td class="text-center">{{ $ivod['委員發言時間']}}</td>
                                    <td class="text-center">{{ $ivod['影片長度']}}</td>
                                    <td class="text-center">
                                        <a href="{{ $ivod['url'] }}">Link</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>無資料</p>
            @endif
        </div>
    </div>
</div>
