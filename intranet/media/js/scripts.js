$(document).ready(function() {
	
	$('a.checked_all_report').click(function(){
		$('table.reports_table input[name="FIELDS[REPORT_ID][]"]').attr('checked', 'checked');
		
		return false;
	});
	
	$('form.plan_filter select').change(function(){
		$('form.plan_filter').submit();
	});
	
	ReportForm	= {
		Init :function()
		{
			this.p_template	= $('form.report_form table.product_list tr.p_template').html();
			
			$('form.report_form table.product_list tr.p_template').remove();
			
			if($('form.report_form table.product_list tr').size() < 2)
				$('form.report_form table.product_list').hide();
				
			/*$('form.report_form button.add_product').click(function(){
				ReportForm.AddProduct($(this));
			});*/
			
			$(document).on('change', 'form.report_form table.product_list input[name="FIELDS[PRODUCT_PRICE][]"], form.report_form table.product_list input[name="FIELDS[PRODUCT_COUNT][]"]', function(){
				
				var totla_price	= $(this).parents('tr').find('input[name="FIELDS[PRODUCT_PRICE][]"]').val() * $(this).parents('tr').find('input[name="FIELDS[PRODUCT_COUNT][]"]').val();

				$(this).parents('tr').find('td.p_price').html(totla_price);
				
				ReportForm.ReCalcResultPrice();
			});
			
			
			$(document).on('click', 'form.report_form table.product_list button.close', function(){
				$(this).parents('tr').slideUp('fast', function(){
					$(this).remove();
				});	
			});
			
			
			/*$('form.report_form button.add_model').click(function(){
				ReportForm.AddProduct($(this));
			});*/
			
			$('select.product_section').change(function(){
				$.fancybox.showLoading();
				var section_id	= $('select.product_section').val();
				var datastring	= 'action=get_product_category&section_id=' + section_id;
		
				$.ajax({
					type: "GET",
					url: '/intranet/ajax_get_product.php',
					dataType: "html",
					data: datastring,
					success: function(in_data)
					{
						$.fancybox.hideLoading();
						//alert(in_data);
						//$('select.category_section').html($('div.content', in_data).html());
						$('select.category_section').html(in_data);
						$('select.category_section').trigger('change');
					}
				});
			});
			
			$('select.product_section').trigger('change');
			
			$('select.category_section').change(function(){
				$.fancybox.showLoading();
				var section_id	= $('select.category_section').val();
				var datastring	= 'action=get_product&section_id=' + section_id;
		
				$.ajax({
					type: "GET",
					url: '/intranet/ajax_get_product.php',
					dataType: "html",
					data: datastring,
					success: function(in_data)
					{
						$.fancybox.hideLoading();
						
						//$('select.model_section').html($('div.content', in_data).html());
						$('select.model_section').html(in_data);
						//alert($('div', in_data).html());
						//$('select.category_section').trigger('change');
					}
				});
				$.fancybox.hideLoading();
			});
			
		},
		ReCalcResultPrice: function()
		{
			var totla_price	= 0;
			var totla_count	= 0;
			$('form.report_form table.product_list input[name="FIELDS[PRODUCT_COUNT][]"]').each(function(){
				totla_price	+= $(this).parents('tr').find('input[name="FIELDS[PRODUCT_PRICE][]"]').val() * $(this).val();
				totla_count += parseInt($(this).val());
			});

			$('form.report_form table.product_list td.result_price').html(totla_price);
			$('form.report_form table.product_list td.result_count').html(totla_count);
		}
		//AddProduct заменён на функцию select в autocomplite
		/*AddProduct: function(button)
		{
			var p_template 	= this.p_template;
			
			var select 		= button.parents('div.form-group').find('select');
			
			var id			= select.val();
			
			if($('form.report_form table.product_list input[name="FIELDS[PRODUCT_ID][]"][value="'+id+'"]').size() > 0)
			{
				alert('Данный товар уже добавлен в отчет');
				return;
			}
				
			//p_template	= '<tr>' + p_template + '</tr>';
			//p_template	= p_template.replace(/{ID}/g, $('select.product_list').val());
			//p_template	= p_template.replace(/{NAME}/g, $('select.product_list option:selected').text().trim());
			//p_template	= p_template.replace(/{ARTICLE}/g, $('select.product_list option:selected').attr('article'));
				
			p_template	= '<tr>' + p_template + '</tr>';
			p_template	= p_template.replace(/{ID}/g, select.val());
			//p_template	= p_template.replace(/{NAME}/g, select.find('option:selected').text().trim());
			p_template	= p_template.replace(/{NAME}/g, $.trim(select.find('option:selected').text()));

			p_template	= p_template.replace(/{ARTICLE}/g, select.find('option:selected').attr('article'));
						
			$('form.report_form table.product_list tbody').append(p_template);
			
			if($('form.report_form table.product_list').is(':hidden'))
				$('form.report_form table.product_list').slideDown('slow');
		}*/
	}

	ReportForm.Init();



});
