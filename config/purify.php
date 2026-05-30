<?php

use Stevebauman\Purify\Definitions\Html5Definition;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Config
    |--------------------------------------------------------------------------
    */

    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Config sets
    |--------------------------------------------------------------------------
    |
    | The "default" set is tuned for rendered blog Markdown: it permits the
    | block-level elements CommonMark/GFM produce (code blocks, tables, etc.)
    | and keeps the `class` attribute on <code> so highlight.js can colour it.
    |
    | Documentation: http://htmlpurifier.org/live/configdoc/plain.html
    */

    'configs' => [

        'default' => [
            'Core.Encoding' => 'utf-8',
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => implode(',', [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'p', 'br', 'hr',
                'b', 'u', 'strong', 'i', 'em', 's', 'del', 'ins', 'sup', 'sub', 'mark',
                'a[href|title|target|rel]',
                'ul', 'ol', 'li',
                'blockquote',
                'span[class]', 'img[width|height|alt|src|title]',
                'pre', 'code[class]',
                'table', 'thead', 'tbody', 'tr',
                'th[align|style|colspan|rowspan]', 'td[align|style|colspan|rowspan]',
            ]),
            'HTML.ForbiddenElements' => '',
            'CSS.AllowedProperties' => 'text-align',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | HTMLPurifier definitions
    |--------------------------------------------------------------------------
    */

    'definitions' => Html5Definition::class,

    /*
    |--------------------------------------------------------------------------
    | HTMLPurifier CSS definitions
    |--------------------------------------------------------------------------
    */

    'css-definitions' => null,

    /*
    |--------------------------------------------------------------------------
    | Serializer
    |--------------------------------------------------------------------------
    */

    'serializer' => [
        'driver' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),
        'cache' => \Stevebauman\Purify\Cache\CacheDefinitionCache::class,
    ],

];
