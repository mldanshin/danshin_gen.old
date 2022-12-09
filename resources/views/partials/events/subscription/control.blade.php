<?php
/**
 * @var App\Models\Eloquent\User $user
 */
?>
<div id="subscription-control-container">
    @if ($user->isSubscription())
        <form id="events-subscription-delete-form" method="post" action="{{ route("partials.events.subscription.delete") }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input class="button icon-lg"
                src="{{ asset('img/events/unsubscription.svg') }}"
                alt="unsubscription"
                tabindex="0"
                title="{{ __('events.subscription.crud.delete.tooltip') }}"
                type="image">
        </form>
    @else
        <button class="button"
            id="events-subscription-create-button"
            tabindex="0"
            title="{{ __('events.subscription.crud.create.tooltip') }}"
            data-href="{{ route('events.subscription.create') }}"
            data-href-part="{{ route('partials.events.subscription.create') }}"
            >
            <img class="icon-lg" src="{{ asset('img/events/subscription.svg') }}" alt="subscription">
        </button>
    @endif
</div>