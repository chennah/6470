$(document).ready(function(){

	// get cookie
    // alert('load?');	
    // alert($.cookie('Message_RegistrationSuccess'));
    // alert($.cookie('Message_RegistrationSuccess') == 'ExistingUser'); 
    
    // Check if cookie exists
    if($.cookie('Message_RegistrationSuccess') == 'ExistingUser'){
       // alert('Entered the if block');
       
        $('#RegistrationAttemptStatus').html(' <p> Username: \'' + $.cookie('Message_RegistrationSuccess_User') + '\' already exists. Please choose another name. </p>');
               
    }
    else if($.cookie('Message_RegistrationSuccess') == 'UserCreated'){
       // alert('Entered the else if block');
       
        $('#RegistrationAttemptStatus').html(' <p> User Created: \'' + $.cookie('Message_RegistrationSuccess_User') + '\'  </p>');
               
    }
	
	$.removeCookie('Message_RegistrationSuccess', {path: '/'});
        $.removeCookie('Message_RegistrationSuccess_User', {path: '/'});       
    
});




/**********************************************/