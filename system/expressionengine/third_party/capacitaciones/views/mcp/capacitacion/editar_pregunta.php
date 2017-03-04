<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url, array('id' => 'form_pregunta'))?>
      <?= form_hidden('id', $pregunta->id) ?>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre', $pregunta->nombre); ?>">
        <?php echo form_error('nombre', '<p class="error"> *', '</p>'); ?>
      </div>
      <br>
      <label>Opciones</label>
      <table class="tbl-opciones">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Acción</th>
            <th>Es respuesta</th>
          </tr>
        </thead>
        <tbody class="tbody-opciones">
          <?php $i = 0; ?>
          <?php foreach ($pregunta->opciones as $opcion) { ?>
          <tr>
            <td>
              <input class="copcion" type="text" name="opciones[]" value="<?= $opcion->nombre ?>">
              <input type="hidden" name="ids[]" value="<?= $opcion->id ?>">
            </td>
            <td><a href="#" class="delete-opcion">Eliminar</a></td>
            <td><input class="radio-respuesta" name="respuesta" type="radio" value="<?= $i ?>" <?= $opcion->es_respuesta == '1' ? 'checked' : '' ?> ></td>
          </tr>
          <?php $i = $i + 1; ?>
          <?php } ?>
        </tbody>
      </table>
      <div>
        <button class="submit add-opcion">Agregar una opción</button>
      </div>
      <div class="text-right">
        <?=form_submit(array('name' => 'submit', 'value' => lang('c:guardar'), 'class' => 'submit'));?>
      </div>
    <?=form_close()?>
  </div>
</div>