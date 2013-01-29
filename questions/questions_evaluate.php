<?php

    require_once 'questions_functions.php';
    
    $test = FALSE;
    
    
    function create_math_function($arg_array, $expression){
        //Takes in LHS (array of variable names [strs]) and RHS of equation (string),
        //Returns EvalMath object, $math
        
        $arg_str = arg_array_to_str($arg_array);
        $numArg  = count($arg_array);
        
        require_once 'EvalMath/evalmath.class.php'; 
        
        $math = new EvalMath;
	$math->suppress_errors = false;
        $input = "f($arg_str) = $expression";
	if ($math->evaluate($input)) {         //eg 'f(x, y, z) = x^2 + y^2 + 2*z^2'
	} else {
		print "\t<p>Could not create function: " . $math->last_error . "</p>\n";
	}
        
        return $math;
    }
    
    function arg_array_to_str($arg_array){
        //Converts array to comma seperated string
        
        $arg_str = '';
        
        foreach($arg_array as $arg)
        {
            $arg_str .= "$arg,";
        }
        
        
        
        return substr($arg_str,0,-1);    //eg 'x,y,z' -- substr() cuts off the trailing comma
    }
    
    function check_approx_equal_to($num1, $num2, $tolerance=0.1){
        //check if they are the same, within $tolerance
        //Wide default tolerance due to possible user-side rounding choices
        
        
        $equal = FALSE;
        
        //echo "check_approx_equal_to values: $num1 and $num2 <br />";
        
        //Ensure that we have floating point numbers
        $float1 = floatval($num1);
        $float2 = floatval($num2);
        
        $diff = abs($float1 - $float2);
        $avg_mag = abs(($float1 + $float2) / 2.0);
        
        
        
        if($diff > (.1 * $avg_mag)){
            $equal = FALSE;
        }
        else{
            $equal = TRUE;
        }
        
        return $equal;
    }
    
    function numerically_evaluate_function($math, $arg_array, $min = 1, $max = 3, $interval = 1){
        // numerically evaluates a certain function (stored in $math) over space of $numArg dimensions, of independent axes. Samples at lattice points determined by $min, $max, $interval (even spacing for all dimensions)
        // negatives and zeros ignored for simplicity when dealing with natural logs and divide-by-zero errors. Not perfect, but it's a prototype
        

        $numSteps = (($max - $min) / $interval) + 1;
        // you must check on your own that 'min' will in fact increment to 'max' properly
        // there is NO sanitization or sanity checks on the lattices that are built at different times. Please make sure lattice points are consistent. (this is something to improve with more time)
        
        $numArg = count($arg_array);
        
        $arg_array_numerical = array();
        
        for($i = 0; $i < $numArg; $i++){
            $arg_array_numerical[] = $min;            
        }
        
        $results_array_numerical = array_fill($min, $numSteps, 0); // initialize, each argument should have a dimension of $numStep size with lattice points for the numerical evaluation. Value at all lattice points set to zero
        for($i = 1; $i < $numArg; $i++){
            $results_array_numerical = array_fill($min, $numSteps, $results_array_numerical);            
        }
        
        //echo "<hr> <br /><br />"; print_r($results_array_numerical); echo "<hr> <br /><br />";
        
        
        $keep_going = TRUE;
        while($keep_going){
            
            $arg_str_numerical = arg_array_to_str($arg_array_numerical);
            
            //echo "<br /> test arg_str_numerical: $arg_str_numerical <br />";
            
            $value  = $math->evaluate("f($arg_str_numerical)");
            //echo "value in while loop of numerically_evaluate: $value <br />";
            
            $results_array_numerical = set_arbitrary_dim_array_element($results_array_numerical, $arg_array_numerical, $value);
            
            
            for($arg_num = 0; $arg_num < $numArg; $arg_num++){
                
                //echo "print_r(arg_array) in for loop: "; print_r($arg_array_numerical); echo "<br />";
                
                if( $arg_array_numerical[$arg_num] < $max ){
                    //increment that index
                    $arg_array_numerical[$arg_num] += $interval;
                    //echo "Simple increment <br />";
                    break;     
                }
                elseif($arg_array_numerical[$arg_num] >= $max AND $arg_num >= $numArg-1){
                    //all indices are at their maximum. Entire phase space has been sampled
                    $keep_going = FALSE;
                    //echo "finished incrementing <br />";
                    break;
                }
                elseif($arg_array_numerical[$arg_num] >= $max AND $arg_num < $numArg-1){
                        //  phase space for that variable has been sampled. Start back at $min, and increment next highest index (done in next loop iteration)
                       $arg_array_numerical[$arg_num] = $min;
                       //echo "reset and continue to next  <br />";
                }
                    
            }
            
            //echo "keep_going at end of while loop: $keep_going <br /> <br />";
        }
        
        //echo "results_array_numerical when finished: "; print_r($results_array_numerical); echo "<br />";
        return $results_array_numerical;
    }
    
    function set_arbitrary_dim_array_element($multi_array, $index_array, $value){
        //multi_array should be the arbitrary-dimensional array that you want to edit an entry;
        //index_array should be an array of the indexs for the array dimensions (this should be a 1-dim array, eg. index[0] = 1, index[1] = 3, index[2] = 2 ...)
        //pop from left side of array
        
        //echo "print_r of index_array in set_arbitrary_dim_array_element: "; print_r($index_array); echo "<br />";
        
        $numIndices = count($index_array);
        
        //Recursive structure:
        if($numIndices == 1){
            
            $index = $index_array[0];
            
            $multi_array[$index] = $value;            
        }
        else{   
            
            $left_most_index = $index_array[0];
            
            $tmp_array = $multi_array[$left_most_index];
            $tmp_array = set_arbitrary_dim_array_element($tmp_array, array_slice($index_array, 1), $value);
            $multi_array[$left_most_index] = $tmp_array;
        }
        
        return $multi_array;

    }
    
    function get_arbitrary_dim_array_element($multi_array, $index_array){
        
        $numIndices = count($index_array);
        
         $value = NULL;
         
        //Recursive structure:
        if($numIndices == 1){
            $index = $index_array[0];
            $value = $multi_array[$index];
        }
        else{   
            
            $left_most_index = $index_array[0];
            
            $tmp_array = $multi_array[$left_most_index];
            $value = get_arbitrary_dim_array_element($tmp_array, array_slice($index_array, 1));
        }
        
        return $value;

    }
    
    function check_numerical_solutions($numerical_array1, $numerical_array2, $min = 1, $max = 3, $interval = 1){
        //checks each lattice point of both arrays. If ANY point yields a substantially different answer, then solutions are determined as non-equivalent.       
             
        $numArg = NULL;
        if(count($numerical_array1) == count($numerical_array2)){
            $numArg = count($numerical_array1);
            
        }
        else{
            echo "Lattice sizes do not match up! <br />";
            return FALSE;       // lattices do not even appear to match sizes.
        }
        
        
        $numSteps = (($max - $min) / $interval) + 1;
        // you must check on your own that 'min' will in fact increment to 'max' properly
                
        $arg_array_numerical = array();
        
        for($i = 0; $i < $numArg; $i++){
            $arg_array_numerical[] = $min;            
        }
            
        
        $keep_going = TRUE;
        while($keep_going){
            
            $arg_str_numerical = arg_array_to_str($arg_array_numerical);
            
            //echo "<br /> test arg_str_numerical: $arg_str_numerical <br />";
            
            $value1 = get_arbitrary_dim_array_element($numerical_array1, $arg_array_numerical);
            $value2 = get_arbitrary_dim_array_element($numerical_array2, $arg_array_numerical);
            
            if(!check_approx_equal_to($value1, $value2)){
                echo " <hr /> <br /> Arrays do not appear to be equal. Values do not match: $value1 and $value2 <br /> <hr />";
                $keep_going = FALSE;
                return FALSE;
            }
            
            for($arg_num = 0; $arg_num < $numArg; $arg_num++){
                
                //echo "print_r(arg_array) in for loop: "; print_r($arg_array_numerical); echo "<br />";
                
                if( $arg_array_numerical[$arg_num] < $max ){
                    //increment that index
                    $arg_array_numerical[$arg_num] += $interval;
                    //echo "Simple increment <br />";
                    break;     
                }
                elseif($arg_array_numerical[$arg_num] >= $max AND $arg_num >= $numArg-1){
                    //all indices are at their maximum. Entire phase space has been sampled
                    $keep_going = FALSE;
                    //echo "finished incrementing <br />";
                    break;
                }
                elseif($arg_array_numerical[$arg_num] >= $max AND $arg_num < $numArg-1){
                        //  phase space for that variable has been sampled. Start back at $min, and increment next highest index (done in next loop iteration)
                       $arg_array_numerical[$arg_num] = $min;
                       //echo "reset and continue to next  <br />";
                }
                    
            }
            
            //echo "keep_going at end of while loop: $keep_going <br /> <br />";
        }
        
        return TRUE;        //early-returns 'FALSE' if the lattice does not match
    }
    
    ///****************************** RUN TEST ********************************///
    
    if($test === TRUE){   
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
    }
    
    
    
    
    
    




?>