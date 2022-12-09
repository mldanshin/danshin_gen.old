<button class="button"
    id="events-button"
    type="button"
    title="{{ __('events.button.title') }}"
    data-href="{{ route("events.show") }}"
    data-href-part="{{ route("partials.events.show") }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/events/show.svg') }}" alt="events">
</button>