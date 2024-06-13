<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">條文索引</h6>
    </div>
    <div class="card-body">
        @foreach ($diff_result as $law_idx => $diff)
            <a class="law-idx {{ $law_idx }}" href="#{{ $law_idx }}">{{ $law_idx }}</a>
        @endforeach
    </div>
</div>
