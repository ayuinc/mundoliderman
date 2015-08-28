
  $.ajax({
    type: "GET",
    async: true,
    url: "./texto-inicio.html",
    success: function(a) {
      $('.datos-lidermania').html(a);
    }
  });
  /*cuando le das click a los menus*/
  $(".link-menu").click(function(e){
    e.preventDefault();
    $.ajax({
      type: "GET",
      async: true,
      url: "./texto-menu.html",
      success: function(a) {
        $('.datos-lidermania').html(a);
      }
    });
  });
  /*cuando le das click a leer más*/
  $(document).on('click', '.more-lidermania', function(e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      async: true,
      url: "./texto-lidermania.html",
      success: function(a) {
        $('.texto-ajax').html(a);
      }
    });
  });
  /*Regresas contenido de los links*/
  $(document).on('click', '.back', function(e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      async: true,
      url: "./texto-menu.html",
      success: function(a) {
        $('.datos-lidermania').html(a);
      }
    });
  });
  /*Click a inscripciones*/
  $(document).on('click', '.inscribite', function(e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      async: true,
      url: "./texto-inscripciones.html",
      success: function(a) {
        $('.datos-lidermania').html(a);
      }
    });
  });
