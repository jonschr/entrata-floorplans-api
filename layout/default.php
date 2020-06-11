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
    
    $Name = $floorplan->Name;
    $UnitCount = $floorplan->UnitCount;
    $UnitsAvailable = $floorplan->UnitsAvailable;
    $Room = $floorplan->Room; // this one has a bunch of information
    $SquareFeet = $floorplan->SquareFeet;
    $MarketRent = $floorplan->MarketRent;
    $File = $floorplan->File['0']->Src;
    
    echo '<div class="floorplan">';
    
        if ( $File )
            printf( '<a href="" class="featured" style="background-image:url(%s);"></a>', $File );
            
        if ( $Name )
            printf( '<h3>%s</h3>', $Name );
        
    echo '</div>';
    
}