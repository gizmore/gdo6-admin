<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\DB\Database;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\GDT_Permission;
use GDO\User\GDT_User;
use GDO\User\Permission;
use GDO\User\User;
use GDO\User\UserPermission;
use GDO\Util\Common;

class PermissionRevoke extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var User
	 */
	private $user;
	
	/**
	 * @var Permission
	 */
	private $permission;
	
	public function init()
	{
		$this->user = User::table()->find(Common::getRequestString('user'));
		$this->permission = Permission::table()->find(Common::getRequestString('perm'));
	}
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GDT_Form $form)
	{
		$form->addFields(array(
			GDT_User::make('perm_user_id')->notNull()->value($this->user ? $this->user->getID() : '0'),
			GDT_Permission::make('perm_perm_id')->notNull()->value($this->permission ? $this->permission->getID() : '0'),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$condition = sprintf('perm_user_id=%s AND perm_perm_id=%s', $form->getFormVar('perm_user_id'), $form->getFormVar('perm_perm_id'));
		UserPermission::table()->deleteWhere($condition)->exec();
		$affected = Database::instance()->affectedRows();
		$response = $affected > 0 ? $this->message('msg_perm_revoked') : $this->error('err_nothing_happened');
		return $response->add($this->renderPage());
	}
}
