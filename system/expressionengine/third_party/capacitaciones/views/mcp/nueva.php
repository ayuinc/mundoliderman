<div class="cmcp">
  <?php echo $this->view('mcp/_menu'); ?>
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-nueva-capacitacion'")?>
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
        <label for="cfechafinplazo">Fecha Fin Plazo</label>
        <input class="cdate-field" id="cfechafinplazo" type="text" name="fecha_fin_plazo" value="<?php echo set_value('fecha_fin_plazo'); ?>">
        <?php echo form_error('fecha_fin_plazo', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:guardar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>