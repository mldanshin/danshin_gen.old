<?php
/**
 * @var string $userId
 * @var string $code
 */
?>
<div class="events-subscription-create" id="events-subscription-create">
    <h2>
        {{ __("events.subscription.create.title") }}
    </h2>
    <p>
        <b>{{ __("events.subscription.create.manual.what") }}</b>
    </p>
    <p>{{ __("events.subscription.create.manual.description") }}</p>
    <div>
        <p>
            <b>{{ __("events.subscription.create.manual.call_action") }}</b>
        </p>
        <ul>
            <li>
                <p>
                    <span>
                        {{ __("events.subscription.create.manual.step_1_1") }}
                    </span>
                    <a class="button"
                        id="events-subscription-link-confirm"
                        href="{{ config('services.telegram-bot-api.url') }}"
                        target="_blank"
                        >
                        <img class="icon-sm" src="{{ asset('img/events/telegram.svg') }}" alt="telegram">
                    </a>
                    <span>.</span>
                </p>
                <p>
                    {{ __("events.subscription.create.manual.step_1_2") }}
                </p>
                <p>
                    <span>
                        {{ __("events.subscription.create.manual.step_1_3") }}
                    </span>
                    <em id="events-subscription-botname">
                        {{ config('services.telegram-bot-api.name') }}
                    </em>
                    <input class="button icon-sm"
                        id="events-subscription-create-button-copy-botname"
                        type="image"
                        src="{{ asset('img/app/copy.svg') }}"
                        alt="copy"
                        title="{{ __("events.subscription.create.manual.button.copy.tooltip") }}">
                    <span>.</span>
                </p>
            </li>
            <li>
                <p>{{ __("events.subscription.create.manual.step_2") }}</p>
            </li>
            <li>
                <p>
                    <span>
                        {{ __("events.subscription.create.manual.step_3") }}
                    </span>
                    <em id="events-subscription-code">
                        {{ $code }}
                    </em>
                    <input class="button icon-sm"
                        id="events-subscription-create-button-copy-code"
                        type="image"
                        src="{{ asset('img/app/copy.svg') }}"
                        alt="copy"
                        title="{{ __("events.subscription.create.manual.button.copy.tooltip") }}">
                    <span>.</span>
                </p>
            </li>
            <li>
                <p>
                    <span>
                        {{ __("events.subscription.create.manual.step_4") }}
                    </span>
                    <input type="submit"
                            form="events-subscription-store-form"
                            value="{{ __("events.subscription.create.manual.button.register.label") }}"
                            title="{{ __("events.subscription.create.manual.button.register.tooltip") }}">
                    <span>.</span>
                </p>
            </li>
        </ul>
    </div>
</div>
<form id="events-subscription-store-form" method="post" action="{{ route("partials.events.subscription.store") }}">
    <input type="hidden" name="user_id" value="{{ $userId }}">
    <input type="hidden" name="code" value="{{ $code }}">
</form>