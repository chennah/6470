<?php
    //if (is_file('./../login/login_HIDDEN.php'))
    //    {
    //        echo "'is file' appeared to work <br />";
    //     //   include('login_HIDDEN.php');
    //    }
    //else
    //    {
    //        echo "'is file' did not work <br />";     
    //    }
    //
    //echo "About to require 'login_HIDDEN.php' <br />";
    require_once './../login/login_HIDDEN.php';
    
    //echo "About to require 'questions_HIDDEN.php' <br />";
    require_once 'questions_HIDDEN.php';    //includes SQL login information

    
    
    //echo "About to require 'login_functions.php' <br />";
    require_once '../login/login_functions.php';
    
    //echo "About to start writing functions for questions_functions.php <br />";
    
    //if (is_file('./../login/login_functions.php'))
    //    {
    //        echo "'is file' appeared to work <br />";
    //        include('./../login/login_functions.php');
    //    }
    //else
    //    {
    //        echo "'is file' did not work <br />";
    //
    //        
    //    }
    
    
            /////* INFORMATION ABOUT TABLES */////
    $questions_responses_table_name = 'questions_responses';
    
    
    
    /////******************* FUNCTIONS *****************/////

    function check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name, $questions_answers_table_name, $questions_responses_table_name) {
        /* Checks if login table exists. If login table does not exist, it is created.
        */
        
        $create_questions_table_command = "CREATE TABLE $questions_table_name
                            (
                            questionID      INT,
                            classID         INT,
                            prompt          varchar(1000),
                            questionType    varchar(100),
                            activeNum       INT
                            )";
                            // REQUIRES mySQL 5.0.3+ for varchar longer than 255 characters
        
        $create_questions_args_table_command = "CREATE TABLE $questions_args_table_name
                            (
                            questionID      INT,
                            classID         INT,
                            argNum          INT,
                            arg             varchar(255),
                            )";
                            
        $create_questions_answers_table_command = "CREATE TABLE $questions_answers_table_name
                   (
                   questionID       INT,
                   classID          INT,
                   answerNum        INT,
                   answer           varchar(255),
                   correct          INT,
                   label            varchar(255)
                   )";
                   
        $create_questions_responses_table_command = "CREATE TABLE $questions_responses_table_name
                   (
                   questionID       INT,
                   classID          INT,
                   username         varchar(255),
                   response         varchar(255),
                   responseNum      INT
                   )"; 
                   
        
        //echo "Can I connect to the database? : $database_name <br /> ";
        
        mysql_select_db($database_name, $connection) or die(mysql_error());
        
        //echo 'Database connection successful! <br />';    
          
        //echo "About to check if tables exist (check_Question_Tables) <br />";
           
        $table_exist = mysql_query("SELECT 1 from $questions_table_name");
        if($table_exist !== FALSE)
        {
            //echo "Table: '$questions_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$questions_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_table_command, $connection) or die(mysql_error()); 
        }
        
        
        $table_exist = mysql_query("SELECT 1 from $questions_args_table_name");
        if($table_exist !== FALSE)
        {
            //echo "Table: '$questions_args_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$questions_args_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_args_table_command, $connection) or die(mysql_error()); 
        }
        
        $table_exist = mysql_query("SELECT 1 from $questions_answers_table_name");
        if($table_exist !== FALSE)
        {
            //echo "Table: '$questions_answers_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$questions_answers_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_answers_table_command, $connection) or die(mysql_error()); 
        }
        
        $table_exist = mysql_query("SELECT 1 from $questions_responses_table_name");
        if($table_exist !== FALSE)
        {
            //echo "Table: '$questions_responses_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$questions_responses_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_responses_table_command, $connection) or die(mysql_error()); 
        }   
        
    };
    
    
    function get_active_question_questionID($connection, $questions_table_name, $classID ){

        
        $mysql_find_questionID_query = "SELECT questionID FROM $questions_table_name WHERE classID='$classID' AND activeNum<>'0' ORDER BY activeNum ASC LIMIT 1";
        //Treat activeNum questions as a Queue -- pop from lowest index. Inactive questions should be treated as activeNum=0
        
        
        $questionID_resource = mysql_query($mysql_find_questionID_query) or die(mysql_error());   
        $questionID = mysql_fetch_assoc($questionID_resource)[questionID];

        return $questionID;
        
    };
    
    function set_question_inactive($connection, $questions_table_name, $classID, $questionID){
        
        $set_question_inactive_query = "UPDATE $questions_table_name SET activeNum='0' WHERE questionID='$questionID' AND classID='$classID'";
        
        mysql_query($set_question_inactive_query, $connection) or die(mysql_error());   
        
    };
    
    
    
    function get_question_info($connection, $questions_table_name, $questions_args_table_name, $questions_answers_table_name, $classID, $questionID ){
        
        $mysql_find_prompt_query = "SELECT prompt FROM $questions_table_name WHERE questionID='$questionID' AND classID='$classID'";
        //echo         $mysql_find_prompt_query . '<br />';
        $prompt_resource = mysql_query($mysql_find_prompt_query, $connection) or die(mysql_error());
        $prompt= mysql_fetch_array($prompt_resource)['prompt'];

        //echo "prompt (inside get_question_info): $prompt <br />";
        
        $mysql_find_question_type_query = "SELECT questionType FROM $questions_table_name WHERE questionID='$questionID' AND classID='$classID'";
        //echo         $mysql_find_question_type_query . '<br />';
        $question_type_resource = mysql_query($mysql_find_question_type_query, $connection) or die(mysql_error());
        $question_type= mysql_fetch_array($question_type_resource)['questionType'];
        
        //echo "questionType (inside get_question_info): $questionType <br />";
        
        //Parse answer information 
        $mysql_find_answers_query = "SELECT * FROM $questions_answers_table_name WHERE questionID='$questionID' AND classID='$classID'";
        //echo         $mysql_find_answers_query . '<br />';
        $answers_resource = mysql_query($mysql_find_answers_query, $connection) or die(mysql_error());
        
        $answer_array = array();
        $answer_label_array = array();
        $answer_correct_array = array();
        
        
        while($row = mysql_fetch_array($answers_resource)){
            array_push($answer_array, $row['answer']);
            array_push($answer_label_array, $row['label']);
            array_push($answer_correct_array, $row['correct']);
           
        }
        
        
        //Parse argument information 
        $mysql_find_args_query = "SELECT * FROM $questions_args_table_name WHERE questionID='$questionID' AND classID='$classID' ";
        //echo         $mysql_find_args_query . '<br />';
        $args_resource = mysql_query($mysql_find_args_query, $connection) or die(mysql_error());
        
        $arg_array= array();
        
        
        while($row = mysql_fetch_array($args_resource)){
            array_push($arg_array, $row['arg']);
        }
        
        
        $question_info    = array(  'prompt'                => $prompt,
                                    'question_type'         => $question_type,
                                    'arg_array'             => $arg_array,
                                    'answer_array'          => $answer_array,
                                    'answer_correct_array'  => $answer_correct_array,
                                    'answer_label_array'    => $answer_label_array,
        );
        
        return $question_info;

        
    }
    
    function page_loaded_when(){
        // returns a string, formatted like: '28 Jan 2013 05:10:12 PM'
        // See php strftime() documentation for more formats
        $time_format = '%e %b %Y %r';
        $time        = strftime($time_format);
        
        return $time;
    };
    
    function get_responses($connection, $questions_responses_table_name, $classID, $questionID, $question_type){
        
        //echo "entering: get_responses <br />";
        
        $responses_array = array();
                
        $response_query = "SELECT * FROM $questions_responses_table_name WHERE classID='$classID' AND questionID='$questionID'";
        $responses_result = mysql_query($response_query, $connection) or die(mysql_error());
        //echo $response_query . '<br />';
        
        if($question_type ==='multiplechoice'){
            //echo "Inside 'multiplechoice' block of get_responses <br />";
            
            while($row = mysql_fetch_array($responses_result))
              {
                //echo "Inside while-loop of get_responses (MC) <br />";
                
              $username  = $row['username'];
              $response  = $row['responseNum'];
              
            
            
            $tmp_array = array('username'   => $username,
                               'response'   => $response,
                               );
            
            //print_r($tmp_array);
            
            array_push($responses_array, $tmp_array); 
                
              }
        }
        elseif($question_type ==='freeresponse'){
            
            
            while($row = mysql_fetch_array($responses_result))
              {
              $username  = $row['username'];
              $response  = $row['response'];
              
            
            
            $tmp_array = array('username'   => $username,
                               'response'   => $response,
                               );
            
            //print_r($tmp_array);
            
            array_push($responses_array, $tmp_array);
              }
            
        }
        
        
        
        
        
        return $responses_array;
    }
    
        /////******************* THESE FUNCTIONS RUN EVERY TIME *****************/////
    
    check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name, $questions_answers_table_name, $questions_responses_table_name);

?>