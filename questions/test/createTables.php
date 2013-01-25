<?php


    require_once '../questions_HIDDEN.php';
    
    function check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name, $questions_answers_table_name) {
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
                            argNum          INT,
                            arg             varchar(255)
                            )";
                            
        $create_questions_answers_table_command = "CREATE TABLE $questions_answers_table_name
                   (
                   questionID       INT,
                   answerNum        INT,
                   answer           varchar(255),
                   correct          INT,
                   label            varchar(255)
                   )";                   
        
        echo "Can I connect to the database? : $database_name <br /> ";
        
        mysql_select_db($database_name, $connection) or die(mysql_error());
        
        //echo 'Database connection successful! <br />';    
          
        echo "About to check if tables exist (check_Question_Tables) <br />";
           
        $table_exist = mysql_query("SELECT 1 from $questions_table_name");
        if($table_exist !== FALSE)
        {
            echo "Table: '$questions_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            echo "Table: '$questions_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_table_command, $connection) or die(mysql_error()); 
        }
        
        
        $table_exist = mysql_query("SELECT 1 from $questions_args_table_name");
        if($table_exist !== FALSE)
        {
            echo "Table: '$questions_args_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            echo "Table: '$questions_args_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_args_table_command, $connection) or die(mysql_error()); 
        }
        
        $table_exist = mysql_query("SELECT 1 from $questions_answers_table_name");
        if($table_exist !== FALSE)
        {
            echo "Table: '$questions_answers_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            echo "Table: '$questions_answers_table_name'  DOES NOT exist -- tried to CREATE <br />";
            echo "$create_questions_answers_table_command <br />";
            mysql_query($create_questions_answers_table_command, $connection) or die(mysql_error()); 
        }    
        
    };

    
    
    
    check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name, $questions_answers_table_name);

    
    header('Location:' . $_SERVER['HTTP_REFERER']);       


?>