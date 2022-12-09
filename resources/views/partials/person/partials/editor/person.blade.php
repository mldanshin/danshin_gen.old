<?php
/**
 * @var App\Models\Person\Editable\Form\Person $model
 * @var string $formActionUrl
 * @var string $method
 */
?>
<form class="person-editor" id="person-edit-form" action="{{ $formActionUrl }}" enctype="multipart/form-data">
    <input id="person-method" type="hidden" name="_method" value="{{ $method }}">
    <input id="person-id" type="hidden" name="person_id" value="{{ $model->getId() }}">
    <div class="person-card-editor">
        <label for="person-unavailable">{{ __("person.unavailable.content") }}</label>
        <input id="person-unavailable" type="checkbox" name="person_unavailable" @empty(!$model->isUnavailable()) checked @endempty>

        <label for="person-live">{{ __("person.live.label") }}</label>
        <input id="person-live" type="checkbox" name="person_live" @if($model->isLive()) checked @endif>

        <div>{{ __("person.gender.label") }}</div>
        <select id="person-gender" name="person_gender">
            @foreach ($model->getGender()->getOptions() as $option)
            <option value="{{ $option->getId() }}" @if ($option->getId() === $model->getGender()->getType()) selected @endif>
                {{ $option->getName() }}
            </option>
            @endforeach
        </select>

        <div>{{ __("person.surname.label") }}</div>
        <input id="person-surname" type="text" name="person_surname" value="{{ $model->getSurname() }}">

        <div>{{ __("person.old_surname.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getOldSurname(),
            "viewName" => "partials.person.partials.editor.old-surname"
        ])

        <div>{{ __("person.name.label") }}</div>
        <input type="text" name="person_name" value="{{ $model->getName() }}">

        <div>{{ __("person.patronymic.label") }}</div>
        <div>
            <input type="text" name="person_patronymic" value="{{ PersonHelper::patronymicEdit($model->getPatronymic()) }}">
            <small class="help-rule">{{ __("person.patronymic.rule") }}</small>
        </div>

        <div>{{ __("person.birth_date.label") }}</div>
        @include("partials.person.partials.editor.date", ["name" => "person_birth_date", "date" => $model->getBirthDate()])

        <div>{{ __("person.birth_place.label") }}</div>
        <textarea name="person_birth_place">{{ $model->getBirthPlace() }}</textarea>

        <div>{{ __("person.death_date.label") }}</div>
        @include("partials.person.partials.editor.date", ["name" => "person_death_date", "date" => $model->getDeathDate()])

        <div>{{ __("person.burial_place.label") }}</div>
        <textarea name="person_burial_place">{{ $model->getBurialPlace() }}</textarea>

        <div>{{ __("person.note.label") }}</div>
        <textarea name="person_note">{{ $model->getNote() }}</textarea>

        <div>{{ __("person.activities.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getActivities(),
            "viewName" => "partials.person.partials.editor.activity"
        ])

        <div>{{ __("person.emails.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getEmails(),
            "viewName" => "partials.person.partials.editor.email"
        ])

        <div>{{ __("person.internet.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getInternet(),
            "viewName" => "partials.person.partials.editor.internet"
        ])

        <div>{{ __("person.phones.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getPhones(),
            "viewName" => "partials.person.partials.editor.phone"
        ])

        <div>{{ __("person.residences.label") }}</div>
        @include("partials.person.partials.editor.list-input", [
            "iterable" => $model->getResidences(),
            "viewName" => "partials.person.partials.editor.residence"
        ])

        <div>{{ __("person.parents.label") }}</div>
        @include("partials.person.partials.editor.parents", ["parents" => $model->getParents()])

        <div>{{ __("person.marriages.label") }}</div>
        @include("partials.person.partials.editor.marriages", ["marriages" => $model->getMarriages()])
    </div>
    <div class="person-photo">
        <div>{{ __("person.photo.label") }}</div>
        @include("partials.person.partials.editor.photo-collection", ["photo" => $model->getPhoto()])
    </div>
</form>