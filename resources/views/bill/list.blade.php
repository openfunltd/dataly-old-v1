@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bills/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('提案')</h1>
    </div>
    <!-- DataTales -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        @foreach ($terms as $term)
        <a href="{{ route('bills', ['term' => $term])}}"
          class="btn {{ ($term == $parameters['term']) ? 'btn-danger' : 'btn-info'}} btn-sm"
        >
          <span class="text">{{ __('第 :term 屆', ['term' => $term])}}</span>
        </a>
        @endforeach
      </div>
      <div class="card-body">
        <div class="table-responsive" style="overflow-x: auto;">
          <table class="table table-bordered table-hover table-sm nowrap" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="dt-head-center">@lang('快速連結')</th>
                <th class="dt-head-center">@lang('對照表')</th>
                <th class="dt-head-center">@lang('排入院會日期')</th>
                <th class="dt-head-center">@lang('提案編號')</th>
                <th class="dt-head-center">@lang('會期')</th>
                <th class="dt-head-center">@lang('提案第一人/提案單位')</th>
                <th class="dt-head-center">@lang('議案名稱')</th>
                <th class="dt-head-center">@lang('對應法律')</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($rows as $row)
            <tr>
              <td>
                @foreach ($row['links'] as $idx => $link)
                  @if ($link[1] === '')
                    {{ $link[0] }}
                  @else
                    <a href="{{ $link[1] }}" target="_blank">{{ $link[0] }}</a>
                  @endif
                  @if ($idx < count($row['links']) - 1)
                  |
                  @endif
                @endforeach
              </td>
              <td>{{ ($row['law_diff']) ? '✅' : '❌'}}</td>
              <td>{{ $row['initial_date'] }}</td>
              <td>{{ $row['bill_id'] }}</td>
              <td>{{ $row['sessionPeriod'] }}</td>
              <td>{{ $row['proposer'] }}</td>
              <td>{{ $row['bill_name'] }}</td>
              <td>
                @foreach ($row['law_names'] as $idx => $law_name)
                  {{ $law_name }}
                  @if ($idx < count($row['links']) - 1)
                    <br>
                  @endif
                @endforeach
              </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th>對照表</th>
                <th>排入院會日期</th>
                <th>提案編號</th>
                <th>會期</th>
                <th>提案第一人/提案單位</th>
                <th>議案名稱</th>
                <th>對應法律</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
@endsection
@section('body-load')
    <script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/bills/datatables.js') }}"></script>
@endsection
