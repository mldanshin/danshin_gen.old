<?php
/**
 * @var int $personId
 */
?>
<a class="button"
    id="person-download-button"
    href="{{ route('download.person', [$personId, 'pdf']) }}"
    title="{{ __('download.person.tooltip') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/person/download-pdf.svg') }}" alt="download-pdf">
</a>