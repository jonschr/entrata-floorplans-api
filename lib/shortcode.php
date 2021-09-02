<?php

//* Register the shortcode
function entrata_floorplans_shortcode( $atts ) {
    $args = shortcode_atts( array(
        'username' => null,
        'password' => null,
        'propertyid' => null,
        'layout' => 'default',
        'columns' => 3,
        'leaseurl' => null,
        'filters' => null,
        'limit' => 999,
        'onlyshow' => null,
        'gform_id' => null,
    ), $atts );

    ob_start();
    
    //* Error handling: tell the user which parameter is missing if needed
    if ( !$args['username'] )
        return 'Missing parameter: username';
        
    if ( !$args['password'] )
        return 'Missing parameter: password';
        
    if ( !$args['propertyid'] ) {
        echo '<p><strong>Missing parameter: propertyid.</strong> With a username/password, you can find the id right <a target="_blank" href="https://cardinal.entrata.com/api/v1/documentation/getProperties">here</a> or in the list below.</p>';
        
        do_action( 'property_id_fallback', $args );
        return ob_get_clean();
    }
    
    //* Enqueues
    wp_enqueue_style( 'entrata-floorplans');
    wp_enqueue_style( 'entrata-fancybox-theme' );
    wp_enqueue_script( 'entrata-fancybox-main' );
    
    $propertyids = $args['propertyid'];
    $user = $args['username'];
    $password = $args['password'];
    
    $propertyids = explode( ',', $propertyids );
    
    $floorplans = array();
    $floorplans2_data = array();
    
    foreach( $propertyids as $propertyid ) {
        $floorplanstemp = entrata_api_request_one( $user, $password, $propertyid );
        $floorplans = array_merge( $floorplans, $floorplanstemp );
        
        $floorplans2_datatemp = entrata_api_request_two( $user, $password, $propertyid );
        $floorplans2_data = array_merge( $floorplans2_data, $floorplans2_datatemp );
    }
    
    // echo '<pre style="font-size: 13px;">';
    //     print_r( $floorplans );
    // echo '</pre>';
    
    if ( $args['filters'] )
        do_action( 'entrata_filters', $floorplans, $args );
            
    //* Set up the outer markup for the layout
    printf( '<div class="floorplans layout-%s columns-%s">', $args['layout'], $args['columns'] );
    
        $count = 1;
        $limit = $args['limit'];
    
        foreach( $floorplans as $floorplan ) {
            
            //* Skip this floorplan if the name doesn't match an onlyshow
            $Name = $floorplan->Name;
            
            if ( $args['onlyshow']) {
                if (strpos($Name, $args['onlyshow'] ) !== false) {
                    do_action( 'do_before_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );
                    do_action( 'do_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );
                    do_action( 'do_after_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );
                }
            } else {
                do_action( 'do_before_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );
                do_action( 'do_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );
                do_action( 'do_after_loop_layout_' . $args['layout'], $floorplan, $floorplans2_data, $args );    
            }
            
            $count++;
            
            if ( $count > $limit )
                break;
        }
    
    echo '</div>';
    
    $gform_id = $args['gform_id'];
    if ( $gform_id ) {
        echo '<div style="display:none;" id="floorplan-gform-lightbox">';
            echo do_shortcode( '[gravityform id=' . $gform_id . ' title=true description=true ajax=true tabindex=49]' );
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode( 'entrata', 'entrata_floorplans_shortcode' );


function entrata_api_request_one( $user, $password, $propertyids ) {
    
    // $floorplans = get_transient( 'entrata_floorplans_' . $user . '_' . $password . '_' . $propertyids );
    
    if ( $floorplans != false )
        return $floorplans;
    
    //////////////////////////////////////////////////////////////
    // REQUEST 1: Get the floorplans (this is most of the data) //
    //////////////////////////////////////////////////////////////
        
    //* If everything's there, then prepare the request
    $jsonRequest = sprintf( '
    {
        "auth": {
            "type" : "basic"
        },
        "requestId" : 15,
        "method": {
            "name": "getFloorPlans",
            "version":"r1",
            "params": {
                "propertyId" : %s,
                "usePropertyPreferences" : "0"
            }
        }
    }',
    $propertyids
    );
    
    //* Set up the auth part of the request
    $auth = $user . ':' . $password;
    $auth = base64_encode( $auth );
        
    //* Start the request
    $resCurl = curl_init();
    
    curl_setopt( $resCurl, CURLOPT_HTTPHEADER,  array( 'Content-type: APPLICATION/JSON; CHARSET=UTF-8', 'Authorization: Basic ' . $auth ) );
    curl_setopt( $resCurl, CURLOPT_POSTFIELDS, $jsonRequest );
    curl_setopt( $resCurl, CURLOPT_POST, true );
    curl_setopt( $resCurl, CURLOPT_URL, 'https://cardinal.entrata.com/api/v1/properties' );
    curl_setopt( $resCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $resCurl, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
    
    $result = curl_exec( $resCurl );
    
    if( false === $result ) {
        echo 'Curl error: ' . curl_error( $resCurl );
        curl_close( $resCurl );
    } else {
        curl_close( $resCurl );
        $floorplans = ( json_decode( $result ) );            
    }
    
    // echo '<pre style="font-size: 13px;">';
    //     print_r( $floorplans );
    // echo '</pre>';
    
    //* This variable contains all of the information needed
    $floorplans = $floorplans->response->result->FloorPlans->FloorPlan;
    
    set_transient( 'entrata_floorplans_' . $user . '_' . $password . '_' . $propertyids, $floorplans, HOUR_IN_SECONDS );
    
    return $floorplans;
    
}

function entrata_api_request_two( $user, $password, $propertyids ) {
    
    // $floorplans2_data = get_transient( 'entrata_floorplans_2_' . $user . '_' . $password . '_' . $propertyids );
    
    if ( $floorplans2_data != false )
        return $floorplans2_data;
    
    
    /////////////////////////////////////////
    // REQUEST 2: Get the availability URL //
    /////////////////////////////////////////
    
    //* If everything's there, then prepare the request
    $jsonRequest2 = sprintf( '
    {
        "auth": {
            "type" : "basic"
        },
        "requestId" : 15,
        "method": {
            "name": "getMitsPropertyUnits",
            "params": {
                "propertyIds": "%s",
                "availableUnitsOnly": "0",
                "usePropertyPreferences": "1",
                "includeDisabledFloorplans": "1",
                "includeDisabledUnits": "0",
                "showUnitSpaces": 0
            }
        }
    }',
    $propertyids
    );
    
    //* Set up the auth part of the request
    $auth = $user . ':' . $password;
    $auth = base64_encode( $auth );
        
    //* Start the request
    $resCurl2 = curl_init();
    
    curl_setopt( $resCurl2, CURLOPT_HTTPHEADER,  array( 'Content-type: APPLICATION/JSON; CHARSET=UTF-8', 'Authorization: Basic ' . $auth ) );
    curl_setopt( $resCurl2, CURLOPT_POSTFIELDS, $jsonRequest2 );
    curl_setopt( $resCurl2, CURLOPT_POST, true );
    curl_setopt( $resCurl2, CURLOPT_URL, 'https://cardinal.entrata.com/api/v1/propertyunits' );
    curl_setopt( $resCurl2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $resCurl2, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
    
    $result2 = curl_exec( $resCurl2 );
    
    if( false === $result2 ) {
        echo 'Curl error: ' . curl_error( $resCurl2 );
        curl_close( $resCurl2 );
    } else {
        curl_close( $resCurl2 );
        $floorplans2 = ( json_decode( $result2 ) );            
    }
    
    // echo '<pre style="font-size: 13px;">';
    //     print_r( $floorplans2 );
    // echo '</pre>';
    
    //* This variable contains all of the information needed
    $floorplans2 = $floorplans2->response->result->PhysicalProperty->Property[0]->Floorplan;
    // $floorplans2_data = array();
    
    foreach ( $floorplans2 as $floorplan2 ) {
        // $floorplans2_data[] = array( $floorplan2->Name, $floorplan2->FloorplanAvailabilityURL );
        $floorplans2_data[$floorplan2->Name] = $floorplan2->FloorplanAvailabilityURL;
    }
    
    set_transient( 'entrata_floorplans_2_' . $user . '_' . $password . '_' . $propertyids, $floorplans2_data, HOUR_IN_SECONDS );
    
    return $floorplans2_data;
}

