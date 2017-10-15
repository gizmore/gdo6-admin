<?php
use GDO\UI\GDT_Link;
echo \GDO\UI\GDT_Bar::makeWith(
	GDT_Link::make('link_add_perm')->href(href('Admin', 'PermissionAdd'))->icon('add'),
	GDT_Link::make('link_grant_perm')->href(href('Admin', 'PermissionGrant'))->icon('add')
)->horizontal()->render();
