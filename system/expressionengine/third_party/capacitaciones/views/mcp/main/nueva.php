<div class="cmcp">
  <?php echo $this->view('mcp/main/_menu'); ?>
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-nueva-capacitacion'")?>
      <div class="cform-group cform-group-sm">
        <label for="ccodigo">Código</label>
        <input id="ccodigo" type="text" name="codigo" value="<?php echo set_value('codigo'); ?>">
        <?php echo form_error('codigo', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre'); ?>">
        <?php echo form_error('nombre', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group">
        <label for="cdescripcion">Descripción</label>
        <textarea id="cdescripcion" name="descripcion" rows="4"><?php echo set_value('description'); ?></textarea>
      </div>
      <div class="cform-group cdate-group">
        <label for="cfechainicio">Fecha Inicio</label>
        <input class="cdate-field" id="cfechainicio" type="text" name="fecha_inicio" value="<?php echo set_value('fecha_inicio'); ?>">
        <?php echo form_error('fecha_inicio', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group cdate-group">
        <label for="cfechafinvigencia">Fecha Fin Vigencia</label>
        <input class="cdate-field" id="cfechafinvigencia" type="text" name="fecha_fin_vigencia" value="<?php echo set_value('fecha_fin_vigencia'); ?>">
        <?php echo form_error('fecha_fin_vigencia', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group cdate-group">
        <label for="cdias_plazo">Días de plazo</label>
        <input id="cdias_plazo" type="text" name="dias_plazo" value="<?php echo set_value('dias_plazo'); ?>">
        <?php echo form_error('dias_plazo', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group ">
        <label for="ctipo_asignacion">Tipo de asignación</label> <br>
        <select name="tipo_asignacion" id="ctipo_asignacion">
          <option value="1" <?= (set_value('tipo_asignacion') == '1') ? 'selected' : ''  ?> >Por tipo de unidad</option>
          <option value="2" <?= (set_value('tipo_asignacion') == '2') ? 'selected' : ''  ?>>Por unidad</option>
        </select>
        <?php echo form_error('tipo_asignacion', '<p class="error"> *', '</p>'); ?>
      </div>
      <div id="combo-tipo-unidad" class="cform-group">
        <label for="ctipo_unidad">Tipo de unidad</label> <br>
        <select name="tipo_unidad" id="ctipo_unidad">
          <option value="0" <?= (set_value('tipo_unidad') == '0') ? 'selected' : ''  ?> >Unidad Minera</option>
          <option value="1" <?= (set_value('tipo_unidad') == '1') ? 'selected' : ''  ?> >Unidad Retail</option>
          <option value="2" <?= (set_value('tipo_unidad') == '2') ? 'selected' : ''  ?> > Unidad Petrolera </option>
        </select>
        <?php echo form_error('tipo_unidad', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:guardar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>