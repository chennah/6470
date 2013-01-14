$(document).ready(function(){

     //get cookie  
    // alert('alert');
    // alert($.cookie('Message_LoginSuccess'));
    // alert($.cookie('Message_LoginSuccess') == 'Success'); 
    
    // Check if cookie exists
    if($.cookie('Message_LoginSuccess') == 'Success'){
        // alert('Entered the if block');
       
        $('#LoginStatus').html(' <p>Login Succeeded </p>');
        
        $.removeCookie('Message_LoginSuccess', {path: '/'} );
        
    }
    else if($.cookie('Message_LoginSuccess') == 'Failed'){
        // alert('Entered the else-if block'); 
        
        $('#LoginStatus').html('<p> Login Failed: invalid username and/or password </p>');
        
        $.removeCookie('Message_LoginSuccess', {path: '/'} );
        
    }

    
});




/**********************************************/