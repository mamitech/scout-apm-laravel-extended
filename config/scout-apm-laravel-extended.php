<?php

// config for mamitech/scout-apm-laravel-extended
return [
    'sampling_per' =>(int) env('SCOUT_SAMPLING_PER', 10),
    'custom_context_enabled' => env('SCOUT_CUSTOM_CONTEXT_ENABLED', true),
];
