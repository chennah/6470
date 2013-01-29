<?php
    //echo 'login_functions <br />';

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
    
  
    
    function create_Logged_In_Table($connection){
        
        
        $create_logged_in_cookies_command = "CREATE TABLE Logged_In_Cookies
                            (
                            username    varchar(255),
                            loginRand   INT
                            )";
                            
        
                            
        //echo 'Command to create cookies table:' . $create_logged_in_cookies_command . '<br />';
         
        $checktable = mysql_query("SHOW TABLES LIKE 'Logged_In_Cookies'");
        
        //echo   'Check if Logged_In_Cookies exists: ' . (mysql_num_rows($checktable) > 0) . '<br />';
        
        if(mysql_num_rows($checktable) > 0)
        {
            //echo "Table: 'Logged_In_Cookies' already exists -- was trying to CREATE<br />";  
        }
        else
        {
            //echo "Table: 'Logged_In_Cookies'  DOES NOT exist -- trying to CREATE now<br />";
            mysql_query($create_logged_in_cookies_command, $connection);// or die(mysql_error());
            
            mysql_query("DELETE FROM Logged_In_Cookies WHERE username='staticTest' and loginRand=1234") or die(mysql_error());
            mysql_query("INSERT INTO Logged_In_Cookies (username, loginRand) VALUES ('staticTest', 1234)", $connection) or die(mysql_error());

        }
        
        
        
    };
    
    
    function remove_Logged_In_Cookie(){
        //echo "entering: 'remove_Logged_In_Cookie' <br />";

        $login_Rand = mysql_real_escape_string($_COOKIE['Status_Login_RAND']);
        $username   = mysql_real_escape_string($_COOKIE['Status_Login_Username']);
        
        mysql_query("DELETE FROM Logged_In_Cookies WHERE username='$username' and loginRand=$login_Rand") or die(mysql_error());
        
        
        //Deletes cookie
        setcookie("Status_Login_RAND",      "", time()-3600, '/');
        setcookie("Status_Login_Username",  "", time()-3600, '/');
        
        
        //Deletes login_Rand from "Logged_In_Cookies" table     
        mysql_query("DELETE FROM Logged_In_Cookies WHERE username='$username' and loginRand=$login_Rand") or die(mysql_error());
        
    }; 
    
    function clear_User_Cookies($username_raw){
        //echo "entering: 'clear_User_Cookies'     username: '$username_raw' <br />";
        $username   = mysql_real_escape_string($username_raw);
        
        mysql_query("DELETE FROM Logged_In_Cookies WHERE username='$username'") or die(mysql_error());
        
        //echo "exiting: 'clear_User_Cookies'     username: '$username_raw' <br />";

    }; 
    
    
    function create_Logged_In_Cookie($username_raw, $connection){
            //echo 'Entering create_Logged_In_Cookie <br />';
            $username   = mysql_real_escape_string($username_raw);
            $login_rand = rand();
            
            //echo $username . '<br />';
            //echo $login_rand . '<br />';
            
            setcookie('Status_Login_RAND',      "$login_rand",  time()+3600*72, '/');
            setcookie('Status_Login_Username',  "$username",    time()+3600*72, '/');
            
            //echo 'About to insert cookie\'s random into table <br />';
            mysql_query("INSERT INTO Logged_In_Cookies (username, loginRand) VALUES ('$username', $login_rand)", $connection) or die(mysql_error());
            //echo 'User cookie-random information insterted <br />';
            
            
    };
    
    function check_Already_Logged_In(){
        
        if($_COOKIE['Status_Login_RAND']==''){
            return FALSE;
        }
        else {
            $login_Rand = mysql_real_escape_string($_COOKIE['Status_Login_RAND']);
            $username   = mysql_real_escape_string($_COOKIE['Status_Login_Username']);
            
            
            
            $sql_result = mysql_query("SELECT * FROM Logged_In_Cookies WHERE username='$username' and loginRand=$login_Rand");
            
            //echo 'get passed the suspect query? <br />';

            if(is_resource($sql_result) && mysql_num_rows($sql_result) > 0 ){
                //echo 'Found match in table <br />';
                //logged in, with valid credentials
                return TRUE;
            }
            else{
                //echo 'Found no match in table <br />';
                //Invalid cookie random number -- indicates potential client-side tampering
                //Delete all valid cookies of that user -- but note: this might cause issues if user is logged in on multiple instances
                
                //Clears cookie information server side
                clear_User_Cookies($_COOKIE['Status_Login_Username']);
                
                
                //Clears cookie client side
                remove_Logged_In_Cookie();
                return FALSE;
            }
        }
    };
    
    
    
    ///********************** VARIABLES ************/
    
    
     ///********************** Run Functions ************/   
    
    $connection = mysql_connect($sql_server, $sql_username, $sql_password) or die(mysql_error());
    
    if($connection){
        //echo 'Server connection successful! <br />';
    }
    
    //check_Login_Tables($connection, $login_database_name, $class_table_name);
   // create_Logged_In_Table($connection);

?>