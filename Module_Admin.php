<?php
namespace GDO\Admin;

use GDO\Core\Module;
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\User;

class Module_Admin extends Module
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
		if (User::current()->isAdmin())
		{
			$navbar->addField(GDT_Link::make('btn_admin')->label('btn_admin')->href($this->getMethodHREF('Modules')));
		}
	}
	
}
