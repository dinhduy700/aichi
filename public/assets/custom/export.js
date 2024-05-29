var exportJs = {
  formId:'formId',
  urls: {
    masterSuggestion: '/master/suggestion',
    validate: 'export/validate',
  },
  suggestColumns: [],
  expSuggestionKeyup: function(e, fieldCd, fieldNm) {
    var currentInput = $(e);
    var field = $(e).attr('base');
    var showFields = exportJs.suggestColumns[field]['suggestion_show'];
    var changeFields = {};
    changeFields[showFields[0]] = fieldCd;
    changeFields[showFields[2]] = fieldNm;
    suggestionForm(
        currentInput, field,
        showFields,
        changeFields,
        currentInput.parent()
    );
  },
  documentReady: function() {
    $('.btn-xls-export, .btn-csv-export, .btn-pdf-export').click(function() {
      exportJs.elementClick(this);
    });
    $('.datepicker').change(function() {
      autoFillDate(this);
    });
    var tbl = $('<div>').attr({id: 'table'});
    tbl.data('customTableSettings', {urlSearchSuggestion:exportJs.urls.masterSuggestion});
    $('#' + exportJs.formId).append(tbl);
  },
  elementClick: function(btn) {
    var form = $('#' + exportJs.formId);
    $('.group-input').removeClass('error');
    $('.error_message span').html('');
    $.ajax({
      url: exportJs.urls.validate,
      method: 'POST',
      data: form.serialize(),
      success: function(res) {
        if (res.sendForward == 'no') {
          alert(res.message);
          return;
        }
        form.attr('action', $(btn).attr('href')).submit();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        //console.error('AJAX Error:', textStatus, errorThrown);
        if(jqXHR.status == 422) {
          $('.group-input').removeClass('error');
          $('.error_message span').html('');
          var errors = jqXHR.responseJSON.errors;
          $.each(errors, function(key, value) {
            key = key.replace('exp.', '');
            form.find('#error-'+key).parents('.group-input').addClass('error');
            form.find('#error-'+key).html(value);
          });
        }
      },
      complete: function() {

      }
    });
  }
};
