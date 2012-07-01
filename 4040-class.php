<?php
/**
 * 40/40 Prayer Vigil - Main Logic.
 * 
 * This class contains the guts of the plug-in.  You should not need to edit this file.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 * @package FortyFortyPlugin
 * @version $Id$
 */
class FortyForty {
	
	/** Version. */
	const FORTYFORTY_VERSION = '2012.0';
	
	/** Web service URL home. */
	const URL_HOME = 'http://services.djs-consulting.com/FortyForty';
	
	//
	// Plugin options
	//
	const PLUGIN_OPTION_SET = 'fortyforty_prayer_vigil';
	const YEAR              = 'year';
	const LANGUAGE          = 'language';
	const SCRIPTURE_VERSION = 'scripture_version';
	const TYPE              = 'type';
	const OVERLAP           = 'overlap';
	const DEBUG_DATE        = 'debug_date';
	const DEBUG_NUMBER      = 'debug_number';
	const PLUGIN_VERSION    = 'version';
	
	/** @var mixed $_options Option Array. */
	private $_options = '';

	//
	// Cache
	//
	const CACHE_OPTION_SET = 'fortyforty_cache';
	const CACHE_DATE       = 'date';
	const CACHE_NUMBER     = 'number';
	const CACHE_CONTENT    = 'content';
	
	/** @var mixed $_cache Cache Array. */
	private $_cache = '';
	
	/**
	 * Gets or sets the year for the prayer guide.
	 * 
	 * @param string $year The year to set (optional).
	 * @return mixed The year for the prayer guide (get) or the current instance (set).
	 */
	public function Year( $year = '--' ) {
		if ( '--' == $year ) return $this->_options[ FortyForty::YEAR ];
		$this->_options[ FortyForty::YEAR ] = $year; return $this;
	}
	
	/**
	 * Gets or sets the language for the prayer guide.
	 * 
	 * @param string $language The language to set (optional).
	 * @return mixed The language for the prayer guide (get) or the current instance (set).
	 */
	public function Language( $language = '--' ) {
		if ( '--' == $language ) return $this->_options[ FortyForty::LANGUAGE ];
		$this->_options[ FortyForty::LANGUAGE ] = $language; return $this;
	}
	
	/**
	 * Gets or sets the Scripture version for the prayer guide.
	 * 
	 * @param string $version The Scripture version to set (optional).
	 * @return mixed The Scripture version for the prayer guide (get) or the current instance (set).
	 */
	public function ScriptureVersion( $version = '--' ) {
		if ( '--' == $version ) return $this->_options[ FortyForty::SCRIPTURE_VERSION ];
		$this->_options[ FortyForty::SCRIPTURE_VERSION ] = $version; return $this;
	}
	
	/**
	 * Gets or sets the type (hour or day) for the prayer guide.
	 * 
	 * @param string $type The type to set (optional).
	 * @return mixed The type for the prayer guide (get) or the current instance (set).
	 */
	public function Type( $type = '--' ) {
		if ( '--' == $type ) return $this->_options[ FortyForty::TYPE ];
		$this->_options[ FortyForty::TYPE ] = $type; return $this;
	}
	
	/**
	 * Gets or sets the overlap period where the plugin will show content.
	 * 
	 * @param string $overlap The overlap to set (optional).
	 * @return mixed The overlap in days (get) or the current instance (set).
	 */
	public function Overlap( $overlap = '--' ) {
		if ( '--' == $overlap ) return $this->_options[ FortyForty::OVERLAP ];
		$this->_options[ FortyForty::OVERLAP ] = $overlap; return $this;
	}
	
	/**
	 * Gets or sets the date that the plugin is forced to display (used for debugging).
	 * 
	 * @param string $debugDate The debug date/time to set (optional).
	 * @return mixed The debug date (get) or the current instance (set).
	 */
	public function DebugDate( $debugDate = '--' ) {
		if ( '--' == $debugDate ) return $this->_options[ FortyForty::DEBUG_DATE ];
		$this->_options[ FortyForty::DEBUG_DATE ] = $debugDate; return $this;
	}
	
	/**
	 * Gets or sets the day/hour number that the plugin is forced to display (used for debugging).
	 * 
	 * @param string $debugNumber The debug number to set (optional).
	 * @return mixed The debug number (get) or the current instance (set).
	 */
	public function DebugNumber( $debugNumber = '--' ) {
		if ( '--' == $debugNumber ) return $this->_options[ FortyForty::DEBUG_NUMBER ];
		$this->_options[ FortyForty::DEBUG_NUMBER ] = $debugNumber; return $this;
	}
	
	/**
	 * Gets or sets the version of this plugin.
	 * 
	 * @param string $version The version to set (optional).
	 * @param mixed The version of the plugin (get) or the current instance (set).
	 */
	public function Version( $version = '--' ) {
		if ( '--'== $version ) return $this->_options[ FortyForty::PLUGIN_VERSION ];
		$this->_options[ FortyForty::PLUGIN_VERSION ] = $version; return $this;
	}
	
	/**
	 * FortyForty Constructor.
	 * 
	 * @return FortyForty A FortyForty object properly initialized.
	 */
	public function __construct() {

		// Determine if we've already obtained today's date.
		$this->_options = get_option( FortyForty::PLUGIN_OPTION_SET );
	
		if ( '' == $this->_options ) {
			// New installation - add the options
			$this->_options = array();
			$this->Year             ( '2012' )
			     ->Language         ( 'en' )
			     ->ScriptureVersion ( 'hcsb' )
			     ->Type             ( 'day' )
			     ->Overlap          ( '30' )
			     ->DebugDate        ( '' )
			     ->DebugNumber      ( '' )
			     ->Version          ( FortyForty::FORTYFORTY_VERSION );
			
			update_option( FortyForty::PLUGIN_OPTION_SET, $this->_options );
		}
		
		// Populate the cache.
		$this->_cache = get_option( FortyForty::CACHE_OPTION_SET );
		
	}
	
	/**
	 * Display the prayer guide.
	 * 
	 * @return string The text of the prayer guide.
	 */
	public function PrayerGuide() {
		
		$guideDateTime = $this->GetCurrentDateTime();
        
        if ( ! $this->IsWithinRange( $guideDateTime ) ) {
            return '';
        }
		
		if ( is_array( $this->_cache ) && $this->_cache[ FortyForty::CACHE_DATE ] == $guideDateTime )
			return $this->_cache[ FortyForty::CACHE_CONTENT ];
		
		$guideNumber = $this->GetNumber( $guideDateTime );
		
		if ( is_array ($this->_cache ) && $this->_cache[ FortyForty::CACHE_NUMBER ] == $guideNumber )
			return $this->_cache[ FortyForty::CACHE_CONTENT ];
		
		$content = $this->HttpGet( sprintf( '/PrayerGuide/html/%s/%s/%s/%s/%s', $this->Year(), $this->Language(),
				strtoupper( $this->ScriptureVersion() ), $this->Type(), $guideNumber ) );
		
		$this->_cache = array(
				FortyForty::CACHE_DATE    => $guideDateTime,
				FortyForty::CACHE_NUMBER  => $guideNumber,
				FortyForty::CACHE_CONTENT => $content);
		
		update_option( FortyForty::CACHE_OPTION_SET, $this->_cache );
		
		return $content;
	}
	
	/**
	 * Get the current date/time.
	 * 
	 * @return string The date or date/time for the prayer guide.
	 */
	private function GetCurrentDateTime() {
		
		if ( ! empty( $this->_options[ FortyForty::DEBUG_DATE ] ) )
			return $this->DebugDate();
		
		$currentTime = new DateTime( current_time( 'mysql' ) );
		
		return ( 'hour' == $this->Type() )
				? $currentTime->format( 'Y-m-d H:00:00' )
				: $currentTime->format( 'Y-m-d' ); 
		
	}
	
    /**
     * Is the current date/time within the range to display?
     * 
     * @param string $guideDateTime The guide date/time.
	 * @return boolean True if the date is within the display range, false if not.
     */
    private function IsWithinRange( $guideDateTime ) {
        
        // If we have a guide number forced, we're in the range.
        if ( ! empty( $this->_options[ FortyForty::DEBUG_NUMBER ] ) )
            return true;
        
        $startDate = ( 'hour' == $this->Type() ) ? new DateTime( '2012-11-02' ) : new DateTime( '2012-09-26' );
		$startRange = $startDate->sub( DateInterval::createFromDateString( sprintf( '%s days', $this->Overlap() ) ) );
		
        $endDate = new DateTime( '2012-11-04' );
		$endRange = $endDate->add( DateInterval::createFromDateString( sprintf( '%s days', $this->Overlap() ) ) );
        
        $date = new DateTime( $guideDateTime );
        
        return ( ( $date >= $startRange ) && ( $date <= $endRange ) );
    }
    
	/**
	 * Get the day or hour number for the date/time.
	 * 
	 * @param string $guideDateTime The date or date/time for the prayer guide.
	 * @return string The number for the prayer guide.
	 */
	private function GetNumber( $guideDateTime ) {
		
		if ( ! empty( $this->_options[ FortyForty::DEBUG_NUMBER ] ) )
			return $this->DebugNumber();
		
		return FortyForty::HttpGet( sprintf( '/Number/%s/%s', ucfirst( $this->Type() ), $guideDateTime ) );
	}
	
	/**
	 * Return the content of an HTTP GET request.
	 * 
	 * @param string $url The request URL (not including the root URL).
	 * @return string The response.
	 */
	private static function HttpGet( $url ) {
		return file_get_contents( FortyForty::URL_HOME . $url );
	}
    
    /**
     * Get a list of options from the web service.
     * 
     * @param string $url The URL for the option list.
     * @param string $tag The XML tag for the option.
     * @return array The options in id/value pairs.
     */
    public static function GetOptionList( $url, $tag ) {
        
        $doc = new DOMDocument();
        $doc->loadXML(FortyForty::HttpGet( $url ) );
        
        $optionList = array();
        
        foreach ( $doc->getElementsByTagName( $tag ) as $elt )
            $optionList[] = array(
                'id'    => ( '' ==  $elt->getAttribute("id") ) ? $elt->textContent : $elt->getAttribute( 'id' ),
                'value' => $elt->textContent
            );
        
        return $optionList;
    }
}