<?php
    //require_once './../questions_HIDDEN.php';
    
    if (is_file('./../questions_HIDDEN.php'))
        {
            echo "'is file' appeared to work <br />";
            include('./../questions_HIDDEN.php');
        }
    else
        {
            echo "'is file' did not work <br />";     
        }

    
    echo "$sql_server <br />";
    echo "$sql_password <br />";
    
    mysql_query("DROP TABLE $questions_table_name") or die(mysql_error());
    
    mysql_query("DROP TABLE $questions_args_table_name") or die(mysql_error());
    
    mysql_query("DROP TABLE $questions_answers_table_name") or die(mysql_error());

    header('Location:' . $_SERVER['HTTP_REFERER']);       




?>