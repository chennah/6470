<?php
//Searches for first active question in a particular class, and then displays it to a student, with student input possible


    // Get the current class (default '1234' if not in get request)
    require_once 'questions_functions.php';  

    function get_html_form_responses($prompt, $arg_array, $answer_array, $answer_correct_array, $answer_label_array, $response_array, $question_type, $current_questionID){
        $current_time       = page_loaded_when();
        
        
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

        $Free_Response_Answers_list = "<table border='1'>
                                <tr>
                                    <th> Answer </th>   <th> Label </th>    <th> Correct? </th>
                                </tr>";
                                
            for($i=0; $i < count($answer_array) ; $i++){
                
                $correct = '';
                if($answer_correct_array[$i] == 1){
                    $correct = 'Correct';
                }
            
                $tmp = "<tr> <td>  $answer_array[$i] </td>  <td> $answer_label_array[$i]  </td>     <td> $correct </td>     </tr>";
                $Free_Response_Answers_list .= $tmp;
                                
            }
         $Free_Response_Answers_list .= '</table>';   
        
        
        
        $Free_Response_Answers_inner = <<< EOF
        
            <div id='Free_Response_Answers'>
        
            <h4> Free Response Answers of Interest (listed by Instructor) </h4>
                    
                    $Free_Response_Answers_list        
                
            </div>
                    
                    
EOF;
        
        
        
        $Free_Response_inner = <<< EOF
        
            $Arguments
        
            $Free_Response_Answers_inner
        
EOF;
        
        
        
            ///**************Prepare HTML code if Multiple Choice********************//
        
        //Build a frequency table of how often each answer is chosen -- replace with SQL table for efficiency later
        $num_MC_answers = count($answer_array);
        
        $MC_answers_frequency = array();
        
             ///*Initialize the frequency table*///
        for($i=0; $i < $num_MC_answers; $i++){
            array_push($MC_answers_frequency, 0);
        }
        
        $num_responses = count($response_array);
        echo "count(response_array): $num_responses <br />";
        for($i=0;  $i < $num_responses; $i++){
            
                $tmp_row = $response_array[$i];
                $tmp_response = intval($tmp_row['response']);   //'response saved as str, eg '1'
                
                $old_freq = $MC_answers_frequency[$tmp_response];
                $MC_answers_frequency[$tmp_response] += 1;
                $new_freq = $MC_answers_frequency[$tmp_response];
                
                echo "$tmp_response: $old_freq -> $new_freq <br />";
            
        }
        
        
        
        
        
        
        $MC_Answers_list = "<table border='1'>
                                <tr>
                                    <th> Answer </th>   <th> Label </th>    <th> Correct? </th>     <th> Frequency chosen </th>
                                </tr>";
            
            for($i=0; $i < count($answer_array) ; $i++){
                
                $correct = '';
                if($answer_correct_array[$i] == 1){
                    $correct = 'Correct';
                }
            
                $tmp = "<tr> <td>  $answer_array[$i] </td>  <td> $answer_label_array[$i]  </td>     <td> $correct </td>  <td> $MC_answers_frequency[$i] </td>   </tr>";
                $MC_Answers_list .= $tmp;
                
            } 
         $MC_Answers_list .= '</table>';
        
        
        
        $MC_Answers_inner = <<< EOF
        
            <div id='MC_Answers'>
        
            <h4> Available Multiple-Choice Answers </h4>
                    
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
        
            ///**************** Create a table of all the responses *******************//
            
        $responses_table = "<table border='1'>
                                <tr>
                                    <th> Username </th>   <th> Answer Chosen </th>
                                </tr>";
                                
                                echo 'entering response section <br />';
                                
            //$tmp_count = count($response_array);
            //echo 'count($response_array): ' . "$tmp_count <br />";
            for($i=0; $i < count($response_array) ; $i++){
                
                $response_row = $response_array[$i];
                $username_temp = $response_row['username'];
                $response_temp = $response_row['response'];

                
                //echo '$response_row, in for loop: '; print_r($response_row); echo "<br />";
                

                $tmp = "<tr> <td> $username_temp </td>  <td> $response_temp  </td>    </tr>";
                $responses_table .= $tmp;
                                
            }
         $responses_table .= '</table>';
         
        
        
        
            
            //****************Wrap it all together*******************//
                    
        $html_temp = <<< EOF
            <html>
            <head>
                <title> Responses - Active Question </title>
            </head>
            
            <body>
                
                <h1> View Responses to Current Active Question</h1>
                
                <p> This page loaded at: $current_time. Responses added since then are not shown. </p>
                
                <h2> Original Question  </h2>
                
                <h4>Prompt </h4>
                <p> $prompt </p>
                
                $Free_Response
                
                $MC_Answers
                
                
                
                <h2>  Summarized Results  </h2>
                
                $summarized_results_html
                
                <h2>  Full List of Responses </h2>
                
                $responses_table
                
                
            </body>
            
        </html>
EOF;
        
           if(isset($html_temp)){
                return $html_temp;
           }
           else{
                $html_temp ='html_temp set to test -- no other values set';
                return $html_temp;
           }            
    }

    
    
    $current_classID    = get_current_classID();
    
    $current_username   = get_current_username($connection);
    
    
    $current_questionID = get_active_question_questionID($connection, $questions_table_name, $current_classID);

    echo "current_questionID: $current_questionID <br />";
    
    $question_info = get_question_info($connection, $questions_table_name, $questions_args_table_name, $questions_answers_table_name, $current_classID, $current_questionID );

    $prompt                 = $question_info['prompt'];
    
    echo "prompt in main: $prompt <br />";
    $question_type          = $question_info['question_type'];
        
    $arg_array              = $question_info['arg_array'];
    
    $answer_array           = $question_info['answer_array'];
    $answer_correct_array   = $question_info['answer_correct_array'];
    $answer_label_array     = $question_info['answer_label_array'];
    
    $response_array         = get_responses($connection, $questions_responses_table_name, $current_classID, $current_questionID, $question_type);
    
    echo "'question_info: '"; print_r($question_info); echo "<br />";
    
    echo "'response_array: '"; print_r($response_array); echo "<br />";
    
    
    
    $html = get_html_form_responses($prompt, $arg_array, $answer_array, $answer_correct_array, $answer_label_array, $response_array, $question_type, $current_questionID);
    
    echo $html;
        




?>