<?php
namespace GDO\Admin;

use GDO\Core\Module;
use GDO\Template\GDO_Bar;
use GDO\UI\GDO_Link;
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
	public function hookRightBar(GDO_Bar $navbar)
	{
		if (User::current()->isAdmin())
		{
			$navbar->addField(GDO_Link::make('btn_admin')->label('btn_admin')->href($this->getMethodHREF('Modules')));
		}
	}
	
}
