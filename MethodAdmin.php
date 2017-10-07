<?php
namespace GDO\Admin;
use GDO\Core\GDT_Template;

trait MethodAdmin
{
	public function renderNavBar($module=null)
	{
		return GDT_Template::responsePHP('Admin', 'navbar.php', ['moduleName' => $module]);
	}

	public function renderPermTabs($module=null)
	{
		return $this->renderNavBar($module)->add(GDT_Template::responsePHP('Admin', 'perm_tabs.php'));
	}
}
