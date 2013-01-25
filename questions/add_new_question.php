<?php
// Use this page to add a new question to the database. Takes a "POST" request.
    echo "About to include 'questions_functions.php' <br />";
    require_once 'questions_functions.php';
    echo "About to write functions of 'add_new_question.php' <br />";

    function create_question($connection, $database_name, $questions_args_table_name, $questions_table_name){
       // //requires POST request
       echo "Entering 'create_question' function <br />";
       
        
       // $current_User = get_Current_User($connection);
       // $current_Class = get_Current_Class($connection);
       
        $current_User = 'testusr';
        $current_Class = 1234;
        
       
        
        if($current_User === ''){
            echo 'Not Logged In';
            return;
        };
       
         
        check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name);
        
        $mysql_find_questionID_query = "SELECT questionID FROM $questions_table_name ORDER BY questionID DESC LIMIT 1";
        $old_questionID_resource = mysql_query($mysql_find_questionID_query) or die(mysql_error());
        $old_questionID = mysql_fetch_assoc($old_questionID_resource)[questionID];
        echo "old_questionID: $old_questionID <br />";
        $questionID = $old_questionID + 1;
        echo "new questionID: $questionID <br />";        
        
        $mysql_find_activeNum_query = "SELECT activeNum FROM $questions_table_name ORDER BY activeNum DESC LIMIT 1";
        $old_activeNum_resource = mysql_query($mysql_find_activeNum_query) or die(mysql_error());
        $old_activeNum = mysql_fetch_assoc($old_activeNum_resource)[activeNum];
        echo "old_activeNum: $old_activeNum <br />";
        //$activeNum = $old_activeNum + 1;
        $activeNum = 0;         //for inactive questions
        
        
        
        
        $question_request = parse_question_request($questionID, $questions_args_table_name, $connection);
        
        $prompt             = $question_request['prompt'];
        $question_type      = $question_request['question_type'];
            
        $arg_array          = $question_request['arg_array'];
        $arg_correct_array  = $question_request['arg_correct_array'];
        $arg_label_array    = $question_request['arg_label_array'];
        
        echo "Prompt: $prompt <br />";
        echo "question_type: $question_type <br />";
        //echo "Prompt: $prompt <br />";
        
        
          //create question in $questions_table_name
        mysql_query("INSERT INTO $questions_table_name (questionID, classID, prompt, questionType, activeNum) VALUES ('$questionID', '$current_Class', '$prompt', '$question_type', '$activeNum')", $connection) or die(mysql_error());
       
       echo "Count of 'arg_array in check_Questions_Table: " . count($arg_array) . '<br />'; 
        
        for($i = 0; $i < count($arg_array); $i++){
              
                mysql_query("INSERT INTO $questions_args_table_name (questionID, argNum, arg, correct, label) VALUES ('$questionID', '$i', '$arg_array[$i]', '$arg_correct_array[$i]', '$arg_label_array[$i]')", $connection) or die(mysql_error());
                
        }
        
        
    }
    
    function parse_question_request($Question_ID, $questions_args_table_name, $connection){
        //Anticipates POST request of arbitrary number of Arguments.
        
        echo "entering 'parse_question_request' <br />";
        
        $arg_array          = array();
        $arg_correct_array  = array();
        $arg_label_array    = array();
        
        echo "Test arg0:" . $_POST["Arg0"] .  "<br />";
        echo "Test arg1:" . $_POST["Arg1"] .  "<br />";
        echo "Test arg2:" . $_POST["Arg2"] .  "<br />";
        
        for( $i = 0; isset($_POST['Arg' . ($i)]); $i++) {
            echo "Entering the Arg for-loop in 'parse_question_request' <br />";
            echo 'Arg'          . $i . ' <br />';
        
            $arg            = $_POST['Arg'          . $i];
            echo "Arg: $arg <br />";
            $arg_correct    = $_POST['Arg_Correct'  . $i];
            $arg_label      = $_POST['Arg_Label'    . $i];
        
            array_push($arg_array,           $arg);
            //echo "Still going after 1st push <br />";
            array_push($arg_correct_array,   $arg_correct);
            //echo "Still going after correct push <br />";
            array_push($arg_label_array,     $arg_label);
            //echo "Still going after label push <br />";
        }
        
        
            
        $prompt         = $_POST['prompt'];
        $question_type  = $_POST['question_type'];
        
           
          
        $question_request = array(  'prompt'            => $prompt,
                                    'question_type'     => $question_type,
                                    'arg_array'         => $arg_array,
                                    'arg_correct_array' => $arg_correct_array,
                                    'arg_label_array'   => $arg_label_array,
        );
            
            return $question_request;
          
    }
    
    ///*******************************Now run code*********************************/
    
    echo "About to start main function of 'add_new_question.php' <br />";
    
    create_question($connection, $database_name, $questions_args_table_name, $questions_table_name);
    
    $redirect = TRUE;
    
    if($redirect === TRUE){
        
        header('Location:' . $_SERVER['HTTP_REFERER']);
        
    };


?>