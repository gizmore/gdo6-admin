<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Login\Module_Login;
use GDO\Login\Method\Form;
use GDO\User\GDT_User;

final class LoginAs extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_User::make('user_name')->notNull());
		$form->addField(GDT_Submit::make()->label('btn_login_as'));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	/**
	 * @return Form
	 */
	private function loginForm()
	{
		return Module_Login::instance()->getMethod('Form');
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = $form->getField('user_name')->getUser();
		return $this->loginForm()->loginSuccess($user);
	}
}
