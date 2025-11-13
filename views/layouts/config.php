<?php

/**
 * config.php
 *
 * Author: pixelcave
 *
 * Configuration file. It contains variables used in the template as well as the primary navigation array from which the navigation is created
 *
 */

/* Template variables */

use app\helpers\PermissionHelper;
use yii\helpers\BaseUrl;

$template = array(
    'name'              => Yii::$app->id,
    'version'           => '1.0',
    'author'            => 'Codeflix Web LLP',
    'robots'            => 'noindex, nofollow',
    'title'             => Yii::$app->id,
    'description'       => Yii::$app->id,
    // true                     enable page preloader
    // false                    disable page preloader
    'page_preloader'    => false,
    // true                     enable main menu auto scrolling when opening a submenu
    // false                    disable main menu auto scrolling when opening a submenu
    'menu_scroll'       => true,
    // 'navbar-default'         for a light header
    // 'navbar-inverse'         for a dark header
    'header_navbar'     => 'navbar-default',
    // ''                       empty for a static layout
    // 'navbar-fixed-top'       for a top fixed header / fixed sidebars
    // 'navbar-fixed-bottom'    for a bottom fixed header / fixed sidebars
    'header'            => '',
    // ''                                               for a full main and alternative sidebar hidden by default (> 991px)
    // 'sidebar-visible-lg'                             for a full main sidebar visible by default (> 991px)
    // 'sidebar-partial'                                for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-partial sidebar-visible-lg'             for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-mini sidebar-visible-lg-mini'           for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
    // 'sidebar-mini sidebar-visible-lg'                for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)
    // 'sidebar-alt-visible-lg'                         for a full alternative sidebar visible by default (> 991px)
    // 'sidebar-alt-partial'                            for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-alt-partial sidebar-alt-visible-lg'     for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-partial sidebar-alt-partial'            for both sidebars partial which open on mouse hover, hidden by default (> 991px)
    // 'sidebar-no-animations'                          add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!
    'sidebar'           => 'sidebar-partial sidebar-visible-lg sidebar-no-animations',
    // ''                       empty for a static footer
    // 'footer-fixed'           for a fixed footer
    'footer'            => '',
    // ''                       empty for default style
    // 'style-alt'              for an alternative main style (affects main page background as well as blocks style)
    'main_style'        => '',
    // ''                           Disable cookies (best for setting an active color theme from the next variable)
    // 'enable-cookies'             Enables cookies for remembering active color theme when changed from the sidebar links (the next color theme variable will be ignored)
    'cookies'           => '',
    // 'night', 'amethyst', 'modern', 'autumn', 'flatie', 'spring', 'fancy', 'fire', 'coral', 'lake',
    // 'forest', 'waterlily', 'emerald', 'blackberry' or '' leave empty for the Default Blue theme
    // 'theme'             => 'spring',
    'theme'             => '',
    // 'theme'             => 'emerald',
    // 'theme'             => '',
    // ''                       for default content in header
    // 'horizontal-menu'        for a horizontal menu in header
    // This option is just used for feature demostration and you can remove it if you like. You can keep or alter header's content in page_head.php
    'header_content'    => '',
    'active_page'       => Yii::$app->controller->id . '/' . Yii::$app->controller->action->id

);
// echo "<pre/>";print_r(Yii::$app->request);exit;

/* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
$primary_nav = array(
    array(
        'name'  => 'Dashboard',
        'url'   => BaseUrl::home() . 'site',
        'icon'  => 'fa fa-chart-line',
        'visible' => 1,
    ),
    array(
        'name'  => 'Users Management',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),

    array(
        'name'  => 'Admins',
        'url'   => BaseUrl::home() . 'admins',
        'icon'  => 'fa fa-user-tie',
        'visible' => PermissionHelper::checkUserHasAccess('admins', 'index'),
    ),
    array(
        'name'  => 'Users',
        'icon'  => 'gi gi-user',
        'visible' => true,
        'sub'   => array(
            array(
                'name'  => 'List',
                'url'   => BaseUrl::home() . 'users',
                'visible' => PermissionHelper::checkUserHasAccess('users', 'index'),
            ),
            array(
                'name'  => 'Tree',
                'url'   => BaseUrl::home() . 'tree',
                'visible' => PermissionHelper::checkUserHasAccess('tree', 'index'),
            ),
        )
    ),

    array(
        'name'  => 'Parties',
        'icon'  => 'fa fa-handshake',
        'visible' => PermissionHelper::checkUserHasAccess('party-categories', 'index') || PermissionHelper::checkUserHasAccess('parties', 'index'),
        'sub'   => array(
            array(
                'name'  => 'Party Categories',
                'url'   => BaseUrl::home() . 'party-categories',
                'visible' => PermissionHelper::checkUserHasAccess('party-categories', 'index')
            ),
            array(
                'name'  => 'Parties',
                'url'   => BaseUrl::home() . 'parties',
                'visible' => PermissionHelper::checkUserHasAccess('parties', 'index')
            ),
        )
    ),



    array(
        'name'  => 'Attendance Tracking',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),

    array(
        'name'  => 'Live Map',
        'url'   => BaseUrl::home() . 'map',
        'icon'  => 'fa fa-map-marker-alt',
        'visible' => PermissionHelper::checkUserHasAccess('map', 'index'),
    ),

    array(
        'name'  => 'Attendance',
        'icon'  => 'fa fa-clipboard',
        'visible' => PermissionHelper::checkUserHasAccess('attendance', 'active-user') || PermissionHelper::checkUserHasAccess('attendance', 'absent-user') ||  PermissionHelper::checkUserHasAccess('attendance', 'report'),
        'sub'   => array(
            array(
                'name'  => 'Today Active User',
                'url'   => BaseUrl::home() . 'attendance/active-user',
                'visible' => PermissionHelper::checkUserHasAccess('attendance', 'active-user')
            ),
            array(
                'name'  => 'Today Absent User',
                'url'   => BaseUrl::home() . 'attendance/absent-user',
                'visible' => PermissionHelper::checkUserHasAccess('attendance', 'absent-user')
            ),
            array(
                'name'  => 'Attendance Report',
                'url'   => BaseUrl::home() . 'attendance/report',
                'visible' => PermissionHelper::checkUserHasAccess('attendance', 'report')
            ),
        )
    ),

    array(
        'name'  => 'Employees Module',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),
    array(
        'name'  => 'Visits',
        'url'   => BaseUrl::home() . 'visits',
        'icon'  => 'fa fa-route',
        'visible' => PermissionHelper::checkUserHasAccess('visits', 'index')
    ),
    array(
        'name'  => 'Payments',
        'url'   => BaseUrl::home() . 'payments',
        'icon'  => 'fa fa-money-bill-wave',
        'visible' => PermissionHelper::checkUserHasAccess('payments', 'index'),
    ),
    array(
        'name'  => 'Notifications',
        'url'   => BaseUrl::home() . 'notifications',
        'icon'  => 'fa fa-bell',
        'visible' => PermissionHelper::checkUserHasAccess('notifications', 'index'),
    ),

    array(
        'name'  => 'Expense',
        'icon'  => 'fa fa-credit-card',
        'visible' => PermissionHelper::checkUserHasAccess('expense-categories', 'index') || PermissionHelper::checkUserHasAccess('fixed-expenses    ', 'index') || PermissionHelper::checkUserHasAccess('claimed-expenses', 'index'),
        'sub'   => array(
            array(
                'name'  => 'Expense Categories',
                'url'   => BaseUrl::home() . 'expense-categories',
                'visible' => PermissionHelper::checkUserHasAccess('expense-categories', 'index'),
            ),
            array(
                'name'  => 'Fixed Expense',
                'url'   => BaseUrl::home() . 'fixed-expenses',
                'visible' => PermissionHelper::checkUserHasAccess('fixed-expenses', 'index'),
            ),
            array(
                'name'  => 'Claimed Expense',
                'url'   => BaseUrl::home() . 'claimed-expenses',
                'visible' => PermissionHelper::checkUserHasAccess('claimed-expenses', 'index'),
            ),
        )
    ),

    array(
        'name'  => 'Leave',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),

    array(
        'name'  => 'Off Days',
        'url'   => BaseUrl::home() . 'off-days',
        'icon'  => 'fa fa-calendar-alt',
        'visible' => PermissionHelper::checkUserHasAccess('off-days', 'index'),
    ),

    array(
        'name'  => 'Leave',
        'icon'  => 'fa fa-bed',
        'visible' => PermissionHelper::checkUserHasAccess('leave-type', 'index') || PermissionHelper::checkUserHasAccess('leave', 'index'),
        'sub'   => array(
            array(
                'name'  => 'Leave Type',
                'url'   => BaseUrl::home() . 'leave-type/index',
                'visible' => PermissionHelper::checkUserHasAccess('leave-type', 'index')
                
            ),
            array(
                'name'  => 'Leave',
                'url'   => BaseUrl::home() . 'leave/index',
                'visible' => PermissionHelper::checkUserHasAccess('leave', 'index')
            ),
        )
    ),

    array(
        'name'  => 'Master',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),

    array(
        'name'  => 'Grade System',
        'icon'  => 'fa fa-graduation-cap',
        'visible' => true,
        'visible' => PermissionHelper::checkUserHasAccess('city-grades', 'index') || PermissionHelper::checkUserHasAccess('employee-grades', 'index'),
        'sub'   => array(
            array(
                'name'  => 'City Grades',
                'url'   => BaseUrl::home() . 'city-grades',
                'visible' => PermissionHelper::checkUserHasAccess('city-grades', 'index')
            ),
            array(
                'name'  => 'Employee Grades',
                'url'   => BaseUrl::home() . 'employee-grades',
                'visible' => PermissionHelper::checkUserHasAccess('employee-grades', 'index')
            ),
        )
    ),

    array(
        'name'  => 'States',
        'url'   => BaseUrl::home() . 'states',
        'icon'  => 'fa fa-globe',
        'visible' => PermissionHelper::checkUserHasAccess('states', 'index'),
    ),

    array(
        'name'  => 'Permissions',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),

    array(
        'name'  => 'Roles',
        'url'   => BaseUrl::home() . 'auth-roles',
        'icon'  => 'fas fa-user-lock',
        'visible' => true,
    ),

    array(
        'name'  => 'Setting Management',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
            '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
        'visible' => true
    ),
    array(
        'name'  => 'Settings',
        'url'   => BaseUrl::home() . 'setting/system',
        'icon'  => 'fa fa-sliders-h',
        'visible' => PermissionHelper::checkUserHasAccess('setting', 'system'),
    ),

    array(
        'name'  => 'Privacy Policy',
        'url'   => BaseUrl::home() . 'cms/update?id=2',
        'icon'  => 'fa fa-lock',
        'visible' => PermissionHelper::checkUserHasAccess('cms', 'update'),
    ),
    array(
        'name'  => 'Terms And Conditions',
        'url'   => BaseUrl::home() . 'cms/update?id=1',
        'icon'  => 'fa fa-file-alt',
        'visible' => PermissionHelper::checkUserHasAccess('cms', 'update'),
    ),
);
