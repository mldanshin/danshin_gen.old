<?php
/**
 * @var App\Models\Person\Editable\Form\Marriage $item
 */
$id = Str::uuid();
?>
<div class="content-container">
    <div>
        <div>{{ __("person.marriages.role_current.label") }}</div>
        <select name="person_marriages[{{ $id }}][role_current]" required>
            @foreach ($item->getRoleCurrentOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $item->getRoleCurrent()) selected @endif >
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>
        <div>{{ __("person.marriages.role_soulmate.label") }}</div>
        <select name="person_marriages[{{ $id }}][role_soulmate]" required>
            @foreach ($item->getRoleSoulmateOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $item->getRoleSoulmate()) selected @endif >
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>
        <div>{{ __("person.marriages.soulmate.label") }}</div>
        <select name="person_marriages[{{ $id }}][soulmate]" required>
            @foreach ($item->getSoulmateOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $item->getSoulmate()) selected @endif >
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