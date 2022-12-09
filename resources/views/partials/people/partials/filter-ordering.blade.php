<?php
/**
 * @var App\Models\People\FilterOrdering $model
 */
?>
<input class="people-search"
    id="people-search"
    type="search"
    name="people_search"
    value="{{ $model->getSearch() }}"
    placeholder="{{ __('people.search') }}"
    autocomplete="off">
@if ($model->getOrdering()->count() > 0)
<div class="people-ordering-container">
    <div>{{ __("people.ordering") }}</div>
    <ul>
    @foreach ($model->getOrdering() as $item)
        <li class="people-ordering-item">
            <input id="people-order-{{ $item->getId() }}" 
                type="radio" 
                name="people_order" 
                value="{{ $item->getId() }}" 
                @if ($model->getOrderingCurrent() == $item->getId())
                    checked
                @endif>
            <label for="people-order-{{ $item->getId() }}">{{ $item->getName() }}</label>
        </li>
    @endforeach
    </ul>
</div>
@endif