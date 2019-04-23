$(function() {
    $( "#modelAutocomplite" ).autocomplete({ //дублируется функционал при выборе модели из списка
        source: jsonProducts,
        select: function( event, ui ) {

            if(ReportForm.IsItemExist(ui.item.value))
                return false;

            p_template	= ReportForm.p_template;
            p_template	= '<tr>' + p_template + '</tr>';
            p_template	= p_template.replace(/{ID}/g, ui.item.value);
            p_template	= p_template.replace(/{NAME}/g, ui.item.label);
            p_template	= p_template.replace(/{ARTICLE}/g, ui.item.article);
            p_template	= p_template.replace(/{POINTS}/g, ui.item.points);
            p_template	= p_template.replace(/{TOTAL_PRICE}/g, ui.item.points); //сумма = цена * 1

            $('form.report_form table.product_list tbody').append(p_template);

            if($('form.report_form table.product_list').is(':hidden'))
                $('form.report_form table.product_list').slideDown('slow');

            ReportForm.ReCalcResultPrice();

            this.value = ""; //обнуляем инпут

            return false;
        }
    });
    $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        minDate: new Date(startDate),
        maxDate: new Date(endDate)
    });

    /*$('.add_filefield').click(function(){
        console.log(1);
    });*/

    //BX.onCustomEvent(this.parentNode, 'BFileDLoadFormController');
});

/*
function addFileFiled() {
    $('.files-block').append('<input type="file" name="FIELDS[FILES][]" />');
}*/
