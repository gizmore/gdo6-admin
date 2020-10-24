<?php
namespace GDO\Admin\Method;

use GDO\Form\MethodForm;
use GDO\Form\GDT_Form;
use GDO\User\GDO_User;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_AntiCSRF;
use GDO\UI\GDT_Link;
use GDO\Core\GDT_Hook;
use GDO\Core\GDT_Success;

final class UserCreate extends MethodForm
{
	public function createForm(GDT_Form $form)
	{
		$users = GDO_User::table();
		$form->addFields(array(
			$users->gdoColumnCopy('user_name')->notNull(),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = GDO_User::blank(array(
			'user_type' => 'member',
			'user_name' => $form->getFormVar('user_name'),
		));
		$user->insert();
		GDT_Hook::callWithIPC('UserActivated', $user);
		$linkEdit = GDT_Link::make('link_user_edit')->href(href('Admin', 'UserEdit', '&id='.$user->getID()));
		return GDT_Success::responseWith('admin_user_created')->addField($linkEdit);
	}
	
}
