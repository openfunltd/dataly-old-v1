@extends('layouts.sbAdmin2')
@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">@lang('儀表板 Work In Progress')</h1>
</div>

<!-- Content Row -->

<div class="row">
    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">第 11 屆立法委員政黨分佈</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="party-pie-chart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #1B9431;"></i> 民主進步黨（51）
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #000095;"></i> 中國國民黨（52）
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #28C8C8;"></i> 台灣民眾黨（8）
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #999999;"></i> 無黨籍（2）
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('body-load')
    <script src="{{ asset('js/vendor/Chart.min.js') }}"></script>
    <script>
      const partyData = @json($partyData);
    </script>
    <script src="{{ asset('js/dashboard/chart-pie-party.js') }}"></script>
@endsection
