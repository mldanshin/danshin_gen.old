<?php
/**
 * @var int $personId
 */
?>
<button class="button"
    id="person-tree-button"
    type="button"
    data-person={{ $personId }}
    data-href="{{ route('tree', $personId) }}"
    data-href-part="{{ route('partials.tree.index') }}"
    title="{{ __('tree.button.tooltip') }}"
    tabindex="0"
    >
    <img class="icon-lg" src="{{ asset('img/tree/tree.svg') }}" alt="tree">
</button>