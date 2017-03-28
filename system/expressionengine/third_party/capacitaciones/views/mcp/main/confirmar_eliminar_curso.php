<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-eliminar-curso'")?>
      <?= form_hidden('id', $curso->id) ?>
      <h3>¿Seguro que quieres eliminar el curso?</h3>
      <p><?= $curso->nombre ?></p>
      <span>Se elminarán todos los registros relacionados</span>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:eliminar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>