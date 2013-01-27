<?php
    // Expects POST request. Parses, adds to mySQL table, then returns to previous page.
    
    require_once 'questions_functions.php';
    
    function parse_and_add_response_request($connection, $questions_responses_table_name){
        
        $questionID  = 0;
        $username    = '';
        $response    = '';
        $responseNum = '';
        
        if(isset($_POST['questionID'])){
            
            $questionID = $_POST['questionID'];
            
        }
        else{
            
        }
        
        $tmpUser = get_current_username($connection);
        if($tmpUser!==''){
            
            $username = $tmpUser;
            
        }
        else{
            
        }
        
        $question_type = $_POST['question_type'];
        
        if($question_type==='multiplechoice'){
            
            $responseNum = $_POST['Answer_Correct'];
            
        }
        elseif($question_type==='freeresponse'){
            
            $response   = $_POST['freeresponse_answer'];
            
        }
        else{
            
            echo "Invalid question type from hidden field. Value: $question_type <br />";
            
        }
        
        
        
        $response_info    = array(  'questionID'            => $questionID,
                                    'username'              => $username,
                                    'response'              => $response,
                                    'responseNum'           => $responseNum
        );
        
        //////Insert response -- overwrite previous responses from user for that question
            $submitted_responses_query = "SELECT * FROM $questions_responses_table_name WHERE username='$username' and questionID='$questionID'";
            echo "Check previous responses using query: $submitted_responses_query <br/>";
            
            $submitted_responses_resource = mysql_query($submitted_responses_query);
                
            if(is_resource($submitted_responses_resource) && mysql_num_rows($submitted_responses_resource) > 0 ){
                /////////*remove previous response(s)*/
                
                mysql_query("DELETE FROM $questions_responses_table_name WHERE username='$username' and questionID='$questionID'") or die(mysql_error());
                echo "Delete Previous Responses using: " . "DELETE FROM $questions_responses_table_name WHERE username='$username' and questionID='$questionID'" . "<br />";
                
            }
            else{    
                
            }
            
            
        mysql_query("INSERT INTO $questions_responses_table_name (questionID, username, response, responseNum) VALUES ('$questionID', '$username', '$response', '$responseNum')", $connection) or die(mysql_error());
        echo "Create response row using: " . "INSERT INTO $questions_responses_table_name (questionID, username, response, responseNum) VALUES ('$questionID', '$username', '$response', '$responseNum')" . "<br />";
        
        return $response_info;
        
    };


            ///****************** Run script ******************/// 



    $response   = parse_and_add_response_request($connection, $questions_responses_table_name);
    
    
    
    $redirect = TRUE;
    
    if($redirect === TRUE){
        
        setcookie("submit_success_message", "Success", time()+3600);
        
        header('Location:' . $_SERVER['HTTP_REFERER']);
        
    };


?>