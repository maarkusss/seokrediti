<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable HTML Minification
    |--------------------------------------------------------------------------
    */
    'enable' => env('LARAVEL_MINIFY', true),

    /*
    |--------------------------------------------------------------------------
    | Skip Routes
    |--------------------------------------------------------------------------
    |
    | Skip Routes paths to exclude.
    | You can use * as wildcard.
    |
    */
    'skip' => [
        '*.xml',
        '*.less',
        '*.pdf',
        '*.doc',
        '*.txt',
        '*.ico',
        '*.zip',
        '*.mp3',
        '*.rar',
        '*.exe',
        '*.wmv',
        '*.avi',
        '*.ppt',
        '*.mpg',
        '*.mpeg',
        '*.tif',
        '*.wav',
        '*.mov',
        '*.psd',
        '*.ai',
        '*.xls',
        '*.mp4',
        '*.m4a',
        '*.iso',
        '*.m4v',
        'admincp*',
        '*/admincp*',
        'go/*',
        '*/go/*',
        'manifest.json',
        'robots.txt',
        'sitemap.xml',
        'llms.txt',
        'captcha*',
    ],
];
