$(function () {
  $('.cdate-field').datepicker({
      dateFormat: 'yy/mm/dd'
    });

  if ($('#ctipo_asignacion').val() == "1") {
    $('#combo-tipo-unidad').show();
  } else if ($('#ctipo_asignacion').val() == "2") {
    $('#combo-tipo-unidad').hide();
  }

  if (window.location.href.indexOf("inscripciones") >= 0) {
    $('table').table('add_filter', $('#form-filtrar'));

    $('#unidad').autocomplete({
      source: function (request, response) {
        $.ajax({
          url: $("#unidad_url").val(),
          dataType: 'json',
          data: request,
          success: function (data) {
            response(data);
          }
        })
      },
      minLength: 2,
      select: function( event, ui ) {
        $( "#unidad" ).val( ui.item.name );
        $( "#unidad" ).trigger('change');
        return false;
      }
    })
    .autocomplete('instance')._renderItem = renderItem;

    $('#zona').autocomplete({
      source: function (request, response) {
        $.ajax({
          url: $("#zona_url").val() + '&unidad=' + $('#unidad').val(),
          dataType: 'json',
          data: request,
          success: function (data) {
            response(data);
          }
        })
      },
      minLength: 0,
      select: function( event, ui ) {
        $( "#zona" ).val( ui.item.name );
        $( "#zona" ).trigger('change');
        return false;
      }
    })
    .autocomplete('instance')._renderItem = renderItem;
  }

  $('#ctipo_asignacion').on('change', function (e) {
    e.preventDefault();
    if ($(this).val() == "1") {
      $('#combo-tipo-unidad').show();
    } else if ($(this).val() == "2") {
      $('#combo-tipo-unidad').hide();
    }
  });

  $(document).on('change', ':checkbox', function (e) {
    e.preventDefault();
    $this = $(e.target);
    if ($this.data('is') == 'checked') {
      $this.prop('checked', true);
    }
  });

  // Test 
  $('.add-opcion').on('click', function (e) {
    e.preventDefault();
    var length = $('.tbody-opciones tr').length;
    if (length <= 5) {
      var template = '<tr>' +
                      '<td><input type="text"></td>' +
                      '<td><a href="#"" class="delete-opcion">Eliminar</a></td>' +
                      '<td><input class="radio-respuesta" name="respuesta" type="radio" value="' + length + '"></td>' +
                    '</tr>';
      $('.tbody-opciones').append(template);
    } else {
      showMessage("Solo se puede agregar hasta 6 opciones", 'error');
    }
  });

  $(document).on('click', '.delete-opcion', function (e) {
    e.preventDefault();
    var length = $('.tbody-opciones tr').length;
    if (length > 2) {
      var $tr = $(this).parents('tr');
      $tr.remove();
      reasignarValoresARadioRespuesta();
    } else {
      showMessage('Deben haber como mínimo 2 opciones', 'error');
    }
  });

  function reasignarValoresARadioRespuesta(){
    $('.radio-respuesta').each(function (idx, elem) {
      $(elem).prop('value', idx);
    });
  }

  function showMessage(msg, type) {
    $.ee_notice(msg, {open: true, type: 'error'});
    setTimeout(function () {
      $.ee_notice.destroy();
    }, 3000);
  }

  function renderItem( ul, item ) {
    return $( "<li>" )
      .append( "<div>" + item.name +  "</div>" )
      .appendTo( ul );
  };
});