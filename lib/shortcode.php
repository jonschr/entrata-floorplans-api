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
    ), $atts );

    ob_start();
    
    //* Error handling: tell the user which parameter is missing if needed
    if ( !$args['propertyid'] )
        return 'Missing parameter: propertyid. You can find the id right here: https://cardinal.entrata.com/api/v1/documentation/getProperties';
        
    if ( !$args['username'] )
        return 'Missing parameter: username';
        
    if ( !$args['password'] )
        return 'Missing parameter: password';
    
        
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
    
    //* Set up the outer markup for the layout
    printf( '<div class="floorplans layout-%s columns-%s">', $args['layout'], $args['columns'] );
    
        foreach( $floorplans as $floorplan ) {
            
            do_action( 'before_loop_layout_' . $args['layout'], $floorplan, $args );
            do_action( 'add_loop_layout_' . $args['layout'], $floorplan, $args );
            do_action( 'after_loop_layout_' . $args['layout'], $floorplan, $args );
            
        }
    
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode( 'floorplans', 'entrata_floorplans_shortcode' );
