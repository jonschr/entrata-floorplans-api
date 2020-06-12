<?php

//* Output default before
// add_action( 'before_loop_layout_default', 'rb_default_before' );
function rb_default_before( $floorplan, $args ) {
	// wp_enqueue_script( 'SCRIPTHANDLE' );
}

//* Output each default
add_action( 'add_loop_layout_default', 'rb_default_each', 10, 2 );
function rb_default_each( $floorplan, $args ) {
    
    // echo '<pre style="font-size: 12px;">';
    // print_r( $floorplan );
    // echo '</pre>';
    
    //* Get the information
    $Name = $floorplan->Name;
    $UnitCount = $floorplan->UnitCount;
    $UnitsAvailable = $floorplan->UnitsAvailable;
    $Room = $floorplan->Room; // this one has a bunch of information
        $beds = $Room[0]->Count;
        $baths = $Room[1]->Count;
        
        if ( !$beds )
            $beds = 0;
            
        if ( !$baths )
            $baths = 0;
        
    $SquareFeet = $floorplan->SquareFeet;
        $sqrftmin = $SquareFeet->{'@attributes'}->Min;
        $sqrftmax = $SquareFeet->{'@attributes'}->Max;
        
    $MarketRent = $floorplan->MarketRent;
        $rentmin = $MarketRent->{'@attributes'}->Min;
        $rentmax = $MarketRent->{'@attributes'}->Max;
    
    $File = $floorplan->File['0']->Src;
    
    //* Classes
    $classes = array();
    $classes[] = 'floorplan';
    
    // Avaailable classes
    if ( $UnitsAvailable > 0 ) {
        $classes[] = 'units-available';
    } else {
        $classes[] = 'no-units-available';
    }
    
    // Beds/baths classes
    if ( $beds )
        $classes[] = 'beds-' . $beds;
        
    if ( $baths )
        $classes[] = 'baths-' . $baths;
        
    //* Turns 'classes' back into a string
    $classes = implode( ' ', $classes );
    
    printf( '<div class="%s">', $classes );
    
        if ( $File )
            printf( '<a href="" class="featured" style="background-image:url(%s);"></a>', $File );
            
        if ( $Name )
            printf( '<h3>%s</h3>', $Name );
            
        echo '<div class="the-content">';
        
            if ( $beds )
                printf( '<div class="items"><span class="label beds-label">Bedrooms</span><span class="item beds-item">%s</span></div>', $beds );
                
            if ( $baths )
                printf( '<div class="items"><span class="label baths-label">Bathrooms</span><span class="item baths-item">%s</span></div>', $baths );
                                            
            if ( $MarketRent )
                printf( '<div class="items"><span class="label rent-label">Rent</span><span class="item rent-item">%s-%s</span></div>', $rentmin, $rentmax );
                
            if ( $SquareFeet )
                printf( '<div class="items"><span class="label sqrft-label">Square Feet</span><span class="item sqrft-item">%s-%s</span></div>', $sqrftmin, $sqrftmax );
            
        echo '</div>';
                    
    echo '</div>';
    
}