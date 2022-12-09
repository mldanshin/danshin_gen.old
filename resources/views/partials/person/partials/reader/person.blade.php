<?php
/**
 * @var App\Models\Person\Readable\Person $model
 */
?>
<div class="person-reader" 
    id="person"
    data-href="{{ route('person.show', $model->getId()) }}"
    data-href-part="{{ route('partials.person.show', $model->getId()) }}"
    >
    <div class="person-card-reader">
        @if ($model->isUnavailable())
            <div>{{ __("person.unavailable.label") }}</div>
            <div>{{ __("person.unavailable.content") }}</div>
        @else
            <div>{{ __("person.live.label") }}</div>
            @if ($model->isLive())
                <div>{{ __("person.live.yes") }}</div>
            @else
                <div>{{ __("person.live.no") }}</div>
            @endif
        @endif

        <div>{{ __("person.gender.label") }}</div>
        <div>{{ PersonHelper::gender($model->getGenderId()) }}</div>

        <div>{{ __("person.surname.label") }}</div>
        <div>{{ PersonHelper::surname($model->getSurname()) }}</div>

        @empty (!$model->getOldSurname())
        <div>{{ __("person.old_surname.label") }}</div>
        <div>{{ PersonHelper::oldSurname($model->getOldSurname()) }}</div>
        @endempty

        <div>{{ __("person.name.label") }}</div>
        <div>{{ PersonHelper::name($model->getName()) }}</div>

        <div>{{ __("person.patronymic.label") }}</div>
        <div>{{ PersonHelper::patronymic($model->getPatronymic()) }}</div>

        <div>{{ __("person.birth_date.label") }}</div>
        <div>{{ DateHelper::getBirth($model->getBirthDate(), $model->getAge(), $model->isLive()) }}</div>

        <div>{{ __("person.birth_place.label") }}</div>
        <div>{{ $model->getBirthPlace() }}</div>

        @if (!$model->isLive() || $model->isUnavailable())
        <div>{{ __("person.death_date.label") }}</div>
        <div>{{ DateHelper::getDeath($model->getDeathDate(), $model->getAge(), $model->getDeathDateInterval()) }}</div>
        <div>{{ __("person.burial_place.label") }}</div>
        <div>{{ $model->getBurialPlace() }}</div>
        @endif

        @empty (!$model->getNote())
        <div>{{ __("person.note.label") }}</div>
        <div class="person-card-reader__cell-list">{{ $model->getNote() }}</div>
        @endempty

        @empty (!$model->getActivities())
        <div>{{ __("person.activities.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getActivities() as $item)
                <div>{{ $item }}</div>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getEmails())
        <div>{{ __("person.emails.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getEmails() as $item)
                <div>{{ $item }}</div>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getInternet())
        <div>{{ __("person.internet.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getInternet() as $item)
                <a href="{{ $item->getUrl() }}" title="{{ $item->getUrl() }}" target="_blank" rel="noopener noreferrer">
                    {{ $item->getName() }}
                </a>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getPhones())
        <div>{{ __("person.phones.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getPhones() as $item)
                <div>{{ $item }}</div>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getResidences())
        <div>{{ __("person.residences.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getResidences() as $item)
                <div>
                    <span>{{ $item->getName() }}</span>
                    @empty(!$item->getDate())
                    <span>
                        {{ __("person.residences.date.content", [
                            "date" => DateHelper::format($item->getDate())
                        ]) }}
                    </span>
                    @endempty
                </div>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getParents())
        <div>{{ __("person.parents.label") }}</div>
        <div class="person-card-reader__cell-list">
        @foreach ($model->getParents() as $parent)
            <div class="person-card-reader__cell-relation-role ">
                <div>
                    {{ PersonHelper::parent($parent->getRole()) }}
                </div>
                <div>
                    @include("partials.person-short", [
                        "model" => $parent->getPerson(),
                        "classButtonShowPerson" => "person__button-show-person text-link",
                        "classButtonShowTree" => "person__button-show-tree"
                    ])
                </div>
            </div>
        @endforeach
        </div>
        @endempty

        @empty (!$model->getMarriages())
        <div>{{ __("person.marriages.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getMarriages() as $person)
            <div class="person-card-reader__cell-relation-role ">
                <div>
                    {{ PersonHelper::marriage($person->getRole()) }}
                </div>
                <div>
                    @include("partials.person-short", [
                        "model" => $person->getSoulmate(),
                        "classButtonShowPerson" => "person__button-show-person text-link",
                        "classButtonShowTree" => "person__button-show-tree"
                        ])
                </div>
            </div>
            @endforeach
        </div>
        @endempty

        @empty (!$model->getChildren())
        <div>{{ __("person.children.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getChildren() as $person)
                <div>
                    @include("partials.person-short", [
                        "model" => $person,
                        "classButtonShowPerson" => "person__button-show-person text-link",
                        "classButtonShowTree" => "person__button-show-tree"
                    ])
                </div>
            @endforeach
        </div>
        @endempty
        
        @empty (!$model->getBrothersSisters())
        <div>{{ __("person.brothers_sisters.label") }}</div>
        <div class="person-card-reader__cell-list">
            @foreach ($model->getBrothersSisters() as $person)
                <div>
                    @include("partials.person-short", [
                        "model" => $person,
                        "classButtonShowPerson" => "person__button-show-person text-link",
                        "classButtonShowTree" => "person__button-show-tree"
                    ])
                </div>
            @endforeach
        </div>
        @endempty
    </div>
    <div class="person-photo">
        @if ($model->getPhoto() !== null)
            @foreach ($model->getPhoto() as $item)
                <figure>
                    <img class="photo-img"
                        src="{{ $item->getUrl() }}"
                        alt="{{ __('person.photo.label') }} {{ $model->getSurname().' '.$model->getName().' '.$model->getPatronymic() }}">
                    @empty (!$item->getDate())
                    <figcaption>
                        {{ DateHelper::format($item->getDate()) }}
                    </figcaption>
                    @endempty
                </figure>
            @endforeach
        @else
            {{ __("person.photo.missing") }}
        @endif
    <div>
</div>