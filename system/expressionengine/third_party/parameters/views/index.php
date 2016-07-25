<?php
  function formatLabel($text) {
    $txtContinue = "...";
    $maxLen = 35;
    $txtLen = strlen($text);

    if ( $txtLen > $maxLen) {
      $text =  substr($text, 0, $maxLen - strlen($txtContinue)) . $txtContinue;
    }

    return $text;
  }
?>
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
      <?=form_open($action_url, '', $form_hidden)?>
      <?php $parameter = $parameters['UTILIDADES']; ?>
      <tr>
        <td>
          <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
          <span><?php echo $parameter['description'] ?></span>
        </td>
        <td>
          <select name="value" id="utilidades-val">
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
    <br>
    <div>
      <?=form_open($action_url_access, '', $form_hidden)?>
      <?php $parameter = $parameters['ACCESSSGI']; ?>
      <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
      <span><?php echo $parameter['description'] ?></span><br>
      <table>
      <tr>
      <?php $count = 1; ?>
      <?php foreach ($member_groups as $member_group) { ?>
          <td>
            <label>
            <input id="member-group-sgi<?= $member_group['group_id']?>" type="checkbox" name="value[]" value="<?= $member_group['group_id']?>"/>
            <?= formatLabel($member_group['group_title']) ?>
            </label>
          </td>
          <?php if($count % 4 == 0) 
            echo "</tr>\n<tr>";
                $count += 1; 
          ?>
      <?php } ?>
      </tr>
      </table>
      <input type="submit" value=" Aplicar ">
      <?=form_close()?>
    </div>
    <br>
    <br>
    <div>
      <?=form_open($action_url_access, '', $form_hidden)?>
      <?php $parameter = $parameters['ACCESSIND']; ?>
      <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
      <span><?php echo $parameter['description'] ?></span><br>
      <table>
      <tr>
      <?php $count = 1; ?>
      <?php foreach ($member_groups as $member_group) { ?>
          <td>
            <label>
            <input id="member-group-ind<?= $member_group['group_id']?>" type="checkbox" name="value[]" value="<?= $member_group['group_id']?>"/>
            <?= formatLabel($member_group['group_title']) ?>
            </label>
          </td>
          <?php if($count % 4 == 0) 
            echo "</tr>\n<tr>";
                $count += 1; 
          ?>
      <?php } ?>
      </tr>
      </table>
      <input type="submit" value=" Aplicar ">
      <?=form_close()?>
    </div>
  </div>
  <br>
  <br>
  <div>
    <?=form_open($action_url_access, '', $form_hidden)?>
    <?php $parameter = $parameters['ACCESSCHAT']; ?>
    <input name="id" type="hidden" value="<?php echo $parameter['id'] ?>">
    <span><?php echo $parameter['description'] ?></span><br>
    <table>
    <tr>
    <?php $count = 1; ?>
    <?php foreach ($member_groups as $member_group) { ?>
        <td>
          <label>
          <input id="member-group-chat<?= $member_group['group_id']?>" type="checkbox" name="value[]" value="<?= $member_group['group_id']?>"/>
          <?= formatLabel($member_group['group_title']) ?>
          </label>
        </td>
        <?php if($count % 4 == 0) 
          echo "</tr>\n<tr>";
              $count += 1; 
        ?>
    <?php } ?>
    </tr>
    </table>
    <input type="submit" value=" Aplicar ">
    <?=form_close()?>
    </div>
  </div>    
<?php else: ?>
  <?=lang('no_matching_files')?>
<?php endif; ?>
<script>$("#boletas-val").val("<?php echo $parameters['BOLETAS']['value']?>")</script>
<script>$("#cts-val").val("<?php echo $parameters['CTS']['value']?>")</script>
<script>$("#utilidades-val").val("<?php echo $parameters['UTILIDADES']['value']?>")</script>
<script>
  var strValue = "<?php echo $parameters['ACCESSSGI']['value']?>";
  var arrValue = strValue.split(",");
  arrValue.forEach(function (elem, idx) {
    $('#member-group-sgi' + elem).attr("checked", true);
  });
</script>
<script>
  var strValue = "<?php echo $parameters['ACCESSIND']['value']?>";
  var arrValue = strValue.split(",");
  arrValue.forEach(function (elem, idx) {
    $('#member-group-ind' + elem).attr("checked", true);
  });
</script>
<script>
  var strValue = "<?php echo $parameters['ACCESSCHAT']['value']?>";
  var arrValue = strValue.split(",");
  arrValue.forEach(function (elem, idx) {
    $('#member-group-chat' + elem).attr("checked", true);
  });
</script>