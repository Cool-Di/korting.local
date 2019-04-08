$(function() {
    $( "#modelAutocomplite" ).autocomplete({
        source: jsonProducts,
        select: function( event, ui ) {
            //$(this).data("id", ui.item.value );
            //$(this).val( ui.item.label );
            p_template	= ReportForm.p_template;
            p_template	= '<tr>' + p_template + '</tr>';
            p_template	= p_template.replace(/{ID}/g, ui.item.value);
            p_template	= p_template.replace(/{NAME}/g, ui.item.label);

            p_template	= p_template.replace(/{ARTICLE}/g, ui.item.article);

            $('form.report_form table.product_list tbody').append(p_template);

            if($('form.report_form table.product_list').is(':hidden'))
                $('form.report_form table.product_list').slideDown('slow');

            return false;
        }
    });
});