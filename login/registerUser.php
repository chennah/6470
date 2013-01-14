<?php

    ///* pseudo-code:
    // *      Establish connection
    // *      Check for existing table
    // *
    // *      escape username characters
    // *      check for existing username
    // *
    // *      cryptpassword
    // *      escape hashed password
    // *
    // *      insert user information
    // *
    // *      close connection
    // *      
    // *      set message cookie  -- change naming structure on cookies
    // *
    // *      create jQuery to read message cookie
    // *
    // */
    
    require 'login_functions.php';
    
    ///********************** FUNCTIONS ************/
   function parse_Registration_Request(){
    // Take in an HTTP POST request, and store in a PHP datastructure
        
        $name       = '';
        $username   = '';
        $password   = '';
        $email      = '';
        
 
        //Username
        if (isset($_POST["myname"])){    
           $username = $_POST["myname"];
          // echo $username . '<br />';
        }  
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        } 
        
        //Username
        if (isset($_POST["myusername"])){    
           $username = $_POST["myusername"];
          // echo $username . '<br />';
        }  
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        
        
        //Password 
        if (isset($_POST["mypword"])){    
           $password = ($_POST["mypword"]);
        }    
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        $password_hashed = cryptPass($password);
        
        
        if (isset($_POST["myemail"])){    
           $email = ($_POST["myemail"]);
        }    
        else { 
            //echo 'No POST Request found';
            //print_r($_POST);
        }
        
        
        return array('name'             => $name,
                     'username'         => $username,
                     'password_hashed'  => $password_hashed,
                     'email'            => $email);
    };

    function evaluate_Registration_Request($authentication_request, $connection, $login_database_name, $login_table_name  ) {
        ///*  Returns TRUE / FALSE -- request can be created using 'parse_Authentication_Request' and an HTTP POST ****/
        $name               = mysql_real_escape_string($authentication_request['name']);
        $username           = mysql_real_escape_string($authentication_request['username']);
        $password_hashed    = mysql_real_escape_string($authentication_request['password_hashed']);
        $email              = mysql_real_escape_string($authentication_request['email']);
        //Check if user exists
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());

        $select_user_query = "SELECT lastLogin FROM $login_table_name WHERE username='$username' ";
        $user_info_query = mysql_query($select_user_query, $connection) or die(mysql_error());
        $user_info = mysql_fetch_assoc($user_info_query);
       
        $Registration_Message = '';
       
        if ($user_info) {
            $Registration_Message = 'ExistingUser';     //User already exists!
            setcookie('Message_RegistrationSuccess_User', "$username");
            return $valid_Authentication;
        }
        else {
            mysql_query("INSERT INTO $login_table_name (name, username, password, email, lastLogin) VALUES ($name, '$username', '$password_hashed', '$email', NOW())", $connection) or die(mysql_error());
            
            $Registration_Message = "UserCreated";
            //echo "$username successfully logged in. <br />";
        } 

        return $Registration_Message;
        
    }    


    ///********************** VARIABLES ************/

    
    ///**********************RUN PROGRAM ********************/
    
    
    function main($connection, $login_database_name, $login_table_name){
        
        check_Login_Tables($connection, $login_database_name, $login_table_name);    
       
        $registration_request = parse_Registration_Request();
        
        $Registration_Message = evaluate_Registration_Request($authentication_request, $connection, $login_database_name, $login_table_name );
        
        
        
        // Redirect and pass cookie giving login attempt result
        // Cookie does not give access, it is simply to inform the user
        if($login_status_valid){
            setcookie('Message_RegistrationSuccess', $Registration_Message);
            header('Location: https://egentry.scripts.mit.edu:444/6470_test/index.html');
            echo "Registration Message: $Registration_Message <br />";
            echo 'Cookie value: ' . $_COOKIE['Message_RegistrationSuccess'] . '<br />'; 
        }
        else{
            setcookie('Message_RegistrationSuccess', $Registration_Message);
            header('Location: https://egentry.scripts.mit.edu:444/6470_test/index.html');
            echo 'Failed registration <br />';
            echo 'Cookie value: ' . $_COOKIE['Message_RegistrationSuccess'] . '<br />'; 
        }
        
        mysql_close($connection);  
    }
    
    
main($connection, $login_database_name, $login_table_name);    





?>