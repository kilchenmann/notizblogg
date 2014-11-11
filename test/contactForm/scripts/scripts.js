$(function(){
    //CONTACT FORM AJAX SUBMIT
    $('#contactForm').submit(function(){

		$.ajax({
			url:'mailer.php',
			type : 'POST',
			dataType: 'json',
			data: $(this).serialize(),
			success: function(data){
				if(data.error){
					$('#error').css('display','block');
				}else {
					$('#note').show();
					$('#error').hide();
					$("#fields").hide();
				}

			}
		});
        return false;
    });


});
