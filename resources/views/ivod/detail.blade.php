@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/vendor/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/meets/datatables.css') }}" rel="stylesheet">
<style>
div.top-div {
    margin-top: 20px;
}

#subtitleTable td {
  border: 1px solid black;
}

.dataTable th {
  border: 1px solid black;
}

tr[id^=s-] {
    cursor: pointer;
}
</style>
@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-1">
            <h1 class="h3 mb-0 text-gray-800">
                {{ $ivod->委員名稱 ?? '完整' }} @
                {{ property_exists($ivod, 'meet') ? $ivod->meet->title : $ivod->{'會議名稱'} }}
            </h1>
        </div>
        <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div>
                    時間:
                    <span id="text">0</span>
                </div>
                <div>
                    <table id="subtitleTable" class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Text</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ivod->transcript->whisperx->result->segments ?? [] as $idx => $segment)
                            <tr id="s-{{ $idx }}">
                                <td>{{ sprintf("%02d:%02d:%02d,%03d", $segment->start / 3600, $segment->start / 60 % 60, $segment->start % 60, (1000 * $segment->start) % 1000) }}</td>
                                <td>{{ sprintf("%02d:%02d:%02d,%03d", $segment->end / 3600, $segment->end / 60 % 60, $segment->end % 60, (1000 * $segment->end) % 1000) }}</td>
                                <td>{{ $segment->text }}</td>
                            </tr>
							@endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
				<video id="video" controls width="100%"></video>
            </div>
        </div>
    </div>
    <!-- DataTales -->
@endsection
@section('body-load')
    <script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js')}}"></script>
    <script src="{{ asset('js/vendor/hls.js') }}"></script>
    <script>
        var subtitles = @json($ivod->transcript->whisperx->result->segments);
    </script>
    <script src="{{ asset('js/ivods/datatables.js') }}"></script>
    <script>
    if(Hls.isSupported()) {
        var video = document.getElementById('video');
        var hls = new Hls();
        hls.loadSource(@json($ivod->video_url));
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED,function() {
            video.play();
        });
    }
    </script>
@endsection
