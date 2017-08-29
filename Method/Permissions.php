<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Table\GDT_Count;
use GDO\Table\MethodQueryTable;
use GDO\Type\GDT_Int;
use GDO\Type\GDT_Name;
use GDO\UI\GDT_Button;
use GDO\User\Permission;
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Permissions extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function getGDO() { return Permission::table(); }
	
	public function getHeaders()
	{
		return array(
			GDT_Count::make(),
			GDT_Button::make('edit'),
			GDT_Name::make('perm_name'),
			GDT_Int::make('user_count')->virtual(),
		);
	}
	
	public function getQuery()
	{
		$query = $this->getGDO()->select('perm_id, perm_name');
		$query->select('COUNT(perm_user_id) user_count')->join('LEFT JOIN gwf_userpermission ON perm_id = perm_perm_id')->uncached();
		return $query->group('perm_id,perm_name');
	}
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
}
