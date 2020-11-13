<?php
namespace GDO\Admin;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\UI\GDT_Page;

class Module_Admin extends GDO_Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() { return $this->loadLanguage('lang/admin'); }

	###############
	### Navbars ###
	###############
	public function onInitSidebar()
	{
		if (GDO_User::current()->isAdmin())
		{
		    GDT_Page::$INSTANCE->rightNav->addField(
		        GDT_Link::make('btn_admin')->label('btn_admin')->href(href('Admin', 'Modules')));
		}
	}
	
}
