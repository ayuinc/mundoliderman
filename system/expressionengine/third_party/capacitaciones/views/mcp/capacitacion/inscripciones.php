<div class="cmcp">
  <?php echo $this->view('mcp/capacitacion/_menu'); ?>
  <div class="cbody p-21">
    <form id="form-filtrar" action="">
      <div id="filterMenu">
        <fieldset>
          <legend>Filtrar</legend>
          <p>
            <label for="codigo" class="field">Cod &nbsp;</label>
            <input type="text" name="codigo" class="field" id="codigo" placeholder="Código" style="width: 15%;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="dni" class="field">DNI &nbsp;</label>
            <input type="text" name="dni" class="field" id="dni" placeholder="DNI" style="width: 15%;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="nombre" class="field">Nombre &nbsp;</label>
            <input type="text" name="nombre" class="field" id="nombre" placeholder="Nombre" style="width: 15%;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="apellidos" class="field">Apellidos &nbsp;</label>
            <input type="text" name="apellidos" class="field" id="apellidos" placeholder="Apellidos" style="width: 15%;">
          </p>
          <p>
            <label for="unidad">Unidad</label>
            &nbsp;
            <input type="text" id="unidad" name="unidad" style="width: 200px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="zona">Zona</label>
            <input type="text" id="zona" name="zona"  style="width: 200px;">
          </p>
        </fieldset>
      </div>
    </form>
    <form action="">
      <?php echo $table_inscripciones['pagination_html']; ?>
      <?php echo $table_inscripciones['table_html']; ?>
      <?php echo $table_inscripciones['pagination_html']; ?>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:guardar'), 'class' => 'submit'));?>
      </div>
    </form>
  </div>
</div>
<input id="unidad_url" type="hidden" value="<?= $base_url . '&method=ajax_find_unidad' ?>">
<input id="zona_url" type="hidden" value="<?= $base_url . '&method=ajax_find_zona' ?>">