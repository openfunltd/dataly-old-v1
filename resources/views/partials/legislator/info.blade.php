<div class="row">
  <div class="col-xl-10 col-lg-9">
    <p id="term-{{ $legislator->term }}" class="text-lg text-primary">第 {{ $legislator->term }} 屆</p>
    <div class="table-responsive">
      <table class="table table-sm" width="100%" cellspacing="0">
        <tbody>
          <tr>
            <th scpoe="row" class="col-3">姓名</th>
            <td class="col-9">{{ $legislator->name }}</td>
          </tr>
          <tr>
            <th>英文姓名</th>
            <td>{{ $legislator->ename }}</td>
          </tr>
          <tr>
            <th>性別</th>
            <td>{{ $legislator->sex }}</td>
          </tr>
          <tr>
            <th>政黨</th>
            <td>{{ $legislator->party }}</td>
          </tr>
          <tr>
            <th>所屬黨團</th>
            <td>{{ $legislator->partyGroup }}</td>
          </tr>
          <tr>
            <th>選區</th>
            <td>{{ $legislator->areaName }}</td>
          </tr>
          <tr>
            <th>所屬委員會</th>
            <td>{!! join('<br>', array_reverse($legislator->committee)) !!}</td>
          </tr>
          <tr>
            <th>就任日期</th>
            <td>{{ $legislator->onboardDate }}</td>
          </tr>
          <tr>
            <th>學歷</th>
            <td>{!! join('<br>', $legislator->degree) !!}</td>
          </tr>
          <tr>
            <th>經歷</th>
            <td>{!! join('<br>', $legislator->experience) !!}</td>
          </tr>
          @if($legislator->leaveFlag == '是')
            <tr>
              <th>離職日期</th>
              <td>{{ $legislator->leaveDate }}</td>
            </tr>
            <tr>
              <th>就任日期</th>
              <td>{{ $legislator->leaveReason }}</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-xl-2 col-lg-3">
    <img class="img-fluid img-thumbnail" src="{{ $legislator->picUrl }}">
  </div>
</div>
