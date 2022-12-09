<?php
/**
 * @var App\Models\Events\Events $events
 * @var App\Models\Eloquent\User $user
 */
?>
@include("partials.events.subscription.control")
<x-events :model="$events" />