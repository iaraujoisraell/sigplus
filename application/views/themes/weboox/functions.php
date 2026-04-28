<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
* Required app theme head hook
*/
hooks()->add_action('app_customers_head', 'app_theme_head_hook');

/**
* Flat theme menu items
*/
hooks()->add_action('clients_init', 'add_default_theme_menu_items');

register_theme_assets_hook('theme_assets');

function theme_assets()
{
    $CI = &get_instance();

    $groupName = $CI->app_scripts->default_theme_group();

    $CI->app_scripts->theme('bootstrap-js', 'assets/plugins/bootstrap/js/bootstrap.min.js');

    add_datatables_js_assets($groupName);
    add_jquery_validation_js_assets($groupName);
    add_bootstrap_select_js_assets($groupName);

    $CI->app_scripts->theme('datetimepicker-js', 'assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js');
    $CI->app_scripts->theme('chart-js', 'assets/plugins/Chart.js/Chart.min.js');
    $CI->app_scripts->theme('colorpicker-js', 'assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');
    $CI->app_scripts->theme('lightbox-js', 'assets/plugins/lightbox/js/lightbox.min.js');

    if (is_client_logged_in()) {

        $CI->app_scripts->theme('dropzone-js', 'assets/plugins/dropzone/min/dropzone.min.js');
        $CI->app_scripts->theme('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');

        $CI->app_scripts->theme('jquery-comments-js', 'assets/plugins/jquery-comments/js/jquery-comments.min.js');
        $CI->app_scripts->theme('jquery-gantt-js', 'assets/plugins/gantt/js/jquery.fn.gantt.min.js');
        add_moment_js_assets($groupName);
        add_dropbox_js_assets($groupName);

        $CI->app_css->theme('jquery-comments-css', 'assets/plugins/jquery-comments/css/jquery-comments.css');
        $CI->app_css->theme('jquery-gantt-css', 'assets/plugins/gantt/css/style.css');
        add_calendar_assets($groupName, false);

        if (get_option('enable_google_picker') == '1') {
            add_google_api_js_assets($groupName);
        }
    }

    $CI->app_scripts->theme('common-js', 'assets/builds/common.js');

    $CI->app_scripts->theme(
        'theme-global-js',
        base_url($CI->app_scripts->core_file(theme_assets_path() . '/js', 'global.js')) . '?v=' . $CI->app_css->core_version(),
        ['common-js']
    );

    if (is_client_logged_in()) {
        $CI->app_scripts->theme(
            'theme-js',
            base_url($CI->app_scripts->core_file(theme_assets_path() . '/js', 'clients.js')) . '?v=' . $CI->app_css->core_version(),
            ['common-js']
        );
    }

    // CSS
    add_favicon_link_asset($groupName);

    $CI->app_css->theme(
        'reset-css',
        base_url($CI->app_css->core_file('assets/css', 'reset.css')) . '?v=' . $CI->app_css->core_version()
    );

    $CI->app_css->theme('bootstrap-css', 'assets/plugins/bootstrap/css/bootstrap.min.css');
    $CI->app_css->theme('roboto-css', 'assets/plugins/roboto/roboto.css');

    if (is_rtl()) {
        $CI->app_css->theme('bootstrap-rtl-css', 'assets/plugins/bootstrap-arabic/css/bootstrap-arabic.min.css');
    }

    $CI->app_css->theme('datatables-css', 'assets/plugins/datatables/datatables.min.css');
    $CI->app_css->theme('fontawesome-css', 'assets/plugins/font-awesome/css/font-awesome.min.css');
    $CI->app_css->theme('datetimepicker-css', 'assets/plugins/datetimepicker/jquery.datetimepicker.min.css');
    $CI->app_css->theme('bootstrap-select-css', 'assets/plugins/bootstrap-select/css/bootstrap-select.min.css');

    if (is_client_logged_in()) {
        $CI->app_css->theme('dropzone-basic-css', 'assets/plugins/dropzone/min/basic.min.css');
        $CI->app_css->theme('dropzone-css', 'assets/plugins/dropzone/min/dropzone.min.css');
    }

    $CI->app_css->theme('lightbox-css', 'assets/plugins/lightbox/css/lightbox.min.css');
    $CI->app_css->theme('colorpicker-css', 'assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
    $CI->app_css->theme('feathericon-css', 'assets/themes/flat/libs/feathericon/css/feathericon.css');

    $CI->app_css->theme(
        'bootstrap-overrides-css',
        base_url($CI->app_scripts->core_file('assets/css', 'bs-overides.css')) . '?v=' . $CI->app_css->core_version()
    );


    // $CI->app_css->theme(
    //     'theme-css',
    //     base_url($CI->app_scripts->core_file(theme_assets_path() . '/css', 'style.css')) . '?v=' . $CI->app_css->core_version()
    // );

    $CI->app_css->theme(
        'login-css',
        base_url($CI->app_scripts->core_file(theme_assets_path() . '/css', 'login.css')) . '?v=' . $CI->app_css->core_version()
    );

    $CI->app_css->theme(
        'weboox-css',
        base_url($CI->app_scripts->core_file(theme_assets_path() . '/css', 'metronic.css')) . '?v=' . $CI->app_css->core_version()
    );

    $CI->app_css->theme('fullcalendar-css', 'assets/themes/weboox/css/fullcalendar.bundle.css');
    $CI->app_css->theme('invoice-css', 'assets/themes/weboox/css/invoice-2.css');
    $CI->app_css->theme('perfectscrollbar-css', 'assets/themes/weboox/css/perfect-scrollbar.css');
    $CI->app_css->theme('datatables-css', 'assets/themes/weboox/css/datatables.bundle.css');
	$CI->app_css->theme('custom-css', 'assets/themes/weboox/css/custom.css');
    $CI->app_scripts->theme('my-script-js', 'assets/themes/weboox/js/my-script.js');
    $CI->app_scripts->theme('my-scripts-bundle-js', 'assets/themes/weboox/js/scripts.bundle.js');

    $CI->app_scripts->theme('global-js', 'assets/themes/weboox/js/global.js');
    $CI->app_scripts->theme('dashboard-js', 'assets/themes/weboox/js/dashboard.js');
    // $CI->app_scripts->theme('fullcalendar-js', 'assets/themes/weboox/js/fullcalendar.bundle.js');
    $CI->app_scripts->theme('fullcalendar-basic', 'assets/themes/weboox/js/basic.js');
    $CI->app_scripts->theme('fullcalendar-background', 'assets/themes/weboox/js/background-events.js');
    $CI->app_scripts->theme('fullcalendar-external', 'assets/themes/weboox/js/external-events.js');
    $CI->app_scripts->theme('fullcalendar-listview', 'assets/themes/weboox/js/list-view.js');
    $CI->app_scripts->theme('perfect-scrollbar', 'assets/themes/weboox/js/perfect-scrollbar.js');
    $CI->app_scripts->theme('clients-js', 'assets/themes/weboox/js/clients.js');
    $CI->app_scripts->theme('datatables-js', 'assets/themes/weboox/js/datatables.bundle.js');
    $CI->app_scripts->theme('mask-js', 'assets/themes/weboox/js/jquery.mask.min.js');
    $CI->app_scripts->theme('metronic-js', 'assets/themes/weboox/js/weboox.js');




}
