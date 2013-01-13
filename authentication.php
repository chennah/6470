<?php

    ///********************** FUNCTIONS ************/

    function check_Login_Tables($connection, $database_name, $login_table_name) {
        /* Checks if login table exists. If login table does not exist, it is created.
        */
        
        $create_login_database_command = "CREATE TABLE $login_table_name
                            (
                            username varchar(255),
                            password varchar(128),
                            email varchar(255),
                            lastLogin DATETIME
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
    
        
   function parse_Authentication_Request($crypt_salt){
    // Take in an HTTP POST request, and store in a PHP datastructure
        
        $username = '';
        $password = '';
        
        
        //Username
        if (isset($_POST["user"])){    
           $username = $_POST["user"];
          // echo $username . '<br />';
        }  
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        
        
        
        if (isset($_POST["password"])){    
           $password = ($_POST["password"]);
        }    
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        
        if($crypt_salt === ''){
        $password_hashed = cryptPass($password);
        }
        else{
        $password_hashed = cryptPass($password, $crypt_salt);
        }
        
        
        return array('username'         => $username,
                     'password_hashed'  => $password_hashed);
    };
    
    
    function validate_Authentication_Request($authentication_request, $connection, $login_database_name, $login_table_name  ) {
        ///*  Returns TRUE / FALSE -- request can be created using 'parse_Authentication_Request' and an HTTP POST ****/
        $username           = mysql_real_escape_string($authentication_request['username']);
        $password_hashed    = mysql_real_escape_string($authentication_request['password_hashed']);
        
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $select_user_query = "SELECT lastLogin FROM $login_table_name WHERE username='$username' AND PASSWORD='$password_hashed'";
        $user_info_query = mysql_query($select_user_query, $connection) or die(mysql_error());

        $user_info = mysql_fetch_assoc($user_info_query);
       
        $valid_Authentication = FALSE;
       
        if ($user_info) {
            $valid_Authentication = TRUE;
            mysql_query("UPDATE $login_table_name SET lastLogin=NOW() WHERE username = '$username' and password = '$password_hashed'", $connection) or die(mysql_error());
            //echo "$username successfully logged in. <br />";
        }
        else {
            $valid_Authentication = FALSE;
            //echo "LOGIN FAILED. Incorrect username or password. <br />";
        } 
        
        return $valid_Authentication;
        
        
    }
    
    function cryptPass($string){
        $salt = hash('whirlpool', hash('md5', $string));
        $string = hash('sha512', $string.$salt);
        
        return $string;
        
        
    }

    ///********************** VARIABLES ************/


    $sql_server             = 'sql.mit.edu';

    $sql_username           = 'egentry';
    $sql_password           = 'Philmont';
    
    $login_database_name    = 'egentry+www_test';
    $login_table_name       = 'login';
    
    $crypt_salt             = '';
    
    $connection = mysql_connect($sql_server, $sql_username, $sql_password) or die(mysql_error());
    
    if($connection){
        //echo 'Server connection successful! <br />';
    }   

    ///**********************RUN PROGRAM ********************/
 
    
function main($connection, $login_database_name, $login_table_name){

    check_Login_Tables($connection, $login_database_name, $login_table_name);
    
  //  echo 'about to parse request <br />';
    
    $authentication_request = parse_Authentication_Request($crypt_salt);
   
  //  echo $$authentication_request;
   
  //  echo 'about to validate request <br />';
   
    $login_status_valid = validate_Authentication_Request($authentication_request, $connection, $login_database_name, $login_table_name );
    
  //  echo 'about to check if login worked <br />';
    
    
    // Redirect and pass cookie giving login attempt result
    // Cookie does not give access, it is simply to inform the user
    if($login_status_valid){
        setcookie('LoginSuccessMessage', 'Success');
        header('Location: https://egentry.scripts.mit.edu:444/6470_test/index.html');
        echo 'Valid login <br />';
        echo 'Cookie value: ' . $_COOKIE['LoginSuccessMessage'] . '<br />'; 
    }
    else{
        setcookie('LoginSuccessMessage', 'Failed');
        header('Location: https://egentry.scripts.mit.edu:444/6470_test/index.html');
        echo 'Failed login <br />';
        echo 'Cookie value: ' . $_COOKIE['LoginSuccessMessage'] . '<br />'; 
    }
    
   mysql_close($connection);  
    
    }
    
  main($connection, $login_database_name, $login_table_name);

    
   // check_Login_Tables($connection, $database_name, $login_table_name)

?>