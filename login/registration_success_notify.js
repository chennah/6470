$(document).ready(function(){

    // get cookie  
    //alert($.cookie('Message_RegistrationSuccess'));
   // alert($.cookie('Message_RegistrationSuccess') == 'Success'); 
    
    // Check if cookie exists
    if($.cookie('Message_RegistrationSuccess') == 'ExistingUser'){
       // alert('Entered the if block');
       
        $('#RegistrationAttemptStatus').html(' <p> Username: ' + $.cookie('Message_RegistrationSuccess_User') + 'already exists. Please choose another name. </p>');
        
        $.removeCookie('Message_RegistrationSuccess');
        
    }
    else if($.cookie('Message_RegistrationSuccess') == 'UserCreated'){
    //    alert('Entered the else-if block'); 
        
        $('#RegistrationAttemptStatus').html('<p> User Created </p>');
        
        $.removeCookie('Message_RegistrationSuccess');
        
    }

    
});




/**********************************************/