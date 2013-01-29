$(document).ready(function(){

     //get cookie  
      //  alert('alert');
    //	alert($.cookie('Message_LoginSuccess'));
    	//alert($.cookie('Message_LoginSuccess') == 'Success'); 
    //	alert('are you here');
    // Check if cookie exists
    if($.cookie('Message_LoginSuccess') == 'Success'|| $.cookie('Status_Login_RAND')!=null){
        var a =$.cookie('Status_Login_Username');
        $('#headline1').html('Welcome '+a);
                
    }
    else if($.cookie('Message_LoginSuccess') == 'Failed'){
      //   alert('Entered the else-if block'); 
        
        $('#LoginStatus').html('<p> Login Failed: invalid username and/or password </p>');
        
    }
	
	$.removeCookie('Message_LoginSuccess', {path: '/'} );

    
});