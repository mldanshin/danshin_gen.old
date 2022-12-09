<?php
/*
* @var string $code
*/
?>
<style>
    .genealogy-mail-container {
        display: flex;
        justify-content: center;
    }

    .genealogy-mail-content {
    width: max-content;
    padding: 2rem;
    background-color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    .genealogy-mail-code {
        font-size: 1.2rem;
        font-weight: bold;
    }
</style>
<div class="genealogy-mail-container">
    <div class="genealogy-mail-content">
        <div>{{ __("auth.confirm.code")  }}</div>
        <div class="genealogy-mail-code">{{ $code }}</div>
    </div>
</div>