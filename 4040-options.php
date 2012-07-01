<?php
/**
 * 40/40 Prayer Vigil - Settings Administration.
 * 
 * This file contains the logic necessary to allow the user to configure their display of the prayer guides.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 * @package FortyFortyPlugin
 * @version $Id$
 */

/**
 * Register the 40/40 Prayer Vigil options page.
 */
function fortyforty_plugin_menu() {
	add_options_page( __( '40/40 Prayer Vigil Options', 'fortyforty_plugin' ),
            __( '40/40 Prayer Vigil', 'fortyforty_plugin' ), 'manage_options', '4040-prayer-vigil',
            'fortyforty_plugin_options' );
}

/**
 * Register the options with the settings API.
 */
function fortyforty_register_settings() {
	
	$menuSlug = '4040-prayer-vigil';
	$optionGroup = sprintf( '%s_main', FortyForty::PLUGIN_OPTION_SET );
	
	register_setting( $menuSlug, FortyForty::PLUGIN_OPTION_SET, 'fortyforty_validate_options' );
	add_settings_section( $optionGroup, __( 'Options', 'fortyforty_plugin' ), sprintf( '%s_text', $optionGroup ),
            $menuSlug );
	
	// Regular settings.
	add_settings_field( FortyForty::YEAR, __( 'Year', 'fortyforty_plugin' ), 'fortyforty_option_year', $menuSlug,
			$optionGroup );
	add_settings_field( FortyForty::LANGUAGE, __( 'Language', 'fortyforty_plugin' ), 'fortyforty_option_language',
			$menuSlug, $optionGroup );
	add_settings_field( FortyForty::SCRIPTURE_VERSION, __( 'Scripture Version', 'fortyforty_plugin' ),
			'fortyforty_option_scripture_version', $menuSlug, $optionGroup );
	add_settings_field( FortyForty::TYPE, __( 'Prayer Guide Type', 'fortyforty_plugin' ), 'fortyforty_option_type',
			$menuSlug, $optionGroup );
	add_settings_field( FortyForty::OVERLAP, __( 'Days to Overlap', 'fortyforty_plugin' ), 'fortyforty_option_overlap',
			$menuSlug, $optionGroup );
	
	// Debugging settings.
	$debugGroup = sprintf( '%s_debug', FortyForty::PLUGIN_OPTION_SET );
	add_settings_section( $debugGroup, __( 'Debugging', 'fortyforty_plugin' ), sprintf( '%s_text', $debugGroup ),
            $menuSlug );
    
	add_settings_field( FortyForty::DEBUG_DATE, __( 'Force Date or Date/Time', 'fortyforty_plugin' ),
			'fortyforty_option_debug_date', $menuSlug, $debugGroup );
	add_settings_field( FortyForty::DEBUG_NUMBER, __( 'Force Day/Hour Number', 'fortyforty_plugin' ),
			'fortyforty_option_debug_number', $menuSlug, $debugGroup );
}

add_action( 'admin_menu', 'fortyforty_plugin_menu' );
add_action( 'admin_init', 'fortyforty_register_settings' );

/**
 * Handle the option administration for this plugin.
 */
function fortyforty_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div class="wrap">
	<h2><?php _e( '40/40 Prayer Vigil', 'fortyforty_plugin' ); ?></h2>
	<form method="post" action="options.php"><?php
		settings_fields( '4040-prayer-vigil' );
		do_settings_sections( '4040-prayer-vigil' ); ?>
        <br />
		<?php submit_button(); ?>
		<p>
			<strong><?php _e( 'NOTE', 'fortyforty_plugin' ); ?>:</strong>
			<?php _e( "The plugin caches the current day's or hour's results.", 'fortyforty_plugin' );
			_e( 'To force a refresh, just save this page with no changes', 'fortyforty_plugin' ); ?>
		</p>
        <p>
            <strong><?php _e( 'NOTE', 'fortyforty_plugin' ); ?>:</strong>
            <?php _e( 'Invalid values in the form above will be silently translated to valid ones.', 'fortyforty_plugin' ); ?>
        </p>
	</form>
	<p>
        <small>
        <?php _e( 'Version', 'fortyforty_plugin' ); ?>: <code><?php echo FortyForty::FORTYFORTY_VERSION; ?></code>
        </small>
    </p>
</div>
<?php
}

/**
 * Text to display before the setting group.
 */
function fortyforty_prayer_vigil_main_text() {
    // Nothing to say here...
}

/**
 * Text to display before the debug setting group.
 */
function fortyforty_prayer_vigil_debug_text() {
    
    echo '<p>';
    _e( 'These options can be used to force a particular date/time or number to be displayed.', 'fortyforty_plugin' );
    _e( 'If they are not valid, they will be blanked when the page is saved.', 'fortyforty_plugin' );
    echo '<br />';
    _e( '(The "Number" setting overrides the "Date/Time" setting.)', 'fortyforty_plugin' );
    echo '</p>';
    
}

/**
 * Display options for the YEAR setting.
 */
function fortyforty_option_year() {
    fortyforty_option_dropdown( FortyForty::YEAR, FortyForty::GetOptionList( '/List/Year', 'year' ) );
}

/**
 * Display options for the LANGUAGE setting.
 */
function fortyforty_option_language() {
    fortyforty_option_dropdown( FortyForty::LANGUAGE, FortyForty::GetOptionList( '/List/Language', 'language' ) );
}

/**
 * Display options for the SCRIPTURE_VERSION setting.
 */
function fortyforty_option_scripture_version() {
    fortyforty_option_dropdown( FortyForty::SCRIPTURE_VERSION, FortyForty::GetOptionList( '/List/Version', 'version' ) );
}

/**
 * Display options for the TYPE setting.
 */
function fortyforty_option_type() {
    fortyforty_option_dropdown( FortyForty::TYPE, array(
        array (
            'id'    => 'day',
            'value' => 'Daily (40 Days)' ),
        array(
            'id'    => 'hour',
            'value' => 'Hourly (40 Hours)')
        ));
}

/**
 * Display options for the OVERLAP setting.
 */
function fortyforty_option_overlap() { 
    fortyforty_option_input( FortyForty::OVERLAP, 2 );
}

/**
 * Display options for the DEBUG_DATE setting.
 */
function fortyforty_option_debug_date() {
    fortyforty_option_input(FortyForty::DEBUG_DATE, 20); ?> <code>YYYY-MM-DD</code> <?php
    _e( 'or' ); ?> <code>YYYY-MM-DD HH:MM:SS</code><?php
}

/**
 * Display options for the DEBUG_NUMBER setting.
 */
function fortyforty_option_debug_number() {
    fortyforty_option_input( FortyForty::DEBUG_NUMBER, 2 );
}

/**
 * Generate a dropdown list from a list of options.
 * 
 * @param string $optionName The name of the option.
 * @param array $listOptions The possible selections for the option.
 */
function fortyforty_option_dropdown( $optionName, $listOptions ) {
    
    $pluginOptions = get_option( FortyForty::PLUGIN_OPTION_SET ); ?>
    
    <select size="1" id="fortyforty_option_<?php echo $optionName; ?>"
            name="<?php printf( "%s[%s]", FortyForty::PLUGIN_OPTION_SET, $optionName ); ?>">
    <?php
        foreach ( $listOptions as $option ) { ?>
            <option value="<?php echo $option[ 'id' ]; ?>" <?php
                if ( $option[ 'id' ] == $pluginOptions[ $optionName ] ) echo 'selected="selected"';
            ?>><?php echo $option[ 'value' ]; ?></option><?php
        }
    ?>
    </select><?php
}

/**
 * Generate an input element for the given option.
 * 
 * @param string $optionName The name of the option.
 * @param int $inputSize The size of the input box.
 */
function fortyforty_option_input( $optionName, $inputSize ) {
    
    $pluginOptions = get_option( FortyForty::PLUGIN_OPTION_SET ); ?>
    <input type="text" size="<?php echo $inputSize; ?>" id="fortyforty_option_<?php echo $optionName; ?>"
           name="<?php printf( "%s[%s]", FortyForty::PLUGIN_OPTION_SET, $optionName ); ?>"
           value="<?php echo $pluginOptions[ $optionName ]; ?>" /><?php
}

/**
 * Validate the options from the user.
 * 
 * @param array $input The options from the page.
 */
function fortyforty_validate_options( $input ) {
	
	// Clear the cache.
	update_option( FortyForty::CACHE_OPTION_SET, array() );
	
	// Update the options.
	$options = get_option( FortyForty::PLUGIN_OPTION_SET );
	
	$options[ FortyForty::YEAR              ] = $input[ FortyForty::YEAR              ];
	$options[ FortyForty::LANGUAGE          ] = $input[ FortyForty::LANGUAGE          ];
    $options[ FortyForty::SCRIPTURE_VERSION ] = $input[ FortyForty::SCRIPTURE_VERSION ];
    $options[ FortyForty::TYPE              ] = $input[ FortyForty::TYPE              ];
    
    $options[ FortyForty::OVERLAP ] = fortyforty_plugin_validate_overlap( $input[ FortyForty::OVERLAP ] );
    $options[ FortyForty::DEBUG_DATE ] = fortyforty_plugin_validate_debug_date( $input[ FortyForty::DEBUG_DATE ],
            $input[ FortyForty::TYPE ] );
    $options[ FortyForty::DEBUG_NUMBER ] = fortyforty_plugin_validate_debug_number( $input[ FortyForty::DEBUG_NUMBER ] );
    
	return $options;
}

/**
 * Validate the overlap.
 * 
 * @param string $inputOverlap The value input by the user.
 * @return string The overlap to set.
 */
function fortyforty_plugin_validate_overlap( $inputOverlap ) {
    
    if ( is_numeric( $inputOverlap ) ) {
        
        $overlap = ( $inputOverlap + 0) / 1;
        
        if ( ( -1 < $overlap ) && ( 61 > $overlap ) ) {
            return "$overlap";
        }
    }
    
    add_action( 'admin_notices', create_function( '', sprintf( 'echo \'<div class="error"><p>%s</p></div>\';',
            __( 'WARNING: "Overlap" value must be between 0 and 60; value set to 30.', 'fortyforty_plugin' ))));
    
    return '30';
}

/**
 * Validate the debug date.
 * 
 * @param string $inputDate The value input by the user.
 * @param string $type The prayer guide type.
 * @return string The debug date, properly formatted.
 */
function fortyforty_plugin_validate_debug_date( $inputDate, $type ) {
    
    if ( empty( $inputDate ) )
        return '';
        
    try {
            
        $date = new DateTime( $inputDate );
            
        return ( 'hour' == $type )
                ? $date->format( 'Y-m-d H:00:00' )
                : $date->format( 'Y-m-d' );
            
    } catch (Exception $exception) {
        // Invalid date; we'll catch that below.
    }
    
    add_action( 'admin_notices', create_function('', sprintf( 'echo \'<div class="error"><p>%s</p></div>\';',
            __( 'WARNING: "Force Date or Date/Time" was not valid; value cleared.', 'fortyforty_plugin' ))));
    
    return '';
}

/**
 * Validate the debug number.
 * 
 * @param string $inputNumber The value input by the user.
 * @return string The number to set.
 */
function fortyforty_plugin_validate_debug_number( $inputNumber ) {
    
    if ( is_numeric( $inputNumber ) ) {
        
        $number = ( $inputNumber + 0) / 1;
        
        if ( ( -1 < $number ) && ( 42 > $number ) ) {
            return "$number";
        }
    }
    
    add_action( 'admin_notices', create_function( '', sprintf( 'echo \'<div class="error"><p>%s</p></div>\';', 
        __( 'WARNING: "Force Day/Hour Number" must be between 0 and 41; value cleared.', 'fortyforty_plugin' ))));
    
    return '';
}
