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
    echo "About to require 'login_HIDDEN.php' <br />";
    require_once './../login/login_HIDDEN.php';
    
    echo "About to require 'questions_HIDDEN.php' <br />";
    require_once 'questions_HIDDEN.php';    //includes SQL login information

    
    
    echo "About to require 'login_functions.php' <br />";
    require_once '../login/login_functions.php';
    
    echo "About to start writing functions for questions_functions.php <br />";
    
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

    function check_Question_Tables($connection, $database_name, $questions_table_name, $questions_args_table_name) {
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
                            arg             varchar(255),
                            correct         INT,
                            label           varchar(255)
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
            //echo "Table: '$questions_args_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$questions_args_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_questions_args_table_command, $connection) or die(mysql_error()); 
        }         
        
    };
    
    
    function get_active_questions($connection, $database_name, $questions_table_name ){
    
    };
    

?>