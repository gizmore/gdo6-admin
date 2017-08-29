<?php
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;

$bar = GDT_Bar::make();
$bar->addFields(array(
	GDT_Link::make('link_add_perm')->href(href('Admin', 'PermissionAdd'))->icon('add'),
	GDT_Link::make('link_grant_perm')->href(href('Admin', 'PermissionGrant'))->icon('add'),
));
echo $bar->render();
