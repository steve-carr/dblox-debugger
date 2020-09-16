<?php

/** WordPress required details
Plugin Name:     Dblox Debugger
Plugin URI:      https://twocarrs.com/dblox
Description:     General purpose debugger.
Author:          Two Carr Productions, LLC
Author URI:      https://twocarrs.com/productions
Version:         2.00.00
Text Domain:     dblox_text_domain
Domain Path:     /languages
Network:         true            // <== Multisite enabled
Requires at least: 5.2
Requires PHP:    5.6.20
* 
*  Specify "Network: true" to require that a plugin is activated
*  across all sites in an installation. This will prevent a plugin from being
*  activated on a single site when Multisite is enabled.
*/
/**
 * Copyright (C) 2020 Two Carr Productions, LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dblox\Products\Debugger;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('DBLOX_TEXT_DOMAIN')) {
    //* Make WP required detail (above) the same value
    define('DBLOX_TEXT_DOMAIN', 'dblox_text_domain');
}

$function_name = __NAMESPACE__."\\".'pre_debugger_init';
if( !function_exists($function_name) ){

    function pre_debugger_init()
    {
        $title = __('Dblox Debugger', DBLOX_TEXT_DOMAIN);
        $good = validate_requirements($title);
        if (!$good) { return; }
        $label = str_replace(' ', '_', strtolower($title));
        include __DIR__ . '/lib/debugger.php';
        $args['vars'] = [
            'title'     => $title,
            'version'   => '2.0.0',
            'label'     => $label,
            'dir'       => __DIR__,
            'namespace' => __NAMESPACE__,
        ];
        $class = __NAMESPACE__."\\".'Api';
        $api = $class::get_instance($args);
        //* set up for debugging
        $api->define_debug_flag('DEBUG');
        //* examples of debug flag seeting
        $api->define_debug_flag('PLUGIN');
        $api->define_debug_flag('THEME');
        $api->define_debug_flag('REGISTRATION');
        //* silences output to the log file
        $api->disable_debug( null ); 
    }

}

$validate_requirements = __NAMESPACE__ . "\\" . 'validate_requirements';
if( !function_exists($valid_requirements) ){

    function validate_requirements($title)
    {
        //* Use "dirname(__FILE__)" & "array()" in case php is pre-5.3
        $dir = dirname(__FILE__);
        $min_reqs = array(
            'title' => $title,
            'php'   => '5.6.25',  //* 5.6.25 = earliest version I have to test with.
            'wp'    => '3.8.0',   //* 3.8 Help needs >=3.3 Customizer needs >=3.4
            'file'  => __FILE__,
        );
        include_once $dir . '/requirements-check.php';
        //* check minimum resource requirements *//
        $check = Framework\Requirements_Check::get_instance($min_reqs);
        return $check->if_requirements_pass();
    }

}

$label = str_replace("\\", '_', strtolower($function_name));
add_action($label, $function_name);
do_action($label);

//* Example of how one might invoke the debugger
if ( ! TRUE ) {
    $class = __NAMESPACE__."\\".'debugger';
    $dbgr = $class::get_instance();
    $dbgr->write_log( 'Enter', 'My_Example' );
    $dbgr->my_example($dbgr);
    $dbgr->write_log( 'Exit ', 'My_Example' );
    
    function my_example($dbgr)
    {
      // write some code...
      //* test a variable's value
      $dbgr->write_log( __FUNCTION__, '$var='.$var, 'REGISTRATION' );
      //* I can turn off this log while keeping other debugging working by
      $dbgr->disable_flag('REGISTRATION');
    }
}
?>