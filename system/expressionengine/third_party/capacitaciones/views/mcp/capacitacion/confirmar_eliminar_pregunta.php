<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-eliminar-contenido'")?>
      <?= form_hidden('id', $pregunta->id) ?>
      <h3>¿Seguro que quieres eliminar la pregunta?</h3>
      <p><?= $pregunta->nombre ?></p>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:eliminar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>