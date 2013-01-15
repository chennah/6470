<?php

    

    //***************** HIDDEN INFORMATION ****************//
    require 'login_HIDDEN.php';
    // function: 'cryptPass' hidden for security
    // variables relating to sql login hidden for security
    //      -sql_server
    //      -sql_username
    //      -sql_password
    //
    //      -login_database_name
    //      -login_table_name
    
 
    //***************** Shared Functions ****************// 
    
    function check_Login_Tables($connection, $database_name, $login_table_name) {
        /* Checks if login table exists. If login table does not exist, it is created.
        */
        
        $create_login_database_command = "CREATE TABLE $login_table_name
                            (
                            name        varchar(255),
                            username    varchar(255),
                            password    varchar(128),
                            email       varchar(255),
                            lastLogin   DATETIME
                            )";
    
        mysql_select_db($database_name, $connection) or die(mysql_error());

        echo 'Database connection successful! <br />';    
           
        $table_exist = mysql_query("SELECT 1 from $login_table_name");
        if($table_exist !== FALSE)
        {
            //echo "Table: '$login_table_name' already exists -- tried to CREATE<br />";  
        }
        else
        {
            //echo "Table: '$login_table_name'  DOES NOT exist -- tried to CREATE <br />";
            mysql_query($create_login_database_command, $connection) or die(mysql_error()); 
        }
         
        
    }
    
    
    
    ///********************** VARIABLES ************/
    
    
     ///********************** Run Functions ************/   
    
    $connection = mysql_connect($sql_server, $sql_username, $sql_password) or die(mysql_error());
    
    if($connection){
        //echo 'Server connection successful! <br />';
    }
    
    check_Login_Tables($connection, $login_database_name, $login_table_name);


?>