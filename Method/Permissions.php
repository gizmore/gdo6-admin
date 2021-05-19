<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Table\GDT_Count;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;
use GDO\User\GDO_Permission;

/**
 * Overview of permissions.
 * 
 * @version 6.10.1
 * @since 6.0.0
 * @author gizmore
 */
class Permissions extends MethodQueryTable
{
	use MethodAdmin;
	
	public function gdoTable() { return GDO_Permission::table(); }
	
	public function getPermission() { return 'staff'; }
	
	public function getTableTitle()
	{
	    return $this->getTitle();
	}
	
	public function getTitle()
	{
	    return t('btn_permissions');
	}

	public function gdoHeaders()
	{
	    $perms = GDO_Permission::table();
		return [
			GDT_Count::make(),
			GDT_Button::make('btn_edit'),
		    $perms->gdoColumn('perm_name'),
		    $perms->gdoColumn('perm_level'),
		    $perms->gdoColumn('perm_usercount'),
		];
	}
	
	public function beforeExecute()
	{
	    $this->renderNavBar();
	    $this->renderPermTabs();
	}
	
}
