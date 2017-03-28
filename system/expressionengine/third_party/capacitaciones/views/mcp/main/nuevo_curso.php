<div class="cmcp">
  <?php echo $this->view('mcp/main/_menu'); ?>
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-nuevo-curso'")?>
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
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:guardar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>