@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/party_icon.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('立委')</h1>
    </div>
    <!-- DataTales -->
    <div class="card shadow mb-4">
      <div class="card-header py-1">
        <span>屆期：</span>
        @foreach ($terms as $term)
        <a href="{{ route('legislators', ['term' => $term])}}"
          class="btn {{ ($term == $params['term']) ? 'btn-danger' : 'btn-info'}} btn-sm"
        >
          <span class="text">{{ __('第 :term 屆', ['term' => $term])}}</span>
        </a>
        @endforeach
      </div>
      <div class="card-header py-1">
        <span>會期：</span>
        @foreach ($sessionPeriods as $sessionPeriod)
            <a href="{{ route('legislators', [
                'term' => $params['term'],
                'sessionPeriod' => $sessionPeriod,
            ])}}"
            class="btn {{ ($sessionPeriod == $params['sessionPeriod']) ? 'btn-danger' : 'btn-info'}}
                btn-sm">
                @if ($sessionPeriod == 'all')
                    <span class="text">{{ __('不篩選')}}</span>
                @else
                    <span class="text">{{ __('第 :sp 會期', ['sp' => $sessionPeriod])}}</span>
                @endif
            </a>
        @endforeach
      </div>
      <div class="card-body">
        <div class="table-responsive" style="overflow-x: auto;">
          <table class="table table-bordered table-hover table-sm nowrap" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                  <th class="dt-head-center">@lang('姓名')</th>
                  <th class="dt-head-center">@lang('政黨')</th>
                  <th class="dt-head-center">@lang('所屬黨團')</th>
                  <th class="dt-head-center">@lang('所屬委員會')</th>
                  <th class="dt-head-center">@lang('選區')</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($legislators as $legislator)
                    <tr>
                        {{-- 姓名 --}}
                        <td>
                            <a href="{{ route('legislator', ['bio_id' => $legislator['bioId']]) }}">{{ $legislator['name'] }}</a>
                            @include('partials.party_icon', ['party' => $legislator['party']])
                        </td>
                        {{-- 政黨 --}}
                        <td>{{ $legislator['party'] }}</td>
                        {{-- 所屬黨團 --}}
                        <td>{{ $legislator['partyGroup'] }}</td>
                        {{-- 所屬委員會 --}}
                        <td>
                            <?php $keys = array_keys($legislator['committee']); ?>
                            @foreach ($legislator['committee'] as $key => $comt)
                                @if ($params['term'] < 4 || $params['sessionPeriod'] == 'all')
                                    {{ $comt }}
                                @else
                                    {{ explode('：', $comt)[1] }}
                                @endif
                                @if ($key != end($keys))
                                    <br>
                                @endif
                            @endforeach
                        </td>
                        {{-- 選區 --}}
                        <td>{{ $legislator['areaName'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
              <tr>
                  <th>姓名</th>
                  <th>政黨</th>
                  <th>所屬黨團</th>
                  <th>所屬委員會</th>
                  <th>選區</th>
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
    <script src="{{ asset('js/legislators/datatables.js') }}"></script>
@endsection
