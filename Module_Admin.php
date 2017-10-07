<?php
namespace GDO\Admin;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;

class Module_Admin extends GDO_Module
{
	##############
	### Module ###
	##############
	public function isCoreModule() { return true; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/admin'); }

	###############
	### Navbars ###
	###############
	public function hookRightBar(GDT_Bar $navbar)
	{
		if (GDO_User::current()->isAdmin())
		{
			$navbar->addField(GDT_Link::make('btn_admin')->label('btn_admin')->href(href('Admin', 'Modules')));
		}
	}
	
}
