<?php
	
    require_once 'add_functions.php';
	//echo 'I did it! <br />';
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
            //echo 'No POST Request found';
            print_r($_POST);
        }
        
        
        
        

        
        
        
        return array('classname'         => $classname);
    };
    
    
    function validate_Authentication_Request($authentication_request, $connection, $login_database_name, $class_table_name, $login_table_name ) {
        ///*  Returns TRUE / FALSE -- request can be created using 'parse_Authentication_Request' and an HTTP POST ****/
 $classname=mysql_real_escape_string($authentication_request['classname']);
        
        //echo $login_table_name;
        //echo "Username: $username   and password_hashed: $password_hashed <br />";
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $select_user_query = "SELECT classname FROM $class_table_name WHERE classname='$classname'";
        $user_info_query = mysql_query($select_user_query, $connection) or die(mysql_error());

        $user_info = mysql_fetch_assoc($user_info_query);
       
        $unique_class = FALSE;
       
        if ($user_info) {
            $unique_class = FALSE;
            
        }
        else {
            $unique_class = TRUE;
            ///*  Returns TRUE / FALSE -- request can be created using 'parse_Authentication_Request' and an HTTP POST ****/
 		$classname=mysql_real_escape_string($authentication_request['classname']);
        
        //echo $classname;
        //echo "Username: $username   and password_hashed: $password_hashed <br />";
        $username = ($_COOKIE['Status_Login_Username']);
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        $username=$_COOKIE['Status_Login_Username'];
        $select_user_query = "SELECT classes FROM $login_table_name WHERE username='$username'";
        //echo $select_user_query;
        $user_info_query = mysql_query($select_user_query, $connection) or die(mysql_error());

        $user_info = mysql_fetch_array($user_info_query);
        
		//echo $user_info[0]."<br />";
		//echo $string;
       	
        $newclass = $user_info[0] . $classname . ';';
        $update_stuff = "UPDATE $login_table_name SET classes='$newclass' WHERE username = '$username'";
        //echo $update_stuff."<br />";
        
        mysql_query($update_stuff, $connection) or die(mysql_error());
            ////echo "LOGIN FAILED. Incorrect username or password. <br />";
        } 
        
        return $unique_class;
        
        
    };
    

    ///**********************RUN PROGRAM ********************/
 
    
function main($connection, $login_database_name, $class_table_name, $login_table_name){

    ////echo 'about to parse request <br />';

    
    $authentication_request = parse_Authentication_Request();
   
    ////echo $authentication_request;
   
    ////echo 'about to validate request <br />';
   
    $login_status_valid = validate_Authentication_Request($authentication_request, $connection, $login_database_name, $class_table_name, $login_table_name );
    
    ////echo 'about to check if login worked <br />';
    
    
    // Redirect and pass cookie giving login attempt result
    // Cookie does not give access, it is simply to inform the user
    if($login_status_valid){
        //setcookie('Message_LoginSuccess', 'Success', time(), '/');      
        //create_Logged_In_Cookie($authentication_request['username'],$connection);
        
        //header('Location:' . $_SERVER['HTTP_REFERER']);
        ////echo 'New Class! <br />';
	$classname = $authentication_request['classname'];
	////echo $authentication_request.'<br />';
	//echo 'junk'.'<br/>';
        //echo $classname.'<br />';
	$classTable = $classname . '_table';
		mysql_query("INSERT INTO classes (classname, classTable) VALUES ('$classname', '$classTable')", $connection) or die(mysql_error());
		$create_class_table_command = "CREATE TABLE $classname
                            (
                            username    varchar(255),
                            instructor  varchar(5)
                            )";
                            
        mysql_query($create_class_table_command, $connection) or die(mysql_error());
        $username = $_COOKIE['Status_Login_Username'];
        $insert_command = "INSERT INTO $classname (username, instructor) VALUES ('$username', 'yes')";
        
        mysql_query($insert_command, $connection) or die(mysql_error());
         
         setcookie('class', $classname, time()+3600*24, '/');
         header('Location: classhome.html');
    }
    else{
        setcookie('create_class', 'Failed', time()+3600*24, '/');
        //header('Location:' . $_SERVER['HTTP_REFERER']);       
        header('Location: addclass.html');
        
        //echo 'Redirects to: ' . $_SERVER['HTTP_REFERER'] . '<br />';
        //echo 'Class already created :( <br />';
        //echo 'Cookie value: ' . $_COOKIE['Message_LoginSuccess'] . '<br />'; 
    }
    
   mysql_close($connection);  
    
}
    //echo 'there';
    main($connection, $login_database_name, $class_table_name, $login_table_name);

?>