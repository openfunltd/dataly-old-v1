@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('會議')</h1>
    </div>
    <!-- DataTales -->
    <div class="card shadow mb-4">
      <div class="card-header py-1">
        <span>屆期：</span>
        @foreach ($terms as $term)
        <a href="{{ route('meets', ['term' => $term])}}"
          class="btn {{ ($term == $parameters['term']) ? 'btn-danger' : 'btn-info'}} btn-sm"
        >
          <span class="text">{{ __('第 :term 屆', ['term' => $term])}}</span>
        </a>
        @endforeach
      </div>
      <div class="card-header py-1">
        <span>會期：</span>
        @foreach ($sessionPeriods as $sessionPeriod)
            <a href="{{ route('meets', [
                'term' => $parameters['term'],
                'sessionPeriod' => $sessionPeriod,
            ])}}"
            class="btn {{ ($sessionPeriod == $parameters['sessionPeriod']) ? 'btn-danger' : 'btn-info'}}
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
                <th class="dt-head-center">@lang('日期')</th>
                <th class="dt-head-center">@lang('會議 ID')</th>
                <th class="dt-head-center">@lang('會期')</th>
                <th class="dt-head-center">@lang('會議類型/委員會')</th>
                <th class="dt-head-center">@lang('會議名稱')</th>
                <th class="dt-head-center">@lang('會議頁面')</th>
                <th class="dt-head-center">@lang('議事錄')</th>
                <th class="dt-head-center">@lang('Open Data 發言紀錄')</th>
                <th class="dt-head-center">@lang('公報紀錄')</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($rows as $row)
                <tr>
                    {{-- 日期 --}}
                    <td>
                        @for ($i = 0; $i < count($row['dates']); $i++)
                            {{ $row['dates'][$i] }}
                            @if ($i < count($row['dates']) - 1)
                                <br>
                            @endif
                        @endfor
                    </td>
                    {{-- 會議 ID --}}
                    <td>
                        <?php $meet_url = route('meet', ['meet_id' => $row['meet_id']]); ?>
                        <a href="{{ $meet_url }}">
                            {{ $row['meet_id'] }}
                        </a>
                    </td>
                    {{-- 會期 --}}
                    <td>{{ $row['sessionPeriod'] }}</td>
                    {{-- 會議類型/委員會 --}}
                    <td>
                        <?php $keys = array_keys($row['type_or_committee']); ?>
                        @foreach($row['type_or_committee'] as $key => $item)
                            {{ $item }}
                            @if($key != end($keys))
                                <br>
                            @endif
                        @endforeach
                    </td>
                    {{-- 會議名稱 --}}
                    <td>{{ $row['name'] }}</td>
                    {{-- 會議頁面 --}}
                    <td>
                        @for ($i = 0; $i < count($row['dates']); $i++)
                            <a href="{{ $meet_url . '#meetdata-' . $row['dates'][$i] }}">
                                {{ $row['dates'][$i] }}
                            </a>
                            @if ($i < count($row['dates']) - 1)
                                <br>
                            @endif
                        @endfor
                    </td>
                    {{-- 議事錄 --}}
                    <td>
                        <a href="{{ $meet_url . '#sectionMeetNode' }}">{{ $row['proceedings'] }}</a>
                    </td>
                    {{-- Open Data 發言紀錄 --}}
                    <td>
                        <?php $keys = array_keys($row['speeches']); ?>
                        @foreach ($row['speeches'] as $key => $item)
                            <a href="{{ $meet_url . '#speeches-' . $item['date'] }}">
                                {{ $item['date'] }}：{{ $item['legislator_cnt'] }}人
                            </a>
                            @if ($key != end($keys))
                                <br>
                            @endif
                        @endforeach
                    </td>
                    {{-- 公報紀錄 --}}
                    <td>
                        @if (array_key_exists('gazette_records', $row))
                            @for ($i = 0; $i < $row['gazette_cnt']; $i++)
                                @if ($row['gazette_cnt'] > 3 && $i == 2)
                                    @break
                                @endif
                                <?php $item = $row['gazette_records'][$i]; ?>
                                {{ $item['gazette_id'] }}:{{ $item['page_start'] }}：{{ $item['speaker_cnt'] }}人
                                <br>
                            @endfor
                        @if ($row['gazette_cnt'] >= 4)
                        ...共{{ $row['gazette_cnt'] }}章<br>
                        @endif
                        公報出版日：{{ $row['gazette_publish_date'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>日期</th>
                <th>會議 ID</th>
                <th>會期</th>
                <th>會議類型/委員會</th>
                <th>會議名稱</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
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
    <script src="{{ asset('js/meets/datatables.js') }}"></script>
@endsection
