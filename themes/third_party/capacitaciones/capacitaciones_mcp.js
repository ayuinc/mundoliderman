$(function () {
  $('.cdate-field').datepicker({
      dateFormat: 'yy/mm/dd'
    });

  if ($('#ctipo_asignacion').val() == "1") {
    $('#combo-tipo-unidad').show();
    $('#check-presencial').hide();
  } else if ($('#ctipo_asignacion').val() == "2") {
    $('#combo-tipo-unidad').hide();
    $('#check-presencial').show();
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
      $('#check-presencial').hide();
    } else if ($(this).val() == "2") {
      $('#combo-tipo-unidad').hide();
      $('#check-presencial').show();
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
                      '<td>' + 
                      '<input class="copcion" type="text" name="opciones[]">' +
                      '<input type="hidden" name="ids[]" value="">' +
                      '</td>' +
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

  // Formulario de pregunta
  $('#form_pregunta').on('submit', function (e) {
    $('.error').remove();

    var has_error = false;

    if (!$('#cnombre').val()) {
      addError('#cnombre', 'Campo requerido');
      has_error = true;
    }

    $('.copcion').each(function (idx, elem) {
      if (!$(elem).val()) { 
        has_error = true;
        addError(elem, "Campo requerido");
      }
    });

    var empty = true;
    $('.radio-respuesta').each(function (idx, elem) {
      if ($(elem).is(':checked')) {
        empty = false;
      }
    });

    if (has_error) {
      e.preventDefault();
      return;
    }

    if (empty) {
      e.preventDefault();
      showMessage('Debe seleccionar una opción de respuesta.', 'error');
    }


  });

  // Formulario de asistencias
  $('#form-asistencias').on('submit', function (e) {
    if (!$('#cfechaasistencia').val()) {
      showMessage('Debe ingresar la fecha de asistencia', 'error');
      e.preventDefault();
      return;
    }

    if (!moment($('#cfechaasistencia').val(), 'YYYY-MM-DD').isValid()) {
      showMessage('Debe ingresar una fecha válida', 'error');
      e.preventDefault();
      return;
    }

    var fechaAsistencia = moment($('#cfechaasistencia').val(), 'YYYY-MM-DD');
    var fechaInicio = moment($('#fecha_inicio').val(), 'YYYY-MM-DD');
    var fechaFin = moment($('#fecha_fin').val(), 'YYYY-MM-DD');

    if (!fechaAsistencia.isBetween(fechaInicio, fechaFin, null , '[]')) {
      showMessage('La fecha de asistencia debe estar entre la fecha de inicio y fin de vigencia', 'error');
      e.preventDefault();
      return;
    }


  });

  function addError(input, msg) {
    $(input).parent().append('<p class="error">* ' + msg + '</p>');
  }

  function reasignarValoresARadioRespuesta(){
    $('.radio-respuesta').each(function (idx, elem) {
      $(elem).prop('value', idx);
    });
  }

  function showMessage(msg, type) {
    $.ee_notice(msg, {open: true, type: 'error'});
    setTimeout(function () {
      $.ee_notice.destroy();
    }, 4000);
  }

  function renderItem( ul, item ) {
    return $( "<li>" )
      .append( "<div>" + item.name +  "</div>" )
      .appendTo( ul );
  };
});