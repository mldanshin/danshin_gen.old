<div id="events-container">
    @if ($isEmpty)
        {{ __("events.not_found") }}
    @else
        <h2>
            {{ __("events.title") }}
        </h2>
        @if ($past->count() > 0)
        <div>
            <h3>
                {{ __("events.past") }}
            </h3>
            <ul class="event-list">
                @foreach ($past as $item)
                <li>
                    <span>{{ $item->name }}</span>
                    <span>{{ $item->date }}</span>
                    <span class="button event__button-show-person text-link"
                        data-href="{{ route('person.show', $item->personId) }}"
                        data-href-part="{{ route('partials.person.show', $item->personId) }}"
                        >
                        {{ $item->person }}
                    </span>
                    <span>{{ $item->calculate }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if ($today->count() > 0)
        <div>
            <h3>
                {{ __("events.today") }}
            </h3>
            <ul class="event-list">
                @foreach ($today as $item)
                <li>
                    <span>{{ $item->name }}</span>
                    <span>{{ $item->date }}</span>
                    <span class="button event__button-show-person text-link"
                        data-href="{{ route('person.show', $item->personId) }}"
                        data-href-part="{{ route('partials.person.show', $item->personId) }}"
                        >
                        {{ $item->person }}
                    </span>
                    <span>{{ $item->calculate }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if ($nearest->count() > 0)
        <div>
            <h3>
                {{ __("events.nearest") }}
            </h3>
            <ul class="event-list">
                @foreach ($nearest as $item)
                <li>
                    <span>{{ $item->name }}</span>
                    <span>{{ $item->date }}</span>
                    <span class="button event__button-show-person text-link"
                        data-href="{{ route('person.show', $item->personId) }}"
                        data-href-part="{{ route('partials.person.show', $item->personId) }}"
                        >
                        {{ $item->person }}
                    </span>
                    <span>{{ $item->calculate }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    @endif
</div>