<?php
/**
 * @var App\Models\Eloquent\User $user
 */
?>
@include("partials.events.subscription.control", ["user" => $user])
<div>
    {{ __("events.subscription.edit.info") }}
</div>