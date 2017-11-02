<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\GDO_Permission;
use GDO\User\GDO_User;
use GDO\User\GDO_UserPermission;

class PermissionGrant extends MethodForm
{
	use MethodAdmin;
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GDT_Form $form)
	{
		$gdo = GDO_UserPermission::table();
		$form->addFields(array(
			$gdo->gdoColumn('perm_user_id'),
		    $gdo->gdoColumn('perm_perm_id')->emptyInitial(t('choose_permission')),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$userpermission = GDO_UserPermission::blank($form->getFormData())->replace();
		$permission = $userpermission->getPermission();
		$permission = $form->getFormValue('perm_perm_id');
		$permission instanceof GDO_Permission;
		$user = $form->getFormValue('perm_user_id');
		$user instanceof GDO_User;
		$user->changedPermissions();
		return $this->message('msg_perm_granted', [$permission->displayName(), $user->displayNameLabel()]);
	}
	
}
