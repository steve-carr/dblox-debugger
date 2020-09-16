<?php
/** functions.php
 *  This is provided to show how you can include the debugger in a theme
 *  instead of a plugin. If you are installing as a plugin - remove the file.
 */

//* This is where you favorite theme might have functions defined...

/*
 * Everything above this point is part of the parent or child theme
 * Everything below this point is your custom code.
 */

add_action( 'after_setup_theme', 'enable_the_debugger', 15 );

function enable_the_debugger() {
    include dirname( __FILE__ ) . '/lib/dblox-debugger.php';
}
