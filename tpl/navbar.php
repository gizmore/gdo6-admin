<?php
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\Core\GDT_Hook;
$bar = GDT_Bar::make('admintabs')->horizontal();
$bar->addFields(array(
	GDT_Link::make('btn_clearcache')->href(href('Admin', 'ClearCache')),
	GDT_Link::make('btn_modules')->href(href('Admin', 'Modules')),
	GDT_Link::make('btn_users')->href(href('Admin', 'Users')),
	GDT_Link::make('btn_permissions')->href(href('Admin', 'Permissions')),
));
GDT_Hook::callHook('AdminBar', $bar);
echo $bar->renderCell();
