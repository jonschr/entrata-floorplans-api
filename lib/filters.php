<?php

add_action( 'entrata_filters', 'entrata_do_the_filters', 10, 2 );
function entrata_do_the_filters( $floorplans, $args ) {
    
    //* Enqueue
    wp_enqueue_script( 'entrata-filters' );
    
    $filters = array();
    
    
    
    //* Figure out what values our query is getting
    foreach ( $floorplans as $floorplan ) {
        
        
        
        $beds = $floorplan->Room[0]->Count;
        $filters[] = $beds;
    }
    
    //* Only keep the unique values
    $filters = array_unique( $filters );
    
    //* Sort these so that if they came in the wrong order they get fixed
    sort( $filters );
        
    //* Output those as a list
    echo '<ul class="floorplan-filters">';
    
        printf( '<li><a class="active filter-select" href="#" data-filter="%s">%s</a></li>', 'floorplan', 'All' );
        
        foreach ( $filters as $filter ) {
            
            if ( $filter == 0 )
                $label = 'Studio';
                
            if ( $filter == 1 )
                $label = 'One Bedroom';
                
            if ( $filter == 2 )
                $label = 'Two Bedroom';
                
            if ( $filter == 3 )
                $label = 'Three Bedroom';
            
            if ( $filter == 4 )
                $label = 'Four Bedroom';
                
            if ( $filter == 5 )
                $label = 'Five Bedroom';
                        
            printf( '<li><a href="#" class="filter-select" data-filter="beds-%s">%s</a></li>', $filter, $label );
            
        }
        
    echo '</ul>';
    
}