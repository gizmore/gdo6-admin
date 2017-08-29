<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_CreatedBy;
use GDO\Table\GDT_Count;
use GDO\Table\GDT_Table;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;
use GDO\User\GDT_User;
use GDO\User\Permission;
use GDO\User\User;
use GDO\User\UserPermission;
use GDO\Util\Common;

class ViewPermission extends MethodQueryTable
{
	use MethodAdmin;
	
	private $permission;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		return $this->renderPermTabs('Admin')->add(parent::execute());
	}
	
	public function init()
	{
		$this->permission = Permission::table()->find(Common::getRequestString('permission'));
	}
	
	public function getHeaders()
	{
		$users = User::table();
		$perms = UserPermission::table();
		return array(
			GDT_Count::make('count'),
			GDT_User::make('perm_user_id'),
			GDT_CreatedAt::make('perm_created_at'),
			GDT_CreatedBy::make('perm_created_by'),
			GDT_Button::make('perm_revoke'),
		);
	}
	
	public function onDecorateTable(GDT_Table $table)
	{
		$table->fetchAs(User::table());
	}
	
	public function getQuery()
	{
		return UserPermission::table()->select('gwf_user.*, gwf_userpermission.*')->joinObject('perm_user_id')->where('perm_perm_id='.$this->permission->getID())->uncached();
	}
	
	
}
