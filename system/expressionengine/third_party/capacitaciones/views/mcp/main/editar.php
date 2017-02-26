<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-editar-capacitacion'")?>
      <?= form_hidden('id', $capacitacion->id) ?>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre', $capacitacion->nombre); ?>">
        <?php echo form_error('nombre', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group">
        <label for="cdescripcion">Descripción</label>
        <textarea id="cdescripcion" name="descripcion" rows="4"><?php echo set_value('description', $capacitacion->descripcion); ?></textarea>
      </div>
      <div class="cform-group cdate-group">
        <label for="cfechainicio">Fecha Inicio</label>
        <input class="cdate-field" id="cfechainicio" type="text" name="fecha_inicio" value="<?php echo set_value('fecha_inicio', $capacitacion->getFInicioFormated()); ?>">
        <?php echo form_error('fecha_inicio', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group cdate-group">
        <label for="cfechafinvigencia">Fecha Fin Vigencia</label>
        <input class="cdate-field" id="cfechafinvigencia" type="text" name="fecha_fin_vigencia" value="<?php echo set_value('fecha_fin_vigencia', $capacitacion->getFFinVigenciaFormated()); ?>">
        <?php echo form_error('fecha_fin_vigencia', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group cdate-group">
        <label for="cfechafinplazo">Fecha Fin Plazo</label>
        <input class="cdate-field" id="cfechafinplazo" type="text" name="fecha_fin_plazo" value="<?php echo set_value('fecha_fin_plazo', $capacitacion->getFFinPlazoFormated()); ?>">
        <?php echo form_error('fecha_fin_plazo', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:actualizar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>