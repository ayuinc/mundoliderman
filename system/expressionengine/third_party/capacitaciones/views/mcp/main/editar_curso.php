<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-editar-curso'")?>
      <?= form_hidden('id', $curso->id) ?>
      <div class="cform-group cform-group-sm">
        <label for="ccodigo">Código</label>
        <input id="ccodigo" type="text" name="codigo" value="<?php echo set_value('codigo', $curso->codigo); ?>">
        <?php echo form_error('codigo', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre', $curso->nombre); ?>">
        <?php echo form_error('nombre', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:actualizar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>