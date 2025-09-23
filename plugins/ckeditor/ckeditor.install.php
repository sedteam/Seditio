<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ckeditor/ckeditor.install.php
Version=180
Updated=2025-jan-23
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
  die('Wrong URL.');
}

foreach ($sed_groups as $k => $v) {
  if ($v['id'] > 3) {
    //$value = ($v['id']==5) ? 'Extended' : 'Default';    
    sed_config_add('plug', 'ckeditor', 99, 'ckeditor_grp' . $v['id'], 'select', 'Default', 'Default', "Global toolbar for the group '" . $v['title'], 'Default,Micro,Basic,Extended,Full');
  }
}
