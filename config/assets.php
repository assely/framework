<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Assets allowed areas with corresponded aliases.
    |--------------------------------------------------------------------------
    | Assets can be assigned only to this areas.
    | Each area represent diffrent wordpress
    | layer where assest will be loaded.
    |
     */
    'areas' => [
        'wp_enqueue_scripts' => 'theme',
        'admin_enqueue_scripts' => 'admin',
        'login_enqueue_scripts' => 'login',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed assets types.
    |--------------------------------------------------------------------------
    | Javascript and CSS assets types are only accepted.
    |
     */
    'types' => ['script', 'style'],

    /*
    |--------------------------------------------------------------------------
    | Allowed asset placement.
    |--------------------------------------------------------------------------
    | Assets can be placed only in inside <head>
    | or before closing </body> element.
    |
     */
    'placements' => ['head', 'footer'],

    /*
    |--------------------------------------------------------------------------
    | Allowed assets media types.
    |--------------------------------------------------------------------------
    | Asset with "style" type accepts this media types.
    |
    | @see http://www.w3.org/TR/CSS2/media.html#media-types
     */
    'media' => [
        'screen',
        'all',
        'braille',
        'embossed',
        'handheld',
        'print',
        'projection',
        'speech',
        'tty',
        'tv',
    ],

    /*
    |--------------------------------------------------------------------------
    | Buildin WordPress libraries.
    |--------------------------------------------------------------------------
    | This is the list of libraries which are provided
    | with WordPress. We can load this libraries
    | out of the box, without registering.
    |
    | @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     */
    'libraries' => [
        'jquery',
        'jquery-form',
        'jquery-color',
        'jquery-masonry',
        'masonry',
        'jquery-ui-core',
        'jquery-ui-widget',
        'jquery-ui-accordion',
        'jquery-ui-autocomplete',
        'jquery-ui-button',
        'jquery-ui-datepicker',
        'jquery-ui-dialog',
        'jquery-ui-draggable',
        'jquery-ui-droppable',
        'jquery-ui-menu',
        'jquery-ui-mouse',
        'jquery-ui-position',
        'jquery-ui-progressbar',
        'jquery-ui-selectable',
        'jquery-ui-resizable',
        'jquery-ui-selectmenu',
        'jquery-ui-sortable',
        'jquery-ui-slider',
        'jquery-ui-spinner',
        'jquery-ui-tooltip',
        'jquery-ui-tabs',
        'jquery-effects-core',
        'jquery-effects-blind',
        'jquery-effects-bounce',
        'jquery-effects-clip',
        'jquery-effects-drop',
        'jquery-effects-explode',
        'jquery-effects-fade',
        'jquery-effects-fold',
        'jquery-effects-highlight',
        'jquery-effects-pulsate',
        'jquery-effects-scale',
        'jquery-effects-shake',
        'jquery-effects-slide',
        'jquery-effects-transfer',
    ],

];
