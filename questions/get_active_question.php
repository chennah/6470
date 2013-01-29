<?php
//Searches for first active question in a particular class, and then displays it to a student, with student input possible


    // Get the current class (default '1234' if not in get request)
    require_once 'questions_functions.php';
    
    
    function build_submit_success_message(){
        
        $message_raw  = '';
        $message_html = '';
        
        if(isset($_COOKIE["submit_success_message"])){
            
            $message_raw = $_COOKIE["submit_success_message"];
            
            $message_html = "<h4> Previous Submission Status: </h4>" . " $message_raw ";
            
            
            
            setcookie("submit_success_message", "", time()-3600);
            
            
        }
        
        return $message_html;
        
        
    }
    
    function get_html_form($prompt, $arg_array, $answer_array, $question_type, $questionID){
        
        ///**************Prepare HTML code if Free Response********************//


        $arg_list = '';
        
        for($i=0; $i < count($arg_array) ; $i++){
        
            $tmp = "<li> $arg_array[$i]  </li>";
            $arg_list .= $tmp;
            
        }       
        
        $Arguments = <<< EOF
        
            <div id='Arguments'>
                <h4> Available Arguments: </h4>
                        <ul>
                            $arg_list
                        </ul>
            </div>
               
EOF;
        
        
        
        $Free_Response_inner = <<< EOF
        
            $Arguments
        
            <div> <h4> Answer: </h4> <input type='text' name='freeresponse_answer' /> </div>
        
EOF;
        
        
        
            ///**************Prepare HTML code if Multiple Choice********************//
        
        $MC_Answers_list = '';  
            for($i=0; $i < count($answer_array) ; $i++){
            
            
                $tmp = "<div>  <input type='radio' name='Answer_Correct' value='$i' /> $answer_array[$i] </div>";
                $MC_Answers_list .= $tmp;
                                
            }        
        
        
        $MC_Answers_inner = <<< EOF
        
            <div id='MC_Answers'>
        
            <h4> Available Answers: </h4>
                    
                    $MC_Answers_list        
                
            </div>
                    
                    
EOF;
        

            ///****************Chose Free Response or Multiple Choice -- ignore the other*******************//
            
        $MC_Answers = '';
        $Free_Response = '';
        
        if($question_type ==='multiplechoice'){
        
            $MC_Answers = $MC_Answers_inner;
            
        }
        elseif($question_type ==='freeresponse'){
        
            $Free_Response = $Free_Response_inner;
        
        }
        
        
        $question_type_hidden   = "<input type='hidden' name='question_type' value='$question_type' />";
        $questionID_hidden      = "<input type='hidden' name='questionID' value='$questionID' />";

        $submit_message = build_submit_success_message();   
            
            //****************Wrap it all together*******************//
                    
        $html_temp = <<< EOF
            <html>
            <head>
                <title> Active Question </title>
            </head>
            
            <body>
                
                <h2> Answer the Active Question </h2>
                
                <h4>Prompt: </h4>
                <p> $prompt <p>
                
                <form action='submit_response.php' method='POST' >
                    
                    $question_type_hidden
                    
                    $questionID_hidden
                
                    $Free_Response
                
                    $MC_Answers
                    
                    <input type='submit' value='Submit Answer'/>
                    <input type='reset' />
                    
                </form>
                
                $submit_message
                
            </body>
            
        </html>
EOF;

    return $html_temp;
            
    }

    
    
    $current_classID    = get_current_classID();
    
    $current_user       = get_current_username($connection);
    
    
    $current_questionID = get_active_question_questionID($connection, $questions_table_name, $current_classID);

    //echo "current_questionID: $current_questionID <br />";
    
    $question_info = get_question_info($connection, $questions_table_name, $questions_args_table_name, $questions_answers_table_name, $current_classID, $current_questionID );

    $prompt                 = $question_info['prompt'];
    
    //echo "prompt in main: $prompt <br />";
    $question_type          = $question_info['question_type'];
        
    $arg_array              = $question_info['arg_array'];

    $answer_array           = $question_info['answer_array'];
    $answer_correct_array   = $question_info['answer_correct_array'];
    $answer_label_array     = $question_info['answer_label_array'];
    
    //print_r($answer_array);
    
    //print_r($question_info);
    
    $html = get_html_form($prompt, $arg_array, $answer_array, $question_type, $current_questionID);
    
    echo $html;
    

?>