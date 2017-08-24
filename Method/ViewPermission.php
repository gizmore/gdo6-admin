<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\DB\GDO_CreatedAt;
use GDO\DB\GDO_CreatedBy;
use GDO\Table\GDO_Count;
use GDO\Table\GDO_Table;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDO_Button;
use GDO\User\GDO_User;
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
			GDO_Count::make('count'),
			GDO_User::make('perm_user_id'),
			GDO_CreatedAt::make('perm_created_at'),
			GDO_CreatedBy::make('perm_created_by'),
			GDO_Button::make('perm_revoke'),
		);
	}
	
	public function onDecorateTable(GDO_Table $table)
	{
		$table->fetchAs(User::table());
	}
	
	public function getQuery()
	{
		return UserPermission::table()->select('gwf_user.*, gwf_userpermission.*')->joinObject('perm_user_id')->where('perm_perm_id='.$this->permission->getID())->uncached();
	}
	
	
}
