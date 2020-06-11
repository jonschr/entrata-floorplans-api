<?php

//* Declare the custom function for formatting
function pretty_print($json_data) {

    // Initialize variable for adding space
    $space = 0;
    $flag = false;

    // Using <pre> tag to format alignment and font
    echo '<pre style="font-size: 13px; max-width: 100%; overflow: scroll; background-color: #f7f7f7; padding: 10px;">';

        // loop for iterating the full json data
        for($counter=0; $counter<strlen($json_data); $counter++)
        {

        // Checking ending second and third brackets
        if ( $json_data[$counter] == '}' || $json_data[$counter] == ']' ) {
            $space--;
            echo "\n";
            echo str_repeat(' ', ($space*2));
        }
        
        // Checking for double quote(“) and comma (,)
        if ( $json_data[$counter] == '"' && ($json_data[$counter-1] == ',' || $json_data[$counter-2] == ',') ) {
            echo "\n";
            echo str_repeat(' ', ($space*2));
        }
        
        if ( $json_data[$counter] == '"' && !$flag ) {
            if ( $json_data[$counter-1] == ':' || $json_data[$counter-2] == ':' )
                // Add formatting for question and answer
                echo '<span style="color:blue;font-weight:bold">';                
            else
                // Add formatting for answer options
                echo '<span style="color:red;">';
            }
            
            echo $json_data[$counter];
                // Checking conditions for adding closing span tag
            
                if ( $json_data[$counter] == '"' && $flag )
                echo '</span>';

        if ( $json_data[$counter] == '"' )
            $flag = !$flag;

            // Checking starting second and third brackets
            if ( $json_data[$counter] == '{' || $json_data[$counter] == '[' ) {
                $space++;
                echo "\n";
                echo str_repeat(' ', ($space*2));
            }
        }
    echo "</pre>";
}