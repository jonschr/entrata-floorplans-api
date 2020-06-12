<?php 

add_action( 'property_id_fallback', 'entrata_get_all_ids' );
function entrata_get_all_ids( $args ) {
    
    //* If everything's there, then prepare the request
    $jsonRequest = sprintf( '
    {
        "auth": {
            "type": "basic"
        },
        "requestId": "15",
        "method": {
            "name": "getProperties",
            "params": {
                "showAllStatus": "1"
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
        $properties = ( json_decode( $result ) );            
    }
    
    $properties = $properties->response->result->PhysicalProperty->Property;
    
    // echo '<pre>';
    // print_r( $properties );
    // echo '</pre>';
    
    echo '<table>';
    
    echo '<tr>';
        echo '<th><strong>Property name and link</strong></th>';
        echo '<th><strong>Email</strong></th>';
        echo '<th><strong>PropertyID</strong></th>';
        echo '<th><strong>Property lookup code</strong></th>';
    echo '</tr>';
    
    foreach( $properties as $property ) {
        printf( '<tr><td><a href="%s" target="_blank">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>', 
            $property->webSite . '/Apartments/module/application_authentication/' , 
            $property->MarketingName, 
            $property->Address->Email, 
            $property->PropertyID, 
            $property->PropertyLookupCode 
        );
    }
    
    echo '</table>';
        
    
}