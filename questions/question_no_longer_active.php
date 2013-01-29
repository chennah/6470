<?php

    require_once 'questions_functions.php';
   
    $classID = NULL;
    $questionID = NULL;
    
    if(isset($_POST['classID'])){
        $classID = $_POST['classID'];
    }
    else{
        $classID = 1234;
    }
    
    
    if(isset($_POST['questionID'])){
        $questionID = $_POST['questionID'];
    }
    else{
        $questionID = get_active_question_questionID($connection, $questions_table_name, $classID );
    }
    
    
    
    set_question_inactive($connection, $questions_table_name, $classID, $questionID);



    $redirect = TRUE;
    
    if($redirect === TRUE){
                
        header('Location:' . $_SERVER['HTTP_REFERER']);
        
    };


?>