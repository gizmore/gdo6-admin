<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\User\Permission;
use GDO\User\User;
use GDO\User\UserPermission;

class PermissionGrant extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GDO_Form $form)
	{
		$gdo = UserPermission::table();
		$form->addFields(array(
			$gdo->gdoColumn('perm_user_id'),
			$gdo->gdoColumn('perm_perm_id'),
			GDO_Submit::make(),
			GDO_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GDO_Form $form)
	{
		$userpermission = UserPermission::blank($form->getFormData())->replace();
		$permission = $userpermission->getPermission();
		$permission = $form->getFormValue('perm_perm_id');
		$permission instanceof Permission;
		$user = $form->getFormValue('perm_user_id');
		$user instanceof User;
		$user->changedPermissions();
		return $this->message('msg_perm_granted', [$permission->displayName(), $user->displayNameLabel()]);
	}
	
}
