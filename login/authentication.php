<?php

    require 'login_functions.php';

    ///********************** FUNCTIONS ************/
    
     
   function parse_Authentication_Request(){
    // Take in an HTTP POST request, and store in a PHP datastructure
        
        $username   = '';
        $password   = '';
        
        
        //Username
        if (isset($_POST["user"])){    
           $username = $_POST["user"];
          // echo $username . '<br />';
        }  
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        
        
        //Password
        if (isset($_POST["password"])){    
           $password = ($_POST["password"]);
        }    
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        

        $password_hashed = cryptPass($password);
        
        
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

    ///**********************RUN PROGRAM ********************/
 
    
function main($connection, $login_database_name, $login_table_name){

    check_Login_Tables($connection, $login_database_name, $login_table_name);
    
  //  echo 'about to parse request <br />';
    
    $authentication_request = parse_Authentication_Request();
   
  //  echo $$authentication_request;
   
  //  echo 'about to validate request <br />';
   
    $login_status_valid = validate_Authentication_Request($authentication_request, $connection, $login_database_name, $login_table_name );
    
  //  echo 'about to check if login worked <br />';
    
    
    // Redirect and pass cookie giving login attempt result
    // Cookie does not give access, it is simply to inform the user
    if($login_status_valid){
        setcookie('Message_LoginSuccess', 'Success', time(), '/');
        header('Location:' . $_SERVER['HTTP_REFERER']);
        echo 'Valid login <br />';
        echo 'Cookie value: ' . $_COOKIE['Message_LoginSuccess'] . '<br />'; 
    }
    else{
        setcookie('Message_LoginSuccess', 'Failed', time(), '/');
        header('Location:' . $_SERVER['HTTP_REFERER']);       
        //header('Location: https://egentry.scripts.mit.edu:444/6470_test/index.html');
        
        echo  'Redirects to: ' . $_SERVER['HTTP_REFERER'] . '<br />';
        echo 'Failed login <br />';
        echo 'Cookie value: ' . $_COOKIE['Message_LoginSuccess'] . '<br />'; 
    }
    
   mysql_close($connection);  
    
}
    
    main($connection, $login_database_name, $login_table_name);

?>