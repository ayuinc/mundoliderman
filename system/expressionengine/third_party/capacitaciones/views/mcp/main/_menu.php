<div id="umenu">
    <ul class="tab_menu" id="tab_menu_tabs">
      <li class="content_tab<?=(($section == 'cursos')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=cursos""><?=lang('c:cursos')?></a>
      </li>
      <li class="content_tab<?=(($section == 'nuevo_curso')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=nuevo_curso"><?=lang('c:nuevo_curso')?></a>
      </li>
      <li class="content_tab<?=(($section == 'listado')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=listado""><?=lang('c:modulos')?></a>
      </li>
      <li class="content_tab<?=(($section == 'nueva')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=nueva"><?=lang('c:nueva')?></a>
      </li>
  </ul>
</div>

