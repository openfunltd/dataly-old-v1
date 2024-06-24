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
    @if(isset($ivod->transcript))
        @include('partials.ivod.ai-transcript')
    @endif
    @if(isset($ivod->gazette))
        @include('partials.ivod.gazette', ['gazette' => $ivod->gazette])
    @endif
@endsection
@section('body-load')
    @if(isset($ivod->transcript))
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
    @endif
@endsection
