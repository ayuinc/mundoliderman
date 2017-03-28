<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open_multipart($action_url, array('id' => 'form-contenido'))?>
      <?= form_hidden('id', $contenido->id) ?>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre', $contenido->nombre); ?>">
        <?php echo form_error('nombre', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="cform-group">
        <label for="cdescripcion">Descripción</label>
        <textarea id="cdescripcion" name="descripcion" rows="4"><?php echo set_value('description', $contenido->descripcion); ?></textarea>
      </div>
      <div class="cform-group">
        <label for="carchivo">Archivo </label> <br>
        <?php if ($contenido->file_path != "") { ?>
          <span><a href="<?= $contenido->file_path ?>">Ver Archivo</a> <?= lang('c:no_update_file_message') ?></span> <br>
        <?php } ?>
        <input id="carchivo" type="file" name="archivo">
      </div>
      <div class="cform-group">
        <label for="cvideo">Youtube Video ID</label>
        <input id="cvideo" type="text" name="video_id" value="<?php echo set_value('video_id', $contenido->video_id); ?>">
      </div>
      <div class="cform-group cform-group-sm">
        <label for="corden">Nro Orden</label>
        <input id="corden" type="text" name="orden" value="<?php echo set_value('orden', $contenido->orden); ?>">
        <?php echo form_error('orden', '<p class="error"> *', '</p>'); ?>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:actualizar'), 'class' => 'submit'));?>
      </div>
    <?=form_close()?>
  </div>
</div>