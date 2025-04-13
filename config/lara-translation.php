<?php

return [

    // Directories to scan for translation keys
    // These are typically where your Blade templates or other view files live
    'directories' => [
        app_path('View'), // e.g., app/View - custom view components or classes
        resource_path('views'),// e.g., resources/views - standard Laravel Blade views
    ],



    // Translation function patterns to search for in your files
    // These are the functions/methods Laravel uses to fetch translation strings
    'patterns' => [
        'trans',  // e.g., trans('key')
        '__',     // e.g., __('key')
        '@lang',  // e.g., @lang('key') in Blade templates
    ],



    // Output directory for the generated translation files
    // JSON files will be saved here, one per locale (e.g., en.json, fr.json)
    'output' => lang_path(),

];
