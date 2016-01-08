<?php

if (class_exists('WPLessPlugin')) {
    $less = WPLessPlugin::getInstance();
    $options = get_option(AZEXO_THEME_NAME);
    if (isset($options['brand-color']))
        $less->addVariable('brand-color', $options['brand-color']);
    if (isset($options['accent-1-color']))
        $less->addVariable('accent-1-color', $options['accent-1-color']);
    if (isset($options['accent-2-color']))
        $less->addVariable('accent-2-color', $options['accent-2-color']);
}