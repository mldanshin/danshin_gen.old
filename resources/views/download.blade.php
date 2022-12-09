<a class="button"
    href="{{ route('download.people', ['pdf']) }}"
    title="{{ __('download.people.tooltip') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/download/people-pdf.svg') }}" alt="download people pdf">
</a>
<a class="button"
    href="{{ route('download.data_base') }}"
    title="{{ __('download.data_base.tooltip') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/download/database.svg') }}" alt="download database">
</a>
<a class="button"
    href="{{ route('download.photo') }}"
    title="{{ __('download.photo.tooltip') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/download/photo.svg') }}" alt="download photo">
</a>