<?php
/**
 * Little Software Stats
 *
 * An open source program that allows developers to keep track of how their software is being used
 *
 * @package		Little Software Stats
 * @author		Little Apps
 * @copyright           Copyright (c) 2011, Little Apps
 * @license		http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 * @link		http://little-apps.org
 * @since		Version 0.1
 * @filesource
 */

if ( !defined( 'LSS_LOADED' ) ) die( 'This page cannot be loaded directly' );

if ( !function_exists( 'tr' ) ) {
    /**
     * Gets the translation for the specified text
     * @param string $text Text to get translation for
     * @return string Translation
     */
    function tr( $text ) {
        if ( empty( $text ) )
            return "";

        return gettext( $text );
    }
}

if ( !function_exists( '__' ) ) {
    /**
     * Returns translations for text
     * @param string $text Translation to lookup
     * @return string Translated text
     */
    function __( $text ) {
        return tr( $text );
    }
}

if ( !function_exists( '_e' ) ) {
    /**
     * Echoes translations for text
     * @param string $text Translation to lookup
     */
    function _e( $text ) {
        echo tr( $text );
    }
}

if ( !function_exists( 'verify_user' ) ) {
    /**
     * Make sure users logged in, otherwise, redirect them to login page
     * @global SecureLogin $login Secure login class
     */
    function verify_user( ) {
        global $login;
        
        if ( !$login->check_user( ) ) { 
            header( sprintf( "Location: %s/login.php", rtrim( SITE_URL, '/' ) ) );
            die( );
        }
    }
}

if ( !function_exists( 'get_option' ) ) {
    /**
     * Gets value for option
     * @global MySQL $db MySQL class
     * @param string $name Name to lookup
     * @return string|null Returns value as a string, otherwise null if name cannot be find
     */
    function get_option( $name ) {
        global $db;
        
        if ( !$db->select( 'options', array( 'name' => $name ), '', '0,1' ) )
            return null;
        
        return $db->arrayed_result['value'];
    }
}

if ( !function_exists( 'set_option' ) ) {
    /**
     * Sets option value
     * @global MySQL $db MySQL class
     * @param string $name Name for option
     * @param string $value Value for option
     * @return bool True if value was set, otherwise false if there was an error
     */
    function set_option( $name, $value ) {
        global $db;
        
        if ( !is_string( $value) )
            $value = strval( $value );
        
        $vars = array( 'name' => $name, 'value' => $value );
        
        if ( !$db->insert_or_update($vars, $vars, 'options') )
            return false;
        
        return true;
    }
}

if ( !function_exists( 'generate_csrf_token' ) ) {
    /**
     * Generates CSRF so it can be added to a form
     * @param bool $echo If true, echoes input field, otherwise, returns it (default: true)
     * @return string If $echo is false, returns input field
     */
    function generate_csrf_token( $echo = true ) {
        if ( SITE_CSRF == false )
            return;
        
        if ( isset( $_SESSION['token'] ) )
            $token = $_SESSION['token'];
        else {
            $token = md5(uniqid(rand(), true));
            $_SESSION['token'] = $token;
        }

        $ret = '<input name="token" type="hidden" value="'.$token.'" />';
        
        if ( $echo )
            echo $ret;
        else
            return $ret;
    }
}

if ( !function_exists( 'verify_csrf_token' ) ) {
    /**
     * Verifies that CSRF token is valid 
     */
    function verify_csrf_token() {
        if ( SITE_CSRF == false )
            return true;
        
        $is_valid = true;
        
        if ( !isset( $_SESSION['token'] ) || !isset( $_POST['token'] ) )
            $is_valid = false;
        
        if ( $_POST['token'] != $_SESSION['token'] )
            $is_valid = false;
            
        if ( !$is_valid )
            die( __('Cross-site request forgery token is invalid') );
        
        //unset( $_SESSION['token'] );
    }
}

if ( !function_exists( 'get_page_contents' ) ) {
    /**
     * Gets webpage contents using either cURL or file_get_contents()
     * @param string $url URL to retrieve
     * @param string $file_path File path to download to (default: '')
     * @return string|bool Returns webpage contents or true if URL was downloaded to file, otherwise false if an error occurred
     */
    function get_page_contents( $url, $file_path = '' ) {
        $ret = false;
        
        if ( function_exists( 'curl_init' ) ) {
            $ch = curl_init( $url );
            
            if ( $file_path != '' ) {
                if ( strpos( $url, '.gz' ) !== false ) 
                    $fp = fopen( $file_path . '.gz', 'w' );
                else
                    $fp = fopen( $file_path, 'w' );
                if ( !$fp )
                    return false;
                
                curl_setopt( $ch, CURLOPT_FILE, $fp );
            }

            curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
            curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 1000 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
            if ( !isset( $fp ) )
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            
            $ret = curl_exec( $ch );

            if ( curl_errno( $ch ) )
                return false;

            if ( isset( $fp ) )
                fclose( $fp );
            curl_close( $ch );
            
            if ( strpos( $url, '.gz' ) !== false ) {
                copy( 'compress.zlib://' . $file_path . '.gz', $file_path );
                
                if ( file_exists( $file_path ) )
                    unlink( $file_path . '.gz' );
            }
        } else if ( ini_get('allow_url_fopen') == true ) {
            // Takes 2x as long as cURL
            
            if ( strpos( $url, '.gz' ) !== false )
                $url = 'compress.zlib://' . $url;
            
            if ( $file_path == '' ) {
                $ret = @file_get_contents( $url );
            
                if ( !is_string( $ret ) )
                    return false;
            } else {
                $ret = copy( $sURL, $file_path );
            }
            
        }
        
        return $ret;
    }
}

if ( !function_exists( 'is_geoip_update_available' ) ) {
    /**
     * Checks if GeoIP database is up to date
     * @return boolean True if update available, otherwise false if current version or error occurred
     */
    function is_geoip_update_available() {
        $last_checked = get_option( 'geoips_database_checked' );
        
        if ( $last_checked != null && ( time() - strtotime( $last_checked ) ) < strtotime( '+2 weeks', 0 ) ) 
            return false;
        
        $update_url = get_option( 'geoips_database_update_url' );
        $current_version = get_option( 'geoips_database_version' );
        
        $xml_contents = get_page_contents( $update_url );

        if ( $xml_contents == false ) 
            return false;
        
        $xml = simplexml_load_string( $xml_contents );
        
        $last_version = (string)$xml->version;
        $download_url = (string)$xml->download;
        
        set_option( 'geoips_database_checked', date( "Y-m-d" ) );  
        
        if ( strtotime( $last_version ) > strtotime( $current_version ) ) {
            $_SESSION['geoip_update_url'] = $download_url;
            $_SESSION['geoip_database_ver'] = $last_version;
            return true;
        } else {
            return false;
        }
    }
}

if ( !function_exists( 'download_geoip_update' ) ) {
    /**
     * Downloads and updates to latest GeoIP database
     * @return boolean True if update was successful, otherwise false
     */
    function download_geoip_update() {
        if ( !isset( $_SESSION['geoip_update_url'] ) ) 
            return false;
        
        if ( !is_string( $_SESSION['geoip_update_url'] ) )
            return false;
        
        $url = $_SESSION['geoip_update_url'];
        $dst_file = get_option( 'geoips_database' );
        
        if ( get_page_contents( $url, $dst_file ) ) {
            set_option( 'geoips_database_version', $_SESSION['geoip_database_ver'] );
            
            unset( $_SESSION['geoip_database_ver'] );
            unset( $_SESSION['geoip_update_url'] );
            
            return true;
        }
        
        return false;
    }
}

if ( !function_exists( 'get_min_uri' ) ) {
    /**
     * Gets minified URI for specified group
     * @param string $group Group
     * @param bool $echo If true echoes URI, otherwise returns it (default: true)
     * @return string Returns minified URI if $echo is false
     */
    function get_min_uri( $group, $echo = true ) {
        $uri = Minify_getUri( $group, array( 'minAppUri' => SITE_URL . '/min', 'rewriteWorks' => false ) );
        
        if ( $echo )
            echo $uri;
        else
            return $uri;
    }
}

if ( !function_exists( 'send_mail' ) ) {
    /**
     * Sends mail using geek mail
     * @global GeekMail $geek_mail Class for GeekMail
     * @param string $to Email address to send to
     * @param string $subject Subject
     * @param string $message Message to send
     * @param string $attach Path of file to attach
     * @return bool Returns true if message was sent
     */
    function send_mail( $to, $subject, $message, $attach = '' ) {
        global $geek_mail;

        $geek_mail->from( SITE_NOREPLYEMAIL, __( 'Little Software Stats' ) );
        $geek_mail->to( $to );
        $geek_mail->subject( $subject );
        $geek_mail->message( $message );
        if ( $attach != '' )
            $geek_mail->attach( $attach );

        return $geek_mail->send();
    }
}

if ( !function_exists( 'get_language_by_lcid' ) ) {
    /**
     * Looks up LCID and returns information
     * @global MySQL $db Class for MySQL
     * @param int $lcid LCID to lookup
     * @return string Returns language name, otherwise, 'Unknown' if it couldnt be found 
     */
    function get_language_by_lcid( $lcid ) {
        global $db;

        if ( !is_numeric( $lcid ) )
            return __( 'Unknown' );
        
        $lcid = intval( $lcid );

        if ( $db->select( 'locales', array( 'LCID' => $lcid ), '', '0,1' ) )
            return $db->arrayed_result['DisplayName'];

        return __( 'Unknown' );
    }
}

if ( !function_exists( 'create_date_range_array' ) ) {
    /**
     * Takes two dates formatted as epoch time and creates an inclusive array of the dates between the from and to dates.
     * @param int $date_from Start date
     * @param int $date_to End date
     * @param string $by Time range (day, week, or month)
     * @return array|bool Array of date range, otherwise, false if arguments are invalid
     */
    function create_date_range_array( $date_from, $date_to, $by = '' ) {
        $range = array();

        if ( $by == '' )
            $by = $_GET['graphBy'];

        // Make sure by argument is valid
        if ( $by != 'day' && $by != 'week' && $by != 'month' )
            return false;
        
        if ( $by == 'day' )
            $time_interval = 24 * 3600;
        elseif ( $by == 'week' )
            $time_interval = 7 * 24 * 3600;
        elseif ( $by == 'month' )
            $time_interval = 30 * 24 * 3600;  

        $diff = '+1 ' . $by;

        if ( $date_to >= $date_from ) {
            $range[] = $date_from; // first entry

            // Make sure we dont add any unneeded intervals
            if ( ( $date_to - $date_from ) > $time_interval ) {
                while ( $date_from < $date_to ) {
                    $date_from = strtotime( $diff, $date_from );
                    
                    if ( $date_from > $date_to ) {
                        $range[] = $date_to;
                        break;
                    }
                    
                    $range[] = $date_from;
                }
            }

            return $range;
        }

        return false;
    }
}

if ( !function_exists( 'page_title' ) ) {
    /**
     * Echoes page title
     */
    function page_title() {
        $pages = array(
            'dashboard' => __( 'Dashboard' ),
            'appsettings' => __( 'Application Settings' ),
            'executions' => __( 'Executions' ),
            'installations' => __( 'Installations' ),
            'uninstallations' => __( 'Uninstallations' ),
            'versions' => __( 'Versions' ),
            'licenses' => __( 'Licenses' ),
            'averagetime' => __( 'Average Time' ),
            'loyaltytime' => __( 'Loyalty Time' ),
            'newvsreturning' => __( 'New vs. Returning' ),
            'bouncerate' => __( 'Bounce Rate' ),
            'events' => __( 'Events' ),
            'eventstiming' => __( 'Events Timing' ),
            'eventsvalue' => __( 'Events Value' ),
            'customdata' => __( 'Custom Data' ),
            'logs' => __( 'Logs' ),
            'exceptions' => __( 'Exceptions' ),
            'operatingsystems' => __( 'Operating Systems' ),
            'languages' => __( 'Languages' ),
            'cpus' => __( 'CPUs' ),
            'memory' => __( 'Memory' ),
            'screenresolutions' => __( 'Screen Resolutions' ),
            'pluginsandvms' => __( 'Plugins and VMs' ),
            'mapoverlay' => __( 'Map Overlay' ),
            'myaccount' => __( 'My Account' ),
            'add' => __( 'Add Application' ),
            'settings' => __( 'Settings' )
        );

        $page = $_GET['page'];

        $page_title = 'Little Software Stats | ';

        if ( $page != 'settings' ) {
            $app_name = get_current_app_name();
            if ( $app_name != '' )
                $page_title .= $app_name . ' | ';
        }

        $page_title .= $pages[$page];

        echo $page_title;
    }
}

if ( !function_exists( 'get_page_url' ) ) {
    /**
     * Outputs page URL
     * @param string $page Page
     * @param bool $echo If true it echoes URL, otherwise, it returns the URL
     */
    function get_page_url( $page, $echo = true ) {    
        $id = $_GET['id'];
        $ver = $_GET['ver'];
        $graph_by = $_GET['graphBy'];
        $start = $_GET['start'];
        $end = $_GET['end'];

        $url = rtrim( SITE_URL, '/' );

        if ( get_option( 'site_rewrite' ) == 'true' )
            $url .= '/'.$id.'/'.$ver.'/'.$graph_by.'/'.$page.'/'.$start.'/'.$end;
        else    
            $url .= '/index.php?id='.$id.'&ver='.$ver.'&graphBy='.$graph_by.'&page='.$page.'&start='.$start.'&end='.$end;

        if ( $echo )
            echo $url;
        else
            return $url;
    }
}

if ( !function_exists( 'get_file_url' ) ) {
    /**
     * Returns file URL
     * @param string $file Relative path of file
     * @return string File URL
     */
    function get_file_url( $file ) {
        if ( !file_exists( SITE_PATH . '/' . ltrim( $file, '/' ) ) )
            return '';

        return rtrim( SITE_URL, '/') . '/' . ltrim( $file, '/' );
    }
}

if ( !function_exists( 'file_url' ) ) {
    function file_url( $file ) {
        echo get_file_url( $file );
    }
}

if ( !function_exists( 'get_applications' ) ) {
    /**
     * Gets applications info and sets id if its not already
     * @global MySQL $db Class for MySQL
     * @global bool $needs_refresh Variable for refresh page
     * @return array Applications
     */
    function get_applications() {
        global $db, $needs_refresh;

        $db->select( "applications" );

        $apps = array();

        if ( $db->records == 1 ) {            
            $apps[] = array( 'AppName' => $db->arrayed_result['ApplicationName'], 'AppId' => $db->arrayed_result['ApplicationId'] );

            if ( !isset( $_GET['id'] ) ) {
                $_GET['id'] = $db->arrayed_result['ApplicationId'];
                $needs_refresh = true;
            }
        } else if ( $db->records > 1 ) {
            foreach ( $db->arrayed_results as $row ) {
                if ( !isset( $_GET['id'] ) ) {
                    $_GET['id'] = $row['ApplicationId'];
                    $needs_refresh = true;
                }

                $apps[] = array( 'AppName' => $row['ApplicationName'], 'AppId' => $row['ApplicationId'] );
            }
        } else { // No records found
            if ( !isset( $_GET['id'] ) ) {
                $_GET['id'] = 'add';
                $needs_refresh = true;
            }
        }

        return $apps;
    }
}

if ( !function_exists( 'app_url' ) ) {
    /**
     * Echoes URL to application ID
     * @param string $id Application ID
     */
    function app_url( $id ) {
        $rewrite_enabled = get_option( 'site_rewrite' );
        $site_url = rtrim( SITE_URL, '/');
        $page = $_GET['page'];
        $ver = $_GET['ver'];
        $graph_by = $_GET['graphBy'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        
        if ( $rewrite_enabled == 'true' )
            echo $site_url . '/' . $id . '/' . $ver . '/' . $graph_by . '/' . $page . '/' . $start . '/' . $end;
        else 
            echo $site_url . '/?id='.$id.'&ver='.$ver.'&graphBy='.$graph_by.'&page='.$page.'&start='.$start.'&end='.$end;
    }
}

if ( !function_exists( 'get_current_app_name' ) ) {
    /**
     * Gets current application name
     * @global array $apps Applications
     * @return string Application Name, otherwise, returns nothing if not found
     */
    function get_current_app_name() {
        global $apps;
        
        if ( !isset( $apps ) )
            $apps = get_applications();
        
        foreach ( $apps as $app ) {
            if ( $app['AppId'] == $_REQUEST['id'] )
                return $app['AppName'];
        }
        
        return '';
    }
}

if ( !function_exists( 'app_versions' ) ) {
    /**
     * Gets application versions
     * @global MySQL $db Class for MySQL
     * @return string HTML Code
     */
    function app_versions() {
        global $db;

        $html = '<select id="versions" class="styledselect">';
        $html .= '<option value="all" ' . ( ( $_GET['ver'] == 'all' ) ? ( 'selected' ) : ( '' ) ) . '>'. __('All Versions') . '</option>';

        $db->select_distinct( 'ApplicationVersion', 'sessions', array( 'ApplicationId' => $_GET['id'] ), 'ApplicationVersion' );

        if ( $db->records == 1 ) {
            $ver = $db->arrayed_result['ApplicationVersion'];
            $html .= '<option value="'.$ver.'" '.( ( $_GET['ver'] == $ver ) ? ( 'selected' ) : ( '' ) ).'>'.$ver.'</option>';
        } else if ( $db->records > 1 ) {
            foreach ( $db->arrayed_results as $row ) {
                $ver = $row['ApplicationVersion'];
                $html .= '<option value="'.$ver.'" '.( ( $_GET['ver'] == $ver ) ? ( 'selected' ) : ( '' ) ).'>'.$ver.'</option>';
            } 
        }

        $html .= '</select>';

        echo $html;
    }
}

if ( !function_exists( 'get_unique_user_info' ) ) {
    /**
     * Get user data for unique ID
     * @global MySQL $db Class for MySQL
     * @param string $unique_id Unique ID
     * @return array|bool Returns user data or false if nothing found
     */
    function get_unique_user_info( $unique_id ) {
        global $db;

        $db->select( 'uniqueusers', array( 'UniqueUserId' => $unique_id ), '', '0,1' );

        if ( $db->records == 0 )
            return false;

        return $db->arrayed_result;
    }
}

if ( !function_exists( 'get_unique_user_from_session_id' ) ) {
    /**
     * Get unique ID from session ID
     * @global MySQL $db Class for MySQL
     * @param string $session_id Session ID
     * @return array|bool Returns unique ID or false if nothing found
     */
    function get_unique_user_from_session_id( $session_id ) {
        global $db;

        $db->select( 'sessions', array( 'SessionId' => $session_id ), '', '0,1' );

        if ( $db->records == 0 )
            return false;

        return $db->arrayed_result['UniqueUserId'];
    }
}

if ( !function_exists( 'calculate_percent' ) ) {
    /**
     * Calculates a percent
     * @param int $fraction Fraction
     * @param int $total Total
     * @param int $round Decimal to round to (default: 2)
     * @return int Percent on success, otherwise 0
     */
    function calculate_percent( $fraction, $total, $round = 2 ) {
        if ( !is_numeric( $fraction ) || !is_numeric( $total ) )
            return 0;
        
        if ( $total == 0 )
            return 0;
        
        $fraction = intval( $fraction );
        $total = intval( $total );

        $percent = ( ( $fraction / $total ) * 100 );

        return round( $percent, $round );
    }
}

if ( !function_exists( 'calculate_percentage_increase' ) ) {
    /**
     * Calculates percentage increase from last month period
     * @param int $last_month_period Total from last month period
     * @param int $current_period Total from current period
     * @return int Percentage increase, otherwise, 0 if not enough data available
     */
    function calculate_percentage_increase( $last_month_period, $current_period ) {
        if ( !is_numeric( $last_month_period ) || !is_numeric( $current_period ) )
            return 0;
        
        $last_month_period = intval( $last_month_period );
        $current_period = intval( $current_period );
        
        if ( $last_month_period == 0 ) {
            if ( $current_period > 0 )
                return 100; 
            else
                return 0;
        }
        
        $difference = ( ( ( $current_period - $last_month_period ) / $last_month_period ) * 100 );
        
        return round( $difference );
    }
}

if ( !function_exists( 'get_time_duration' ) ) {
    /**
     * Converts time duration to a string
     * @param int $duration Time duration (in seconds)
     * @return string Time duration
     */
    function get_time_duration( $duration ) {
        if ( !is_numeric( $duration ) )
            return '0 s';
        
        $duration = intval( $duration );
        
        $hours = $minutes = $seconds = 0;

        while($duration >= 3600) {
            $hours++;
            $duration -= 3600;
        }

        while($duration >= 60) {
            $minutes++;
            $duration -= 60;
        }

        $seconds = round( $duration );
        
        return ( ( $hours > 0 ) ? ( $hours . 'h ' ) : ( '' ) ) . ( ( $minutes > 0 ) ? ( $minutes . 'm ' ) : ( '' ) ) . $seconds . 's';
    }
}

if ( !function_exists( 'generate_app_id' ) ) {
    /**
     * Generates application ID
     * @return string Application ID
     */
    function generate_app_id() {
        $salt = "abcdef0123456789";
        $salt_len = strlen( $salt );
        
        $app_id = '';
        
        mt_srand(); 

        for ( $i = 0; $i < 32; $i++ ) { 
            $chr = substr( $salt, mt_rand( 0, $salt_len - 1 ), 1 ); 
            $app_id .= $chr;
        } 
        
        return $app_id;
    }
}

if ( !function_exists( 'convert_size_to_string' ) ) {
    /**
     * Converts size to nearest decimal and returns formatted string
     * (Based on Chris Jester-Young's implementation)
     * @param int $size Size (in bytes)
     * @param int $precision Number of decimal digits to round to (default: 2)
     * @return string|bool Returns formatted string, otherwise false if size is not a number
     */
    function convert_size_to_string( $size, $precision = 2) {
        if ( !is_numeric( $size ) )
            return false;
        
        $base = log( $size ) / log( 1024 );
        $suffixes = array( ' B', ' KB', ' MB', ' GB', ' TB' );   

        return round( pow( 1024, $base - floor( $base ) ), $precision ) . $suffixes[floor( $base )];
    }
}

if ( !function_exists( 'convert_area_chart_data_to_json' ) ) {
    /**
     * Converts area chart data to JSON so it can be parsed by HighCharts line chart
     * @param array $chart_data Array containing chart data
     * @return string|bool Returns JSON string or false if $chart_data isnt an array 
     */
    function convert_area_chart_data_to_json( $chart_data ) {
        if ( !is_array( $chart_data ) )
            return false;
        
        $json = array();
        
        foreach ( $chart_data as $name => $data ) {
            $json[] = array( 'name' => $name, 'data' => $data );
        }
        
        return json_encode( $json );
    }
}

if ( !function_exists( 'convert_pie_chart_data_to_json' ) ) {
    /**
     * Converts pie chart data to JSON so it can be parsed by HighCharts line chart
     * @param array $chart_data Array containing pie chart data
     * @return string|bool Returns JSON string or false if $chart_data isnt an array 
     */
    function convert_pie_chart_data_to_json( $chart_data ) {
        if ( !is_array( $chart_data ) )
            return false;
        
        $json = array();
        
        foreach ( $chart_data as $name => $total ) {
            if ( is_int( $name ) )
                $name = strval( $name );
            
            if ( !is_int( $total ) )
                $total = intval ( $total );
            
            $json[] = array( $name, $total );
        }
        
        return json_encode( $json );
    }
}

if ( !function_exists( 'show_msg_box' ) ) {
    /**
     * Generates a message box
     * @param string $text Caption of message box
     * @param string $type Type of message box (green, red, or yellow)
     * @param bool $echo If true, echoes HTML code, otherwise, returns it
     * @return string HTML code (or nothing if type was invalid)
     */
    function show_msg_box( $text, $type, $echo = true ) {
        $valid_types = array( 'green', 'red', 'yellow' );

        $ret = '';

        if ( in_array( $type, $valid_types ) && ( is_string( $type ) ) ) {
            $ret = "<div id=\"message-".$type."\">
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                    <tbody>
                    <tr>
                            <td class=\"".$type."-left\">".$text."</td>
                            <td class=\"".$type."-right\"><a class=\"close-".$type."\"><img alt=\"Close\" src=\"".get_file_url( '/images/table/icon_close_'.$type.'.gif' )."\"></a></td>
                    </tr>
                    </tbody>
                    </table>
                    </div>";
        }

        if ( $echo )
            echo $ret;
        else
            return $ret;
    }
}

if ( !function_exists( 'is_page_current' ) ) {
    /**
     * If current page, echoes class so it is selected
     * @param string $page Page name
     */
    function is_page_current( $page ) {
        if ( $page == $_GET['page'] )
            echo "sub_show";
    }
}
