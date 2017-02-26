<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-eliminar-contenido'")?>
      <?= form_hidden('id', $contenido->id) ?>
      <h3>¿Seguro que quieres eliminar el contenido?</h3>
      <p>Nombre: <?= $contenido->nombre ?></p>
      <?php if($contenido->file_path != "") { ?>
        <p><a href="<?= $static_url . $contenido->file_path ?>">Archivo</a></p>
      <?php } ?>
      <?php if($contenido->video_id != "0") { ?>
      <p><a href="<?= 'https://www.youtube.com/watch?v=' . $contenido->video_id ?>">Video</a></p>
      <?php } ?>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:eliminar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>