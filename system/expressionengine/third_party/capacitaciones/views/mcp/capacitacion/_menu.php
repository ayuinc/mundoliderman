<div id="umenu">
    <ul class="tab_menu" id="tab_menu_tabs">
      <li class="content_tab<?=(($section == 'contenidos')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=contenidos&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:contenidos')?></a>
      </li>
      <li class="content_tab<?=(($section == 'nuevo_contenido')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=nuevo_contenido&capacitacion_id=<?=$capacitacion_id?>"><?=lang('c:nuevo_contenido')?></a>
      </li>
  </ul>
</div>

