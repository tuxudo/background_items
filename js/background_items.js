var format_background_items_yes_no = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-success">'+i18n.t('No')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-danger">'+i18n.t('Yes')+'</span>' : colvar)
    col.html(colvar)
}

var format_background_items_yes_no_rev = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-danger">'+i18n.t('No')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-success">'+i18n.t('Yes')+'</span>' : colvar)
    col.html(colvar)
}