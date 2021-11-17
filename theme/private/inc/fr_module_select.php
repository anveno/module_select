<?php
// Module Select CSS/JS
if (!rex::isFrontend()) {
    rex_extension::register('PACKAGES_INCLUDED', function () {
        if (file_exists(theme_path::assets('module_select/backend.css'))) {
            rex_view::addCssFile(theme_url::assets('module_select/backend.css'));
        }
        if (file_exists(theme_path::assets('module_select/backend.js'))) {
            rex_view::addJSFile(theme_url::assets('module_select/backend.js'));
        }
    }, \rex_extension::LATE);
}