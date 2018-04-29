$(document).ready(function(){
    $('#add-image').click(function(){
    		 var form = new FormData();
    		 form.append('image', $("#superheroes-image")[0].files[0]); 

	    	$.ajax({
	    		headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  				},
	    		url: '/add-image',
	    		type: 'POST',
	    		dataType: 'json',
	    		data: form,
            	cache: false,
            	contentType: false,
            	processData: false,
	    		success: function(response){
	    			$('#error').html('');

	    			if(response.success){
	    				$('#all-box-images').append(
	    					"<div class='box-image'>"
					    	+"<img src="+response.file+" class='img-thumbnail'>"
					      	+"<input type='hidden' name='images[]' value="+response.file+">"
					      	+"<div class='container'>"
					        +"<center><a onClick='removeImageTemporary(this)' tmp_name="+response.file+" href='javascript:void(0)'>Remover</a></center>"
					      	+"</div>"
					      	+"</div>" 
	    				);
	    			}else{
	    				$('#error').html(
	    					"<div class='alert alert-danger errors'>"+response.error+"</div>"
	    				);
	    			}
	    		},
	    		error: function(error) {
	    			console.log(error);
	    		}
	    	});
	});

	$('.removeImageSuperHeroes').click(function(){
		$(this).parents('.box-image').remove();
	});
});

function removeImageTemporary(element, nickname = null){
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		url: '/remove-image',
		type: 'POST',
		dataType: 'json',
		data: {'image': $(element).attr('tmp_name'), 'nickname': nickname},
		success: function(response){
			$('#error').html('');

			if(response.success){
				$(element).parents('.box-image').remove()
			}else{
				$('#error').html(
					"<div class='alert alert-danger errors'>"+response.error+"</div>"
				);
			}
		},
		error: function(error) {
			console.log(error);
		}
	});
}