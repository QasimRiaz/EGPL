       

jQuery(document).ready(function() {
	var password1 		= jQuery('#newpassword'); //id of first password field
	var password2		= jQuery('#confirmpassword'); //id of second password field
	var passwordsInfo 	= jQuery('#pass-info'); //id of indicator element
	
	passwordStrengthCheck(password1,password2,passwordsInfo); //call password check function
	
});

function change_password_custome(){
    
    
    var newpassword =jQuery('#newpassword').val();
    var password2 =jQuery('#confirmpassword').val();
    
    if(newpassword == password2){
  
    // console.log(newpassword);
    jQuery("body").css({'cursor':'wait'});
    var url = window.location.protocol + "//" + window.location.host + "/";
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=changepassword';
    var data = new FormData();
    data.append('newpassword', newpassword);
   
    
     jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                
                 jQuery("form")[0].reset();
            
                
                 swal({
					title: "Success",
					text: 'Password Successfuly Changed.',
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				},function() {
                                                                    window.location.replace(url);
                                                                 }
                            
            );
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     jAlert('There was an error during the requested operation. Please try again.'); 
      }
        });
    }else{
        
        
        
         jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-error"><div class="messagebox_text"><p>Passwords do not match!.</p></div></div>' );
                 setTimeout(function() {
                        jQuery( "#sponsor-status" ).empty();
                 }, 2000); // <-- time in milliseconds
    }
    
    
}



/*
        jQuery(document).ready(function () {
            "use strict";
            var $password = $(':password').pwstrength(),
                common_words = ["password", "god", "123456"];

            $password.pwstrength("addRule", "notEmail", function (options, word, score) {
                return word.match(/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i) && score;
            }, -100, true);

            $password.pwstrength("addRule", "commonWords", function (options, word, score) {
                var result = false;
                $.each(common_words, function (i, item) {
                    var re = new RegExp(item, "gi");
                    if (word.match(re)) {
                        result = score;
                    }
                });
                return result;
            }, -500, true);
        });
        
        */




/*jslint vars: false, browser: true, nomen: true, regexp: true */
/*global jQuery */

/*
* jQuery Password Strength plugin for Twitter Bootstrap
*
* Copyright (c) 2008-2013 Tane Piper
* Copyright (c) 2013 Alejandro Blanco
* Dual licensed under the MIT and GPL licenses.
*
*/


function passwordStrengthCheck(password1, password2, passwordsInfo)
{
	//Must contain 5 characters or more
	var WeakPass = /(?=.{5,}).*/; 
	//Must contain lower case letters and at least one digit.
	var MediumPass = /^(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
	//Must contain at least one upper case letter, one lower case letter and one digit.
	var StrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
	//Must contain at least one upper case letter, one lower case letter and one digit.
	var VryStrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=\S*?[^\w\*])\S{5,}$/; 
	
	jQuery(password1).on('keyup', function(e) {
		if(VryStrongPass.test(password1.val()))
		{
			passwordsInfo.removeClass().addClass('vrystrongpass').html("Very Strong! (Awesome, please don't forget your pass now!)");
		}	
		else if(StrongPass.test(password1.val()))
		{
			passwordsInfo.removeClass().addClass('strongpass').html("Strong! (Enter special chars to make even stronger");
		}	
		else if(MediumPass.test(password1.val()))
		{
			passwordsInfo.removeClass().addClass('goodpass').html("Good! (Enter uppercase letter to make strong)");
		}
		else if(WeakPass.test(password1.val()))
    	{
			passwordsInfo.removeClass().addClass('stillweakpass').html("Still Weak! (Enter digits to make good password)");
    	}
		else
		{
			passwordsInfo.removeClass().addClass('weakpass').html("Very Weak! (Must be 5 or more chars)");
		}
	});
	
	jQuery(password2).on('keyup', function(e) {
		
		if(password1.val() !== password2.val())
		{
			passwordsInfo.removeClass().addClass('weakpass').html("Passwords do not match!");	
		}else{
			passwordsInfo.removeClass().addClass('goodpass').html("Passwords match!");	
		}
			
	});
}