<?php
/*
WPUpdates Plugin Updater Class
http://wp-updates.com
v2.0
Modified for SportsPress
*/

if ( !class_exists('SP_Updater') ) {
    class SP_Updater {
    
    	var $api_url;
    	var $plugin_id;
    	var $plugin_path;
    	var $plugin_slug;
    	var $license_key;
    
    	function __construct( $api_url, $plugin_path, $license_key = null ) {
            if ( class_exists( 'SportsPress_Agency' ) ) {
                $this->plugin_id = 1208;
            } elseif ( class_exists( 'SportsPress_Multisite' ) ) {
                $this->plugin_id = 1013;
            } elseif ( class_exists( 'SportsPress_Tournaments' ) ) {
                $this->plugin_id = 1017;
            } else {
                $this->plugin_id = 1018;
            }
    		$this->api_url = $api_url;
    		$this->plugin_path = $plugin_path;
    		$this->license_key = $license_key;
    		if(strstr($plugin_path, '/')) list ($t1, $t2) = explode('/', $plugin_path); 
    		else $t2 = $plugin_path;
    		$this->plugin_slug = str_replace('.php', '', $t2);
    
    		add_filter( 'pre_set_site_transient_update_plugins', array(&$this, 'check_for_update') );
    		add_filter( 'plugins_api', array(&$this, 'plugin_api_call'), 10, 3 );
    	}
    
    	function check_for_update( $transient ) {
    		if(empty($transient->checked)) return $transient;
    		
    		$request_args = array(
    		    'id' => $this->plugin_id,
    		    'slug' => $this->plugin_slug,
    			'version' => $transient->checked[$this->plugin_path]
    		);
    		if ($this->license_key) $request_args['license'] = $this->license_key;
    		
    		$request_string = $this->prepare_request( 'update_check', $request_args );
    		$raw_response = wp_remote_post( $this->api_url, $request_string );
    		
    		$response = null;
    		if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) )
    			$response = unserialize($raw_response['body']);
    		
    		if( is_object($response) && !empty($response) ) {
                // Feed the update data into WP updater
    			$transient->response[$this->plugin_path] = $response;
                return $transient;
            }

            // Check to make sure there is not a similarly named plugin in the wordpress.org repository
            if ( isset( $transient->response[$this->plugin_path] ) ) {
                if ( strpos( $transient->response[$this->plugin_path]->package, 'wordpress.org' ) !== false  ) {
                    unset($transient->response[$this->plugin_path]);
                }
            }

    		return $transient;
    	}
    
    	function plugin_api_call( $def, $action, $args ) {
    		if( !isset($args->slug) || $args->slug != $this->plugin_slug ) return $def;
    		
    		$plugin_info = get_site_transient('update_plugins');
    		$request_args = array(
    		    'id' => $this->plugin_id,
    		    'slug' => $this->plugin_slug,
    			'version' => (isset($plugin_info->checked)) ? $plugin_info->checked[$this->plugin_path] : 0 // Current version
    		);
		    if ($this->license_key) $request_args['license'] = $this->license_key;
    		
    		$request_string = $this->prepare_request( $action, $request_args );
    		$raw_response = wp_remote_post( $this->api_url, $request_string );
    		
    		if( is_wp_error($raw_response) ){
    			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $raw_response->get_error_message());
    		} else {
    			$res = unserialize($raw_response['body']);
    			if ($res === false)
    				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $raw_response['body']);
    		}
    		
    		return $res;
    	}
    
    	function prepare_request( $action, $args ) {
    		global $wp_version;
    		
    		return array(
    			'body' => array(
    				'action' => $action, 
    				'request' => serialize($args),
    				'api-key' => md5(home_url())
    			),
    			'user-agent' => 'WordPress/'. $wp_version .'; '. home_url()
    		);	
    	}
    	
    	function debug_result( $res, $action, $args ) {
    		echo '<pre>'.print_r($res,true).'</pre>';
    		return $res;
    	}
    
    }
}