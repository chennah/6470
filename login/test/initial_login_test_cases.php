<?php
    // REMEMBER TO COMMENT OUT THE main() CALL IN authentication.php !!!

    /*
     *  Initial Debugging + Automation
     *      populate multiple users
     *      
     *
     *
     *  Unit Tests:
     *      no username or password
     *      no username
     *      no password
     *      false username
     *      false password
     *      username + password are from different users
     *      SQL Injection
     *          username
     *          password
     *
     *
     *  Print statement tests:
     *      datetime updates correctly
     *          
     *
     *
     *
     *
     *
     */


    require_once '../initial_login.php';
    
    
    
    function populateTable($connection, $login_database_name, $login_table_name){
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());       
        mysql_query("DROP TABLE $login_table_name") or die(mysql_error());
        echo "Dropped table: $login_table_name <br />";
        check_Login_Tables($connection, $login_database_name, $login_table_name);
                            
 
        $users_array = array(   array('name' => 'Kyle', 'username' => 'User1', 'password' => cryptPass('password1'), 'email' => 'person@web.site' ), 
                                array('name' => 'Tim',  'username' => 'User2', 'password' => cryptPass('pasword2µ!@#$%^&*()'), 'email' => 'person2@place.online'),
                                array('name' => 'John', 'username' => 'User3', 'password' => cryptPass('[]s[d]fs[w]d\n'), 'email' => 'test@asdf.jkl')
        );
        
        echo "attempting to write users into database <br />";

        foreach ($users_array as $user){
            
            $name_temp         = mysql_real_escape_string($user['name']);
            $username_temp     = mysql_real_escape_string($user['username']);
            $password_temp     = mysql_real_escape_string($user['password']);
            $email_temp        = mysql_real_escape_string($user['email']);
            
            
                        
           // echo "INSERT INTO $login_table_name (username, password, email, lastLogin) VALUES ('$usr', '$pw', '$email', NOW())" . '<br />';
            mysql_query("INSERT INTO $login_table_name (name, username, password, email, lastLogin) VALUES ('$name_temp', '$username_temp', '$password_temp', '$email_temp', NOW())", $connection) or die(mysql_error());
            
        }
        
    };
    

    
    function displayUserTable($connection, $login_database_name, $login_table_name){
        mysql_select_db($login_database_name, $connection) or die(mysql_error());       
        check_Login_Tables($connection, $login_database_name, $login_table_name);
        
        $result = mysql_query("SELECT * FROM $login_table_name") or die(mysql_error());

        echo "<table border='1'>
        <tr>
        <th>Username</th>
        <th>Password (hashed)</th>
        <th>Email</th>
        <th>Last Login</th>
        <th>Sign In? </th>
        </tr>";
        
        while($row = mysql_fetch_array($result))
          {
            $authentication_request = array('username'         => $row['username'],
                                            'password_hashed'  => $row['password']);            
        
         
         $login_result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
         $login_bool = FALSE;
         if($login_result){
            $login_bool = 'TRUE';
         }
         else{
            $login_bool = 'FALSE';           
         }
         
          echo "<tr>";
          echo "<td>" . $row['username']    . "</td>";
          echo "<td>" . $row['password']    . "</td>";
          echo "<td>" . $row['email']       . "</td>";
          echo "<td>" . $row['lastLogin']   . "</td>";
          echo "<td>" . $login_bool         . "</td>";
          echo "</tr>";
          };
          
        echo "</table> <br /> <br /> <hr>";

        
    }
    
    function unit_NoUser_NoPass($connection, $login_database_name, $login_table_name){
        echo "<h4> Unit Test: No username, no password </h4>";
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $authentication_request = array('username'  => "",
                                 'password_hashed'  => '');
        
        $result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
    
        if($result === False){
            echo "Test: Passed. <br /> <br /> <hr>";
        }
        else{
            echo "Test: Failed. <br /> <br /> <hr>";
        }
    }; 

    function unit_NoUser($connection, $login_database_name, $login_table_name){
        echo "<h4> Unit Test: No user, real password </h4>";
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $authentication_request = array('username'  => "",
                                 'password_hashed'  => cryptPass('2scca/.w4[]3=µ'));
        
        $result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
    
        if($result === False){
            echo "Test: Passed. <br /> <br /> <hr>";
        }
        else{
            echo "Test: Failed. <br /> <br /> <hr>";
        }
    };
    
    function unit_NoPass($connection, $login_database_name, $login_table_name){
        echo "<h4> Unit Test: No password, real user </h4>";
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $authentication_request = array('username'  => "User1",
                                 'password_hashed'  => '');
        
        $result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
    
        if($result === False){
            echo "Test: Passed. <br /> <br /> <hr>";
        }
        else{
            echo "Test: Failed. <br /> <br /> <hr>";
        }
    };
    
    function unit_MysqlInjection_Pass($connection, $login_database_name, $login_table_name){
        echo "<h4> Unit Test: MYSQL Injection -- Password </h4>";
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $authentication_request = array('username'  => "User1",
                                 'password_hashed'  => cryptPass("randompass' OR '1=1"));
        
        $result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
    
        if($result === False){
            echo "Test: Passed. <br /> <br /> <hr>";
        }
        else{
            echo "Test: Failed. <br /> <br /> <hr>";
        }
    }; 
        
    function unit_MysqlInjection_User($connection, $login_database_name, $login_table_name){
        echo "<h4> Unit Test: MYSQL Injection -- Username </h4>";
        
        
        mysql_select_db($login_database_name, $connection) or die(mysql_error());
        
        $authentication_request = array('username'  => "randomUser' OR '1=1",
                                 'password_hashed'  => cryptPass('password1'));
        
        $result = validate_Authentication_Request($authentication_request, $connection, $login_database_name,$login_table_name);
    
        if($result === False){
            echo "Test: Passed. <br /> <br /> <hr>";
        }
        else{
            echo "Test: Failed. <br /> <br /> <hr>";
        }
    }; 
        
    
    populateTable($connection, $login_database_name, $login_table_name);
    displayUserTable($connection, $login_database_name, $login_table_name);
    
    
    unit_NoUser_NoPass($connection, $login_database_name, $login_table_name);
    
    unit_NoUser($connection, $login_database_name, $login_table_name);
    
    unit_NoUser($connection, $login_database_name, $login_table_name);
 
    unit_NoPass($connection, $login_database_name, $login_table_name);
 
    unit_MysqlInjection_Pass($connection, $login_database_name, $login_table_name);
    
    unit_MysqlInjection_User($connection, $login_database_name, $login_table_name);
    
?>