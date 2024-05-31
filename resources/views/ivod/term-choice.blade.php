<?php
$sessionPeriods = [];
foreach ($ivod_stat->terms as $i_term) {
    if ($i_term->term == $term) {
        $sessionPeriods = $i_term->sessionPeriod_count;
        break;
    }
}
?>
<div class="card shadow mb-4">
  <div class="card-header py-1">
    <span>屆期：</span>
    @foreach ($ivod_stat->terms as $i_term)
    <a href="{{ route('ivods.datelist.term', ['term' => $i_term->term, 'sessionPeriod' => $i_term->sessionPeriod_count[0]->sessionPeriod])}}"
        class="btn {{ ($term == $i_term->term) ? 'btn-danger' : 'btn-info'}} btn-sm"
    >
    <span class="text">{{ __('第 :term 屆', ['term' => $i_term->term])}}</span>
    </a>
    @endforeach
  </div>
  <div class="card-header py-1">
    <span>會期：</span>
    @foreach ($sessionPeriods as $t_sessionPeriod)
        <a href="{{ route('ivods.datelist.term', [
            'term' => $term,
            'sessionPeriod' => $t_sessionPeriod->sessionPeriod,
        ])}}"
        class="btn {{ ($t_sessionPeriod->sessionPeriod == $sessionPeriod) ? 'btn-danger' : 'btn-info'}}
            btn-sm">
            <span class="text">{{ __('第 :sp 會期', ['sp' => $t_sessionPeriod->sessionPeriod])}}</span>
        </a>
    @endforeach
  </div>
</div>
