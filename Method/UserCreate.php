<?php
namespace GDO\Admin\Method;

use GDO\Form\MethodForm;
use GDO\Form\GDT_Form;
use GDO\User\GDO_User;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_AntiCSRF;
use GDO\Core\GDT_Hook;
use GDO\Core\MethodAdmin;
use GDO\UI\GDT_Button;

final class UserCreate extends MethodForm
{
    use MethodAdmin;
    
	public function createForm(GDT_Form $form)
	{
		$users = GDO_User::table();
		$form->addFields(array(
			$users->gdoColumn('user_name')->notNull(),
			GDT_AntiCSRF::make(),
		));
		$form->actions()->addField(GDT_Submit::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = GDO_User::blank(array(
			'user_type' => 'member',
			'user_name' => $form->getFormVar('user_name'),
		));
		$user->insert();
		GDT_Hook::callWithIPC('UserActivated', $user);
		$linkEdit = GDT_Button::make('link_user_edit')->href(href('Admin', 'UserEdit', '&id='.$user->getID()));
		return $this->message('admin_user_created')->addField($linkEdit);
	}
	
}
