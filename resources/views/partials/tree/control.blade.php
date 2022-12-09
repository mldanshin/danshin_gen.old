<?php
/**
 * @var App\Models\Tree\Toggle|null $toggle
 * @var int|string $personTargetId
 * @var int|string|null $personParentId
 */
?>
<div class="tree-control-container">
    <div class="tree-toggle-container">
        @isset ($toggle)
        <span>
            {{ __("tree.toggle.label") }}
        </span>
        <select class="tree-toggle" id="tree-toggle">
            @foreach ($toggle->getList() as $item)
                <option value="{{ $item->getId() }}"
                    @if ($item->getId() === $toggle->getActive()) {{ "selected" }} @endif
                    data-person={{ $personTargetId }}
                    data-parent={{ $item->getId() }}
                    data-href="{{ route("tree", [$personTargetId, $item->getId()]) }}"
                    data-href-part-index="{{ route("partials.tree.index") }}"
                    data-href-part-show="{{ route("partials.tree.show") }}"
                    data-href-download="{{ route("download.tree", [$personTargetId, $item->getId()]) }}"
                    data-href-show-image="{{ route("tree.image", [$personTargetId, $item->getId()]) }}"
                    >
                    {{ PersonHelper::surname($item->getSurname()) }}
                    {{ PersonHelper::name($item->getName()) }}
                    {{ PersonHelper::patronymic($item->getPatronymic()) }}
                </option>
            @endforeach
        </select>
        @endisset
    </div>
    <div>
        <button class="button"
            id="tree-control-help-button"
            type="button"
            title="{{ __('tree.control.help.button.tooltip') }}"
            >
            <img class="icon-lg" src="{{ asset('img/tree/help.svg') }}" alt="help">
        </button>
        <a class="button"
            id="tree-download-button"
            href="{{ route('download.tree', [$personTargetId, $personParentId]) }}"
            title="{{ __('tree.control.download.tooltip') }}"
            >
            <img class="icon-lg" src="{{ asset('img/tree/download-svg.svg') }}" alt="download-svg">
        </a>
        <a class="button"
            id="tree-show-image-button"
            href="{{ route('tree.image', [$personTargetId, $personParentId]) }}"
            title="{{ __('tree.control.show_image.tooltip') }}"
            target="_blank"
            >
            <img class="icon-lg" src="{{ asset('img/tree/show-image.svg') }}" alt="show-image">
        </a>
    </div>
</div>
<div class="tree-control-help hidden" id="tree-control-help">
    <div>
        {{ __("tree.control.help.ctrl_wheel.combination") }}
    </div>
    <div>
        {{ __("tree.control.help.ctrl_wheel.action") }}
    </div>
    <div>
        {{ __("tree.control.help.shift_mousemove.combination") }}
    </div>
    <div>
        {{ __("tree.control.help.shift_mousemove.action") }}
    </div>
    <div>
        {{ __("tree.control.help.dblclick.combination") }}
    </div>
    <div>
        {{ __("tree.control.help.dblclick.action") }}
    </div>
</div>