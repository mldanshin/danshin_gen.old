<div tabindex="0">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-responsive-nav-link class="button" :href="route('logout')"
                onclick="event.preventDefault();
                            this.closest('form').submit();">
            <img class="icon-lg" src="{{ asset('img/auth/logout.svg') }}" alt="{{ __('auth.log_out') }}" title="{{ __('auth.log_out') }}">
        </x-responsive-nav-link>
    </form>
</div>