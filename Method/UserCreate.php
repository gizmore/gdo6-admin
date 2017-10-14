<?php
namespace GDO\Admin\Method;

use GDO\Form\MethodForm;
use GDO\Form\GDT_Form;
use GDO\User\GDO_User;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_AntiCSRF;

final class UserCreate extends MethodForm
{
	public function createForm(GDT_Form $form)
	{
		$users = GDO_User::table();
		$form->addFields(array(
			$users->gdoColumn('user_name'),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
		
	}
}