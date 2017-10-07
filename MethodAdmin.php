<?php
namespace GDO\Admin;
trait MethodAdmin
{
	public function renderNavBar($module=null)
	{
		return Module_Admin::instance()->templatePHP('navbar.php', ['moduleName' => $module]);
	}

	public function renderPermTabs($module=null)
	{
		return $this->renderNavBar($module)->add(Module_Admin::instance()->templatePHP('perm_tabs.php'));
	}
}
