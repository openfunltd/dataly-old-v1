<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">關聯提案</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="nosort">選擇</th>
                    <th class="nosort">法案版本</th>
                    <th class="nosort">版本名稱</th>
                    <th class="nosort">主提案非第一人</th>
                    <th class="nosort">提案編號</th>
                    <th class="nosort">提案日期</th>
                </thead>
                <tbody>
                    @foreach ($related_bills as $related_bill)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" value="{{ $related_bill['bill_idx'] }}">
                            </td>
                            <td>{{ $related_bill['bill_name'] }}</td>
                            <td>{{ $related_bill['version_name'] }}</td>
                            <td>{{ $related_bill['non_first_proposers'] }}</td>
                            <td>{{ $related_bill['bill_no'] }}</td>
                            <td>{{ $related_bill['initial_date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
