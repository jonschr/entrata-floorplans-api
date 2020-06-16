<?php

//* Register the shortcode
function entrata_floorplans_shortcode( $atts ) {
    $args = shortcode_atts( array(
        'username' => null,
        'password' => null,
        'requesturl' => 'https://cardinal.entrata.com/api/v1/properties',
        'propertyid' => null,
        'layout' => 'default',
        'columns' => 3,
        'leaseurl' => null,
        'filters' => null,
        'limit' => 999,
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
    $args['propertyid']
    );
    
    //* Set up the auth part of the request
    $auth = $args['username'] . ':' . $args['password'];
    $auth = base64_encode( $auth );
        
    //* Start the request
    $resCurl = curl_init();
    
    curl_setopt( $resCurl, CURLOPT_HTTPHEADER,  array( 'Content-type: APPLICATION/JSON; CHARSET=UTF-8', 'Authorization: Basic ' . $auth ) );
    curl_setopt( $resCurl, CURLOPT_POSTFIELDS, $jsonRequest );
    curl_setopt( $resCurl, CURLOPT_POST, true );
    curl_setopt( $resCurl, CURLOPT_URL, $args['requesturl'] );
    curl_setopt( $resCurl, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec( $resCurl );
    
    if( false === $result ) {
        echo 'Curl error: ' . curl_error( $resCurl );
        curl_close( $resCurl );
    } else {
        curl_close( $resCurl );
        $floorplans = ( json_decode( $result ) );            
    }
    
    //* This variable contains all of the information needed
    $floorplans = $floorplans->response->result->FloorPlans->FloorPlan;
    
    if ( $args['filters'] )
        do_action( 'entrata_filters', $floorplans, $args );
    
    //* Set up the outer markup for the layout
    printf( '<div class="floorplans layout-%s columns-%s">', $args['layout'], $args['columns'] );
    
    $count = 1;
    $limit = $args['limit'];
    
        foreach( $floorplans as $floorplan ) {
            do_action( 'before_loop_layout_' . $args['layout'], $floorplan, $args );
            do_action( 'add_loop_layout_' . $args['layout'], $floorplan, $args );
            do_action( 'after_loop_layout_' . $args['layout'], $floorplan, $args );
            
            $count++;
            
            if ( $count > $limit )
                break;
        }
    
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode( 'entrata', 'entrata_floorplans_shortcode' );
