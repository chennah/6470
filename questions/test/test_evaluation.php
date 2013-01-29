<?php

    ///****************************** RUN TEST ********************************///
    require '../questions_evaluate.php';
    
    $arg_array[] = 'x';
    $arg_array[] = 'y';
    $arg_array[] = 'z';
    
    $expression = '3x + 2y + z^2';
    //$expression = '3x + 2y^2';
    
    $test_math = create_math_function($arg_array, $expression);
    
    $lattice = numerically_evaluate_function($test_math, $arg_array);

    
    $lattice2 = $lattice;
    $lattice2[1][1][1] += 0.5;
    
    $val1_new =    $lattice[1][1][1];
    $val2_new =    $lattice2[1][1][1];
    
        $approx_equal_to = check_numerical_solutions($lattice, $lattice2);

    
    
    echo "$val1_new and $val2_new <br />";
    
    if($approx_equal_to){
        echo "Lattices are approximatey equal to each other! <br />";
    }
    else{
        echo "Lattices are NOT approximatey equal to each other! <br />";

        
    }
    
    
    
?>
    