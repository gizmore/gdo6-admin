<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Table\GDT_Count;
use GDO\Table\MethodQueryTable;
use GDO\DB\GDT_UInt;
use GDO\DB\GDT_Name;
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
	
	public function gdoHeaders()
	{
		return [
			GDT_Count::make(),
			GDT_Button::make('btn_edit'),
			GDT_Name::make('perm_name'),
			GDT_UInt::make('user_count')->virtual(),
		];
	}
	
	public function beforeExecute()
	{
	    $this->renderNavBar();
	    $this->renderPermTabs();
	}
	
	public function getQuery()
	{
		$query = $this->gdoTable()->select('perm_id, perm_name');
		$query->select('COUNT(perm_user_id) user_count');
		$query->join('LEFT JOIN gdo_userpermission ON perm_id = perm_perm_id')->uncached();
		return $query->group('perm_id, perm_name');
	}
	
}
