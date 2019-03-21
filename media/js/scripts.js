$(document).ready(function() {
	
	if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
		scrollDom = 'html,body';
	}else{
		scrollDom = 'html';
	}
	
	$(window).scroll(function() {
		if($(window).scrollTop() > 500)
			$('#onTop').show();
		else	
			$('#onTop').hide();
	});
	
	$('#onTop').click(function(){
		//$(window).scrollTop(0);
		jQuery(scrollDom).animate({scrollTop:0}, 'fast');
	});

	$('a.write').click(function(){
		$(this).next().slideToggle('slow', function() {});
		return false;
	});
	$('a.post').click(function(){
		form = $(this).parents('form').serialize();
		//alert(form[0]['name']);
		//console.log(form[]);
		//jQuery.each(fields, function(i, field){
		//	$("#results").append(field.value + " ");
		//});
		//alert(form.name);
		_this = $(this);
		$.ajax({
			type: "POST",
			url: "sign_up_for_event.php",
			data: form
		}).done(function( result ) {
			if(result!=""){
				errors = '';
				res = jQuery.parseJSON(result);
				jQuery.each(res, function(i, val) {
					errors+=val+"<br/>";
					//alert(val);
			   });
			 //  errors = result.join('<br/>');
			   
			   _this.parents('div.write_form').find('div.result').html(errors);
			}else{
				 _this.parents('div.write_form').html("<b>Спасибо, вы записаны на данное событие</b>");
			};
		});
		return false;
	});
	
	//Селект компании в форме регистрации
	if($('select.company_list').val() == -1)
		$('input[name="new_company"]').show();
	else
		$('input[name="new_company"]').hide();
		
	$('select.company_list').change(function(){
		if($(this).val() == -1)
			$('input[name="new_company"]').show();
		else
			$('input[name="new_company"]').hide();
	});

	// calendar tab 
	/*$('#calendar a.item').click(function(){
		$('#calendar ul li').removeClass('active');
		$('#calendar div.wrap_table').removeClass('active');
		$(this).parent().addClass('active');
		index = $(this).parent().index();
		$(this).parents('div.wrap_calendar').find('div.wrap_table:eq('+index+')').addClass('active');
		return false;
	})*/
	// detail event
	$('div.wrap_event div.detail a.close').click(function(){
		$(this).parents('div.detail').hide();
		return false;
	})
	$('a.event').click(function(){
		$(this).parent().find('div.detail').css('bottom',$(this).height()+9).show();
		return false;
	})

	// next question
	$('a.next_question').click(function(){
		var is_checked = false;
		$(this).parents('.questions').find('input').each(function(){	
			if($(this).is(':checked') == true)
				is_checked = true;
		})
		if (!is_checked){
			alert("Выберите вариант ответа!");
			return false;
		}
	})
	
	// menu
	$('ul.left_menu > li table a.child_active').each(function(){
		//$(this).parent('li.top').addClass('active');
		//alert($(this).parents('li.top').html());
		$(this).parents('li.top').addClass('active');
		$(this).parents('li.top').find('> a').addClass('active');
		$(this).parents('li.top').find('ul.cat_menu').slideDown();
	});
	
	//$('ul.left_menu li a:not(.cat)').click(function(){
	$('ul.left_menu > li > a').click(function(){
		if($(this).next('ul').size() > 0)
		{
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parent().removeClass('active');
				$(this).parent().find('ul.cat_menu').slideUp();
			}else{
				$(this).addClass('active');
				$(this).parent().addClass('active');
				$(this).parent().find('ul.cat_menu').slideDown();
			}
			
			return false
		}
	
		//return false;
	});
	
	$('ul.children_section > li > table a').click(function(){
	
		if($(this).parents('ul.children_section li').find('ul').size() > 0)
		{
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parents('ul.children_section li').find('ul').slideUp();
			}else{
				$(this).addClass('active');
				$(this).parents('ul.children_section li').find('ul').slideDown();
			}
			
			return false
		}
	
		return false;
	});
});