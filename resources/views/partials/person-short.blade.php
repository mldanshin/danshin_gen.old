<?php
/**
 * @var App\Models\PersonShort $model;
 * @var string $classButtonShowPerson
 * @var string $classButtonShowTree
 */
?>
<div class="person-short">
    <div class="button {{ $classButtonShowPerson }}"
        data-href="{{ route('person.show', $model->getId()) }}"
        data-href-part="{{ route('partials.person.show', $model->getId()) }}"
        >
        <span>{{ PersonHelper::surname($model->getSurname()) }}</span>
        @empty (!$model->getOldSurname())
            <span>{{ PersonHelper::oldSurname($model->getOldSurname()) }}</span>
        @endempty
        <span>{{ PersonHelper::name($model->getName()) }}</span>
        <span>{{ PersonHelper::patronymic($model->getPatronymic()) }}</span>
    </div>
    <button class="button {{ $classButtonShowTree }}"
        type="button"
        title="{{ __('tree.button.tooltip') }}"
        data-person={{ $model->getId() }}
        data-href="{{ route('tree', $model->getId()) }}"
        data-href-part="{{ route('partials.tree.index') }}"
        >
        <img class="icon-sm" src="{{ asset('img/tree/tree.svg') }}" alt="tree">
    </button>
</div>