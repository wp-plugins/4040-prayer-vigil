<?php
/*
Plugin Name: 40/40 Prayer Vigil
Plugin URI: http://techblog.djs-consulting.com/category/programming/wordpress/plug-ins
Description: Displays the daily or hourly prayer guides for the 40/40 Prayer Vigil or 40/40 Campaña de Oración.
Version: 2012.0
Author: Daniel J. Summers
Author URI: http://techblog.djs-consulting.com

This plug-in provides a widget to display either the daily or hourly prayer guides. For more information on the 40/40
Prayer Vigil, created by the Ethics and Religious Liberty Commission of the Southern Baptist Convention, see
http://erlc.com/4040.  
*/

// Assemble the pieces to make one coherent plug-in.
$dir = dirname( __FILE__ );

require_once( $dir . '/4040-class.php'   ); 
require_once( $dir . '/4040-widget.php'  );
require_once( $dir . '/4040-options.php' );
