function deleteData(e, handleDeleteFunction) {
  Swal.fire({
    title: '本当に削除してもよろしいですか？',
    html: '<label><input type="checkbox" id="confirmCheckbox"> 本当に削除する場合は、チェックしてください</label>',
    icon: 'warning',
    showCancelButton: true,
    showCloseButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'YES',
    cancelButtonText: 'NO',
    customClass: {
      confirmButton: 'min-wid-110',
      cancelButton: 'min-wid-110'
    },
    onOpen: function () {
      Swal.getConfirmButton().setAttribute('disabled', 'true');
      document.getElementById('confirmCheckbox').addEventListener('change', function () {
        const checkboxChecked = this.checked;
        Swal.getConfirmButton().disabled = !checkboxChecked;
      });
    },
    preConfirm: () => {
      const checkboxChecked = document.getElementById('confirmCheckbox').checked;
      if (!checkboxChecked) {
        Swal.showValidationMessage('Please agree to proceed');
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      if(typeof window[handleDeleteFunction] === 'function') {
        window[handleDeleteFunction](e);
        return false;
      }
      if (typeof handleDelete === 'function') {
        handleDelete(e)
      }
    }
  });
}

function redirectBack(e, namePage) {
  var form = $('<form>', {
    'action': $(e).data('href'),
    'method': 'POST',
  });

  form.append($('<input>', {
    'type': 'hidden',
    'name': '_token',
    'value': $('meta[name="csrf-token"]').attr('content')
  }));
  var inputs = $('[name^="' + namePage + '"]');
  inputs.each(function () {
    var inputName = $(this).attr('name');
    var strippedName = inputName.slice(namePage.length + 1, -1);
    form.append($('<input>', {
      'type': 'hidden',
      'name': strippedName,
      'value': $(this).val()
    }))
  });

  form.appendTo('body').submit().remove();
}


function redirectForm(e, $formSearch, namePage, $table) {
  var form = $('<form>', {
    'action': $(e).data('href'),
    'method': 'POST',
  });
  if ($formSearch) {
    $formSearch.find('input, select').each(function () {
      form.append($('<input>', {
        'type': 'hidden',
        'name': namePage + '[' + $(this).attr('name') + ']',
        'value': $(this).val()
      }));
    });
  }
  if ($table) {
    var paramsTable = $('#table').customTable.getQueryParams();
    $.each(paramsTable, function (key, value) {
      form.append($('<input>', {
        'type': 'hidden',
        'name': namePage + '[' + key + ']',
        'value': value
      }));
    });
    var settings = $('#table').data('customTableSettings');
    var isShow = settings.isShow;

    if (isShow) {
      form.append($('<input>', {
        'type': 'hidden',
        'name': namePage + '[isShowCustomTable]',
        'value': 1
      }));
    }
  }

  form.append($('<input>', {
    'type': 'hidden',
    'name': '_token',
    'value': $('meta[name="csrf-token"]').attr('content')
  }));
  form.appendTo('body').submit().remove();
}

function submitForm(e) {
  var action = $(e).parents('form').attr('action');
  var form = $(e).parents('form');
  $.ajax({
    url: action,
    method: 'POST',
    data: form.serialize(),
    success: function (res) {
      if (res.status == 200) {
        if(isUseIframe ) {
          sendDataSuccessFromiFrameToParent();
        } else {
          $('#backButton').click();
        }
      } else {
        Swal.fire({
          title: res.message,
          icon: "error"
        });
      }
    },
    beforeSend: function () {
      $('.error-summary').hide();
      $('body').append(`<div id="loading" class="loading">
              <div class="ten">
                <div class="dot dot1"></div>
                <div class="dot dot2"></div>
                <div class="dot dot3"></div>
              </div>
            </div>`);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error('AJAX Error:', textStatus, errorThrown);
      if (jqXHR.status == 422) {
        $('.error-summary').show();
        $('.group-input').removeClass('error');
        $('.error_message span').html('');
        var errors = jqXHR.responseJSON.errors;
        $.each(errors, function (key, value) {
          if($('[name="'+key+'"]').parents('.group-input').hasClass('group-s-input')) {
            var row = $('[name="'+key+'"]').parents('.group-s-input');
            row.find('.error-message-row').addClass('active').html(value);
            $('[name="'+key+'"]').addClass('error-input');
          } else {
            form.find('#error-' + key).parents('.group-input').addClass('error');
            form.find('#error-' + key).html(value);
          }
        });
      }
    },
    complete: function () {
      $('#loading').remove();
    }
  })
}

function suggestionForm(e, field, fieldShow, fieldChange, $row, setCallback) {
  const value = $(e).val();
  if (keyDownCode == 38 || keyDownCode == 40) {
    focusItemSuggestion(e, keyDownCode, -1);
    return;
  } else if (keyDownCode == 13) {
    enterItemSuggestion(e, -1);
    return;
  }
  debouncedFetchData(e, value, field, fieldShow, fieldChange, $row, setCallback);
  $(e).on('blur', function () {
    $(e).parent().find('.suggestion').hide();
  })
}

const debouncedFetchData = debounce(
  function (e, value, field, fieldShow, fieldChange, $row, setCallback) {
    fetchData(e, value, field, fieldShow, fieldChange, $row, setCallback);
  }, 300);

function debounce(func, delay) {
  let timer;
  return function () {
    const context = this;
    const args = arguments;
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(context, args);
    }, delay);
  };
}

function fetchData(e, value, field, fieldShow, fieldChange, $row, setCallback) {
  if (value != '') {

    if (fieldShow == '' && fieldChange == '') {
      return false;
    }
    searchSuggestion(field, value).then(function (res) {
      var html = '';
      if (res.data) {
        res.data.forEach(function (item) {

          if (fieldChange == '') {
            if (fieldShow && Array.isArray(fieldShow)) {
              html = html + '<li value="' + item[field] + '"';
              for (let i = 0; i < fieldShow.length; i++) {
                var dataLi = item[fieldShow[i]] ?? '';
                html = html + ' data-' + fieldShow[i] + '=' + '"' + dataLi + '"';
              }
              html = html + '>';

            }
          } else if (typeof fieldChange === 'object' && Object.keys(fieldChange).length > 0) {
            html = html + '<li value="' + item[field] + '"';
            Object.keys(fieldChange).forEach(function (key) {
              // console.log(key, fieldChange[key]);
              if (item[key] != null) {
                var dataLi = item[key] ?? '';
                html = html + ' data-' + fieldChange[key] + '=' + '"' + dataLi + '"';
              }

            });
            html = html + '>';
          }

          if (fieldShow && Array.isArray(fieldShow)) {
            for (let i = 0; i < fieldShow.length; i++) {
              var textLi = item[fieldShow[i]] ?? '--';
              html = html + textLi + '　';
            }
            html = html + '</li>';
          }
        });
        // $row.find('.suggestion').show();
        // $row.find('.suggestion').html(html);

        // suggestionList = $row.find('.suggestion');
        var $e = $(e);
        $e.parent().find('.suggestion').show();
        $e.parent().find('.suggestion').html(html);

        suggestionList = $row.find('.suggestion');
        suggestionList.on('mousedown', 'li', function (e) {

          e.stopPropagation();

          if (typeof setCallback === 'function') {
            setCallback(this, field, fieldShow, $row);
            $('.suggestion').hide();
            return;
          }
          var selectedField = field;
          if (fieldChange == '') {
            if (fieldShow && Array.isArray(fieldShow)) {
              for (let i = 0; i < fieldShow.length; i++) {
                $row.find('input[name="' + fieldShow[i] + '"]').val($(this).attr('data-' + fieldShow[i]));
              }
            }
          } else if (typeof fieldChange === 'object' && Object.keys(fieldChange).length > 0) {
            var __this = $(this);
            Object.keys(fieldChange).forEach(function (key) {
              $row.find('input[name="' + fieldChange[key] + '"]').
                val(__this.attr('data-' + fieldChange[key]));
            });
          }
          $('.suggestion').hide();

        });
      }
    }).catch(function () {

    });
  }
}


function addExportExcelDataTableOutSide() {
  var settings = $('#table').data('customTableSettings');
  // Get query parameters from the Bootstrap Table
  var params = $('#table').customTable.getQueryParams();

  // Create a form for submitting the parameters to the export URL
  var form = $('<form>', {
    'action': settings.urlExportExcelDataTable,
    'method': 'POST',
    'target': '_blank' // Open the export URL in a new tab/window
  });

  // Append hidden input fields for each parameter
  $.each(params, function (key, value) {
    form.append($('<input>', {
      'type': 'hidden',
      'name': key,
      'value': value
    }));
  });
  form.append($('<input>', {
    'type': 'hidden',
    'name': '_token',
    'value': $('meta[name="csrf-token"]').attr('content')
  }));
  // Append the form to the body, submit it, and remove it
  form.appendTo('body').submit().remove();
}

//FORMAT GRID
function formatNumber(value, row, index) {
  return numberFormat(value, -1);
}

function numberFormat(number, decimals = 0, thousandsSeparator = ',') {
  if (!isNumeric(number)) return number;
  if (decimals === -1) {
    var arrNumber = String(number).split('.');
    var result = arrNumber[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
    if (arrNumber[1]) {
      // Remove trailing zeros from the decimal part
      var trimmedDecimal = rtrim(arrNumber[1], '0');
      // If there are non-zero digits left after trimming, append a decimal point
      result += trimmedDecimal !== '' ? '.' + trimmedDecimal : '';
    }
    return result;
  } else {
    roundedNumber = parseFloat(number).toFixed(decimals);
    var parts = roundedNumber.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
    if (!parts[1]) parts.push('');
    parts[1] = parts[1].padEnd(decimals, '0');
    return parts.join('.');
  }
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function rtrim(str, char) {
  while (str.charAt(str.length - 1) === char) {
    str = str.slice(0, -1);
  }
  return str;
}


function focusItemSuggestion(e, keyCode, $parent) {
  var currentInput = $(e);
  if ($parent == -1) {
    if ($(e).attr('parent')) {
      var currentTh = $($(e).attr('parent'));
    } else {
      var currentTh = currentInput.parent();
    }
  } else {
    var currentTh = $parent;
  }
  var suggestionList = currentTh.find('.suggestion');

  // push arrow down.
  if (keyCode == 40) {
    if (suggestionList.find("li.key-focusing").length == 0) {
      suggestionList.find("li:first-child").addClass("key-focusing");
    } else {
      var liIdx = suggestionList.find("li.key-focusing").index();
      if (liIdx >= 0 && liIdx < suggestionList.find("li").length - 1) {
        suggestionList.find("li.key-focusing").removeClass("key-focusing");
        $(suggestionList.find("li")[liIdx + 1]).addClass("key-focusing");
        suggestionList.scrollTop(liIdx * 36);
      }
    }
  }

  // push arrow up.
  if (keyCode == 38) {
    var liIdx = suggestionList.find("li.key-focusing").index();
    if (liIdx > 0) {
      suggestionList.find("li.key-focusing").removeClass("key-focusing");
      $(suggestionList.find("li")[liIdx - 1]).addClass("key-focusing");
      suggestionList.scrollTop(liIdx * 36 - 36);
    }
  }
}

function enterItemSuggestion(e, $parent) {
  var currentInput = $(e);
  if ($parent == -1) {
    if ($(e).attr('parent')) {
      var currentTh = $($(e).attr('parent'));
    } else {
      var currentTh = currentInput.parent();
    }
  } else {
    var currentTh = $parent;
  }
  var suggestionList = currentTh.find('.suggestion');
  suggestionList.find("li.key-focusing").mousedown();
}

function extractDigits(inputString) {
  return inputString.replace(/[^\d-.]/g, '');
}

function formatNumberOnBlur(input) {
  var value = input.value;
  if(value.trim() !== '') {
    var parts = value.split('.');
    var digitsOnly = extractDigits(parts[0]);
    var decimalPart = parts[1] || '';
    var parsedValue = parseFloat(digitsOnly);
    if (!isNaN(parsedValue)) {
      var formattedValue = new Intl.NumberFormat('en-US').format(parsedValue);
      if(decimalPart) {
        input.value = formattedValue + '.' + decimalPart;
      } else {
        input.value = formattedValue;
      }
    } else {
      console.error('ERROR');
      $(input).val('');
    }
  } else {
    $(input).val('');
  }
}

function removeFormatOnFocus(input) {
  var value = input.value;
  var unformattedValue = value.replace(/,/g, '');
  input.value = unformattedValue;
}

function initFormatNumber(value) {
  if(value) {
    var formattedValue = parseFloat(value).toLocaleString();
    return formattedValue;
  } else {
    return '';
  }
}

function sendDataSuccessFromiFrameToParent()
{
  var data = 'success';
  parent.postMessage(data, '*');
}

window.addEventListener('message', listenParentIframe, false);

function listenParentIframe(value) {
  if(value.data == 'success') {
    $('#modalCreate').modal('hide');
  }
}

function convertDateFormat(dateString, format = 'yyyy/mm/dd') {
  if(!dateString)
  return '-';
  let date = new Date(dateString);
  let year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
  let month = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(date);
  let day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);

  let formattedDate = format
    .replace('yyyy', year)
    .replace('mm', month)
    .replace('dd', day);

  if (format.includes('h:i:s')) {
    formattedDate = formattedDate.replace('h:i:s', '');
    let hours = new Intl.DateTimeFormat('en', { hour: '2-digit', hour12: false }).format(date);
    let minutes = new Intl.DateTimeFormat('en', { minute: '2-digit' }).format(date);
    let seconds = new Intl.DateTimeFormat('en', { second: '2-digit' }).format(date);

    formattedDate += ` ${hours}:${minutes}:${seconds}`;
  }

  return formattedDate;
}

function onlyNumber(event) {
  const charCode = (event.which) ? event.which : event.keyCode;
  const inputValue = event.target.value;

  var maxLengh = $(event.target).data('length');
  if(maxLengh) {
    var value = $(event.target).val();
    if (charCode === 45 && event.target.selectionStart === 0 && inputValue.indexOf('-') === -1) {
      return true;
    }
    if(inputValue.indexOf('-') !== -1) {
      maxLengh += 1;
    }
    if(value.length <= maxLengh - 1) {
      // return true;
    } else {
      event.preventDefault();
      return false;
    }
  }
  // If the character is a digit from 0 to 9
  if (charCode >= 48 && charCode <= 57) {
    return true; // Allow input
  }
  // If the character is a minus sign and there is no minus sign in the string
  else if (charCode === 45 && inputValue.indexOf('-') === -1) {
    // If the minus sign is at the beginning of the string or there is no value in the input field
    if (inputValue.length === 0 || event.target.selectionStart === 0) {
      return true; // Allow input
    }
  }

  event.preventDefault();
  return false;
}


function onlyNumberDecimal(event) {
  const charCode = (event.which) ? event.which : event.keyCode;
  const inputValue = event.target.value;
  const hasDecimalPoint = inputValue.indexOf('.') !== -1;

  var maxLength = $(event.target).data('length');
  if (maxLength) {
    var value = $(event.target).val();
    if (charCode === 45 && event.target.selectionStart === 0 && inputValue.indexOf('-') === -1) {
      return true;
    }
    if (inputValue.indexOf('-') !== -1) {
      maxLength += 1;
    }
    if (inputValue.indexOf('.') !== -1) {
      maxLength += 1;
    }
    if (value.length <= maxLength - 1) {
    } else {
      event.preventDefault();
      return false;
    }
  }
  if ((charCode >= 48 && charCode <= 57) || charCode === 46) {
    if (charCode === 46 && !hasDecimalPoint || (charCode >= 48 && charCode <= 57)) {
      return true;
    }
  }
  else if (charCode === 45 && inputValue.indexOf('-') === -1) {
    if (inputValue.length === 0 || event.target.selectionStart === 0) {
      return true;
    }
  }

  event.preventDefault();
  return false;
}

function loading(onFlag = true) {
  if (onFlag) {
    let elements = [];
    elements.push('<div id="loading" class="loading">');
    elements.push('<div class="ten">');
    elements.push('<div class="dot dot1"></div>');
    elements.push('<div class="dot dot2"></div>');
    elements.push('<div class="dot dot3"></div>');
    elements.push('</div>');
    elements.push('</div>');
    $('body').append(elements.join(""));
  } else {
    $('#loading').remove();
  }
}

function formatDateGrid(value, row, index) {
  return convertDateFormat(value);
}
function closePage() {
  if($('.bootstrap-table').length > 0) {
    var row = $('#table tfoot tr');
    row.find('input, select').each(function () {
      var value = $(this).val();
      if (value != null && value.trim() !== '') {
        hasChangeData = true;
      }
    });
  }
  if(hasChangeData) {
    var cf = confirm('未更新の入力データがありますが、終了しますか？');
    if(cf) {
      window.close();
    }
  } else {
    window.close();
  }
}