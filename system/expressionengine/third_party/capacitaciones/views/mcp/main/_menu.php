<div id="umenu">
    <ul class="tab_menu" id="tab_menu_tabs">
      <li class="content_tab<?=(($section == 'listado')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=listado""><?=lang('c:listado')?></a>
      </li>
      <li class="content_tab<?=(($section == 'nueva')) ? ' current': ''?>">
          <a href="<?=$base_url?>&method=nueva"><?=lang('c:nueva')?></a>
      </li>
  </ul>
</div>

