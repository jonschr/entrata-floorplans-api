<?php

//* Output default before
// add_action( 'do_before_loop_layout_default', 'entrata_floorplans_default_before' );
function entrata_floorplans_default_before( $floorplan, $args ) {
	// wp_enqueue_script( 'SCRIPTHANDLE' );
}

//* Output each default
add_action( 'do_loop_layout_default', 'entrata_floorplans_default_each', 10, 2 );
function entrata_floorplans_default_each( $floorplan, $args ) {
    
    // echo '<pre style="font-size: 12px;">';
    // print_r( $floorplan );
    // echo '</pre>';
    $rand = rand(1, 1000000 );
    
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
    $leaseurl = $args['leaseurl'];
    
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
    $classes[] = 'beds-' . $beds;        
    $classes[] = 'baths-' . $baths;
        
    //* Turns 'classes' back into a string
    $classes = implode( ' ', $classes );
    
    printf( '<div class="%s">', $classes );
    
        if ( $File )
            printf( '<a  href="%s" data-fancybox="gallery-image" class="featured" data-caption="%s" style="background-image:url(%s);"></a>', $File, $Name, $File );
            
        if ( $Name )
            printf( '<h3>%s</h3>', $Name );
            
        echo '<div class="the-content">';
        
            if ( $beds || $beds == 0  )
                printf( '<div class="items"><span class="label beds-label">Bedrooms</span><span class="item beds-item">%s</span></div>', $beds );
                
            if ( $baths || $baths == 0 )
                printf( '<div class="items"><span class="label baths-label">Bathrooms</span><span class="item baths-item">%s</span></div>', $baths );
                                            
            if ( $MarketRent ) {
                if ( $rentmin != $rentmax ) 
                    printf( '<div class="items"><span class="label rent-label">Rent</span><span class="item rent-item">$%s-%s</span></div>', $rentmin, $rentmax );
                    
                if ( $rentmin == $rentmax ) 
                    printf( '<div class="items"><span class="label rent-label">Rent</span><span class="item rent-item">$%s</span></div>', $rentmin );
            }
                
            if ( $SquareFeet ) {
                
                if ( $sqrftmin != $sqrftmax )
                    printf( '<div class="items"><span class="label sqrft-label">Square Feet</span><span class="item sqrft-item">%s-%s</span></div>', $sqrftmin, $sqrftmax );
                    
                if ( $sqrftmin == $sqrftmax )
                    printf( '<div class="items"><span class="label sqrft-label">Square Feet</span><span class="item sqrft-item">%s</span></div>', $sqrftmin );
            }
            
        echo '</div>';
        
        echo '<div class="cta-wrap">';
            
            if ( $File )
                printf( '<a data-fancybox="gallery-button" data-caption="%s" class="button" href="%s">View</a>', $Name, $File );
                
            if ( $leaseurl )
                printf( '<a href="%s" class="button">Lease now</a>', $leaseurl );
            
        echo '</div>';
                    
    echo '</div>';
    
}