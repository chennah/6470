$(document).ready(function(){

    // get cookie  
    //alert($.cookie('LoginSuccessMessage'));
   // alert($.cookie('LoginSuccessMessage') == 'Success'); 
    
    // Check if cookie exists
    if($.cookie('LoginSuccessMessage') == 'Success'){
       // alert('Entered the if block');
       
        $('#LoginStatus').html(' <p>Login Succeeded </p>');
        
        $.removeCookie('LoginSuccessMessage');
        
    }
    else if($.cookie('LoginSuccessMessage') == 'Failed'){
    //    alert('Entered the else-if block'); 
        
        $('#LoginStatus').html('<p> Login Failed: invalid username and/or password </p>');
        
        $.removeCookie('LoginSuccessMessage');
        
    }

    
});




/**********************************************/