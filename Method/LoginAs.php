<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\Login\Module_Login;
use GDO\Login\Method\Form;
use GDO\User\GDO_User;

final class LoginAs extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	public function createForm(GDO_Form $form)
	{
		$form->addField(GDO_User::make('user_name')->notNull());
		$form->addField(GDO_Submit::make()->label('btn_login_as'));
		$form->addField(GDO_AntiCSRF::make());
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
	
	public function formValidated(GDO_Form $form)
	{
		$user = $form->getField('user_name')->getUser();
		return $this->loginForm()->loginSuccess($user);
	}
}
