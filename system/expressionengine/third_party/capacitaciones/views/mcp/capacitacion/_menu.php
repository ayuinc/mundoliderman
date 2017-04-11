<div id="umenu">
    <ul class="tab_menu" id="tab_menu_tabs">
      <?php if($capacitacion->presencial == '1') { ?>
      <li class="content_tab<?=(($section == 'asistencias')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=asistencias&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:asistencias')?></a>
      </li>
      <?php } else { ?>
      <li class="content_tab<?=(($section == 'contenidos')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=contenidos&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:contenidos')?></a>
      </li>
      <li class="content_tab<?=(($section == 'nuevo_contenido')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=nuevo_contenido&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:nuevo_contenido')?></a>
      </li>
      <li class="content_tab<?=(($section == 'inscripciones')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=inscripciones&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:inscripciones')?></a>
      </li>
      <li class="content_tab<?=(($section == 'ver_inscritos')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=ver_inscritos&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:ver_inscritos')?></a>
      </li>
      <li class="content_tab<?=(($section == 'test')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=test&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:test')?></a>
      </li>
      <?php } ?>
  </ul>
</div>

