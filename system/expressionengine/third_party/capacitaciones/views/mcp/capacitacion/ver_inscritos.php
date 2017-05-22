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
            &nbsp;
            <input type="text" id="zona" name="zona"  style="width: 200px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="cliente">Cliente</label>
            &nbsp;
            <input type="text" id="cliente" name="cliente"  style="width: 200px;">
          </p>
          <p>
            <label for="cliente">Vigencia</label>
            &nbsp;
            <select name="vigencia">
              <option value="0">Todos</option>
              <option value="1">En curso</option>
              <option value="2">Finalizado</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="cliente">Test</label>
            &nbsp;
            <select name="test">
              <option value="0">Todos</option>
              <option value="1">Aprobado</option>
              <option value="2">Desaprobado</option>
              <option value="3">Pendiente</option>
            </select>
          </p>
        </fieldset>
      </div>
    </form>
    <?php echo $table_inscritos['pagination_html']; ?>
    <?php echo $table_inscritos['table_html']; ?>
    <?php echo $table_inscritos['pagination_html']; ?>

  </div>
</div>
<input id="unidad_url" type="hidden" value="<?= $base_url . '&method=ajax_find_unidad' ?>">
<input id="zona_url" type="hidden" value="<?= $base_url . '&method=ajax_find_zona' ?>">
<input id="cliente_url" type="hidden" value="<?= $base_url . '&method=ajax_find_cliente' ?>">