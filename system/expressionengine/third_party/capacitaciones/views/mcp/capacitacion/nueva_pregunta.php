<div class="cmcp">
  <div class="cbody p-21">
    <?=form_open($action_url)?>
      <div class="cform-group">
        <label for="cnombre">Nombre</label>
        <input id="cnombre" type="text" name="nombre" value="<?php echo set_value('nombre'); ?>">
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
          <tr>
            <td><input type="text"></td>
            <td><a href="#" class="delete-opcion">Eliminar</a></td>
            <td><input class="radio-respuesta" name="respuesta" type="radio" value="0"></td>
          </tr>
          <tr>
            <td><input type="text"></td>
            <td><a href="#" class="delete-opcion">Eliminar</a></td>
            <td><input class="radio-respuesta"  name="respuesta" type="radio" value="1"></td>
          </tr>
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