<?php

return [
    // The prefix that's used for all `double` API routes
    // You can override this in case you already have routes starting with `double`
    'api_prefix' => 'api/double',

    // The root of your vuejs frontend. Files in this folder have to be executable by php
    // By default this is next to your Laravel's `src` folder and called `double`
    'frontend_root' => base_path('double'),
];
