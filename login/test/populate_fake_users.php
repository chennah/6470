<?php

    require_once '../login_functions.php';
    
    
    function populateTable($connection, $login_database_name, $login_table_name){
        
//mysql_select_db($login_database_name, $connection) or die(mysql_error());       
        //check_Login_Tables($connection, $login_database_name, $login_table_name);
        
        echo 'Preparing to populate user table with 5000 throw-away entries <br />';

        for ($i = 1; $i < 6000; $i++){
            
            $name       = mysql_real_escape_string('bulkName'       . $i);
            $username   = mysql_real_escape_string('bulkUsername'   . $i);
            $password   = mysql_real_escape_string(cryptPass('password'. $i));
            $email      = mysql_real_escape_string('email@website.com');
            
            if($i%1000 == 0){
                echo $i . '<br />';
                //echo $name . '<br />';
                //echo $username . '<br />';
                //echo $email . '<br />';
                //echo $password . '<br />';
                
            }
            
                        
           // echo "INSERT INTO $login_table_name (username, password, email, lastLogin) VALUES ('$usr', '$pw', '$email', NOW())" . '<br />';
            mysql_query("INSERT INTO $login_table_name (name, username, password, email, lastLogin) VALUES ('$name', '$username', '$password', '$email', NOW())", $connection) or die(mysql_error());
            
        };
        
        echo 'Bulk addition of names complete. Please check PHPmyAdmin. <br />';
        
    };
    
    populateTable($connection, $login_database_name, $login_table_name);
    
?>