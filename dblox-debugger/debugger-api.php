<?php

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
/** Developments Details
 * 
 * @author          Two Carr Productions, LLC
 * @link            https://twocarrs.com/productions
 * @copyright       Copyright (c) 2020 Two Carr Productions, LLC
 * @license         GPL-2+
 * 
 * @package         Framework\Products\Debugger\Api
 * @version         1.0.0
 * @since           1.0.0
 */

namespace Dblox\Products\Debugger;

class Api {

    //** ******* <!--/ DEBUGGER /--> ******* **//
    
    private static $instance;
    private $vars;
    private $flags;

    public static function get_instance($args=NULL)
    {
        if (null === static::$instance) {
            static::$instance = new static();
            static::$instance->vars = $args['vars'];
        }
        return static::$instance;
    }

    final protected function __construct() {}
    final private function _clone() {}
    final private function _wakeup() {}

    //** ******* <!--/ EXTERNAL SUPPORT METHODS /--> ******* **//
	
    final public function define_debug_flag($flags)
    {
        if (!is_array($flags)) { [$flags]; }
        foreach ($flags as $id) {
            $this->flags[$id] = TRUE;
        }
    }

    final public function show_debug_flags() 
    {
        $i = 1;
        foreach ($this->flags as $id => $state) {
            $msg = $i++ . ': ' . $id . ' = '.  $state;
            $this->write_log(__FUNCTION__, $msg);
            $html += $msg . '<br>';
         }
         return $html;
    }

    final public function is_debug_enabled($arg) 
    {
        foreach ($this->flags as $id => $state) {
            if ($arg === $id) { return $state; }
        }
        return NULL;
    }

    final private function set_state($targetFlags, $state=TRUE)
    {
        if (!is_array($targetFlags)) { [$targetFlags]; }
        foreach ($targetFlags as $targetId ) {
            foreach ($this->flags as $id => $currState) {
                if ($targetId === $id) { 
                     $this->flags[$id] = $state; 
                     $foundIt = TRUE;
                }
            }
            if (TRUE === $foundIt) { 
                $foundIt = FALSE;
            } else {
                $this->flags[$id] = $state; 
            }
        }
    }

    final public function enable_debug($flags) 
    {
        $this->set_state($flags, TRUE);
    }

    final public function disable_debug($flags) 
    {
        $this->set_state($flags, FALSE);
    }

    final public function write_log ($label, $value, $flag='DEBUG') 
    {
        // WP_DEBUG is set in ./wp-config.php
        if ( TRUE === WP_DEBUG) {
            if( TRUE === $this->states[$flag] ) {
                // Output written to ./wp-content/debug.log
                if ( is_array( $value ) || is_object($value) ) {
                    error_log('[' . $flag . ']' . $label );
                    error_log( print_r( $value, true ) );
                } else {
                    error_log('[' . $flag . ']' . $label . ' => ' . $value);
                };
            };
        };
    }
}
