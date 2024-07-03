@extends('layouts.sbAdmin2')
@section('head-load')
    <link href="{{ asset('css/party_icon.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">立委基本資料</h1>
      <ul class="nav nav-pills">
        @foreach($legislators as $legislator)
          <li class="nav-item">
            <a href="#term-{{ $legislator->term }}" class="nav-link">
              <span class="text">第 {{ $legislator->term }} 屆</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3 text-primary">
        <h5 class="m-0 font-weight-bold text-primary">{{ $legislators[0]->name }}</h5>
      </div>
      <div class="card-body">
        <?php $keys = array_keys($legislators); ?>
        @foreach($legislators as $idx => $legislator)
          @include('partials.legislator.info', ['legislator' => $legislator])
          @if ($idx != end($keys))
            <br>
          @endif
        @endforeach
      </div>
    </div>
@endsection
@section('body-load')
@endsection
