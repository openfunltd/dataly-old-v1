<div id="{{ $law_idx }}" class="diff-comparison {{ $law_idx }} card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ $law_idx }}</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-sm nowrap">
            <thead>
                <th style="width: 20%">版本名稱</th>
                <th>條文內容</th>
            </thead>
            <tbody>
                <tr>
                    <td>現行條文</td>
                    <td>
                        @if (is_null($diff->current))
                            本條新增無現行版本
                        @else
                            {!! $diff->current !!}
                        @endif
                    </td>
                </tr>
                @foreach ($related_bills as $bill_idx => $bill)
                    <tr class="diff {{$bill_idx}}">
                        <td>{{ $bill['version_name'] }}</td>
                        <td>
                            @if (property_exists($diff->commits, $bill_idx))
                                {!! $diff->commits->{$bill_idx} !!}
                            @else
                                {{ '無' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
