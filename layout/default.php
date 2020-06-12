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
        
    $SquareFeet = $floorplan->SquareFeet;
    $MarketRent = $floorplan->MarketRent;
    
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
                    
    echo '</div>';
    
}