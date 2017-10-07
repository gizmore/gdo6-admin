<?php
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
$bar = GDT_Bar::make('admintabs');
$bar->addFields(array(
	GDT_Link::make('btn_phpinfo')->href(href('GWF', 'PHPInfo')),
	GDT_Link::make('btn_clearcache')->href(href('Admin', 'ClearCache')),
	GDT_Link::make('btn_modules')->href(href('Admin', 'Modules')),
	GDT_Link::make('btn_users')->href(href('Admin', 'Users')),
	GDT_Link::make('btn_permissions')->href(href('Admin', 'Permissions')),
	GDT_Link::make('btn_cronjob')->href(href('Admin', 'Cronjob')),
	GDT_Link::make('btn_login_as')->href(href('Admin', 'LoginAs')),
));
echo $bar->renderCell();
