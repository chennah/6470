<?php
	echo 'stuff is here <br />';
    require_once 'add_functions.php';
	echo 'I did it! <br />';
    ///********************** FUNCTIONS ************/
    
     
   function parse_Authentication_Request(){
    // Take in an HTTP POST request, and store in a PHP datastructure
        
        $classname   = '';
        
        
        
        //Username
        if (isset($_POST['classname'])){    
           $classname = $_POST['classname'];
          // echo $username . '<br />';
        }  
        else { 
            echo 'No POST Request found';
            print_r($_POST);
        }
        
        
        
        

        
        
        
        return array('classname'         => $classname);
    };
    
    
    function validate_Authentication_Request($authentication_request, $connection, $login_database_name, $login_table_name ) {
        ///*  Returns TRUE / FALSE -- request can be created using 'parse_Authentication_Request' and an HTTP POST ****/
 		$classname=mysql_real_escape_string($authentication_request['classname']);
        
        echo $classname;
        //echo "Username: $username   and password_hashed: $password_hashed <br />";
        $username = ($_COOKIE['Status_Login_Username']);
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        $username=$_COOKIE['Status_Login_Username'];
        $select_user_query = "SELECT classes FROM $login_table_name WHERE username='$username'";
        echo $select_user_query;
        $user_info_query = mysql_query($select_user_query, $connection) or die(mysql_error());

        $user_info = mysql_fetch_array($user_info_query);
        
		echo $user_info[0]."<br />";
		//echo $string;
       	
        $newclass = $user_info[0] . $classname . ';';
        $update_stuff = "UPDATE $login_table_name SET classes='$newclass' WHERE username = '$username'";
        echo $update_stuff."<br />";
        
        mysql_query($update_stuff, $connection) or die(mysql_error());
        return TRUE;
        
    };
    

    ///**********************RUN PROGRAM ********************/
 
    
function main($connection, $login_database_name, $login_table_name){

    echo 'about to parse request <br />';

    
    $authentication_request = parse_Authentication_Request();
   
    //echo $authentication_request;
   
   
    $login_status_valid = validate_Authentication_Request($authentication_request, $connection, $login_database_name, $login_table_name);
    
    
    // Redirect and pass cookie giving login attempt result
    // Cookie does not give access, it is simply to inform the user
    
        //setcookie('Message_LoginSuccess', 'Success', time(), '/');      
        //create_Logged_In_Cookie($authentication_request['username'],$connection);
        
        //header('Location:' . $_SERVER['HTTP_REFERER']);
        
	$classname = $authentication_request['classname'];
	
	$username=$_COOKIE['Status_Login_Username'];
		mysql_query("INSERT INTO $classname (username, instructor) VALUES ('$username', 'no')", $connection) or die(mysql_error());
        
    
   mysql_close($connection);  
    header('Location: classhome.html');
}
    
    main($connection, $login_database_name, $login_table_name);

?>