<?php
/**
 * @var App\Models\Person\Editable\Form\Parent $item
 */
$id = Str::uuid();
?>
<div class="content-container">
    <div>    
        <div>{{ __("person.parents.role.label") }}</div>
        <select name="person_parents[{{ $id }}][role]" required>
            @foreach ($item->getRoleOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $item->getRole()) selected @endif >
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>
        <div>{{ __("person.parents.person.label") }}</div>
        <select name="person_parents[{{ $id }}][person]" required>
            @foreach ($item->getPersonOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $item->getPerson()) selected @endif >
                <span>{{ PersonHelper::surname($option->getSurname()) }}</span>
                @if ($option->getOldSurname() !== null)
                    <span>{{ PersonHelper::oldSurname($option->getOldSurname()) }}</span>
                @endif
                <span>{{ PersonHelper::name($option->getName()) }}</span>
                <span>{{ PersonHelper::patronymic($option->getPatronymic()) }}</span>
            </option>
            @endforeach
        </select>
    </div>
    @include("partials.person.partials.editor.button-delete-item")
</div>