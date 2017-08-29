<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\Permission;

class PermissionAdd extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		return $this->renderPermTabs()->add(parent::execute());
	}
	
	public function createForm(GDT_Form $form)
	{
		$gdo = Permission::table();
		$form->addFields(array(
			$gdo->gdoColumn('perm_name'),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
	}

	public function formValidated(GDT_Form $form)
	{
		$perm = Permission::blank($form->getFormData())->insert();
		return $this->message('msg_perm_added', [$perm->displayName()]);
	}
}
