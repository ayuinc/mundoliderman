<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, "id='form-eliminar-capacitacion'")?>
      <?= form_hidden('id', $capacitacion->id) ?>
      <h3>¿Seguro que quieres eliminar la capacitación?</h3>
      <p><?= $capacitacion->nombre ?></p>
      <span>Se elminaron todos lo registro relacionados</span>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:eliminar'), 'class' => 'submit'));?>
      </div>

    <?=form_close()?>
  </div>
</div>