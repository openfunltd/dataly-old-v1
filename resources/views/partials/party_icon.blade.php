<?php $party_icon_url = App\Utils\LegislatorHelper::$party_icon_url; ?>
@if (array_key_exists($party, $party_icon_url))
    <img class="party-icon" src="{{ asset($party_icon_url[$party]) }}" title="{{ $party }}">
@endif
