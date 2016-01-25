<?php if (count($parameters) > 0): ?>
    <div>
    <?=form_open($action_url, '', $form_hidden)?>
        <?php $parameter = $parameters['BOLETAS']; ?>
        <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
        <span><?php echo $parameter['description'] ?></span>
        <select name="value" id="boletas-val">
          <option value="y">Activar</option>
          <option value="n">Desactivar</option>
        </select>
        <input type="submit" value=" Aplicar ">
      <?=form_close()?>
    </div>    
<?php else: ?>
  <?=lang('no_matching_files')?>
<?php endif; ?>
<script>$("#boletas-val").val("<?php echo $parameters['BOLETAS']['value']?>")</script>