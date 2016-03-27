<?php if (count($parameters) > 0): ?>
    <div>
    <table>
      <?=form_open($action_url, '', $form_hidden)?>
      <?php $parameter = $parameters['BOLETAS']; ?>
      <tr>
        <td>
          <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
          <span><?php echo $parameter['description'] ?></span>
        </td>
        <td>
          <select name="value" id="boletas-val">
            <option value="y">Activar</option>
            <option value="n">Desactivar</option>
          </select>
        </td>
        <td>
          <input type="submit" value=" Aplicar ">
        </td>
      </tr>
      <?=form_close()?>
      <?=form_open($action_url, '', $form_hidden)?>
      <?php $parameter = $parameters['CTS']; ?>
      <tr>
        <td>
          <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
          <span><?php echo $parameter['description'] ?></span>
        </td>
        <td>
          <select name="value" id="cts-val">
            <option value="y">Activar</option>
            <option value="n">Desactivar</option>
          </select>
        </td>
        <td>
          <input type="submit" value=" Aplicar ">
        </td>
      </tr>
      <?=form_close()?>
    </table>
    </div>    
<?php else: ?>
  <?=lang('no_matching_files')?>
<?php endif; ?>
<script>$("#boletas-val").val("<?php echo $parameters['BOLETAS']['value']?>")</script>
<script>$("#cts-val").val("<?php echo $parameters['CTS']['value']?>")</script>