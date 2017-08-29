<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\User;
use GDO\Util\Common;
use GDO\Util\BCrypt;
/**
 * Edit a user.
 * 
 * @author gizmore
 * @see User
 */
class UserEdit extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	private $user;
	
	public function execute()
	{
		if (!($this->user = User::getById(Common::getRequestString('id'))))
		{
			return $this->error('err_user')->add($this->execMethod('Users'));
		}
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function createForm(GDT_Form $form)
	{
		$this->title(t('ft_admin_useredit', [sitename(), $this->user->displayNameLabel()]));
		foreach ($this->user->gdoColumnsCache() as $gdoType)
		{
			$form->addField($gdoType);
		}
		$form->getField('user_id')->writable(false);
		$form->addField(GDT_Submit::make());
		$form->addField(GDT_AntiCSRF::make());
		$form->withGDOValuesFrom($this->user);
		$form->getField('user_password')->initial('');
	}
	
	public function formValidated(GDT_Form $form)
	{
		$values = $form->getFormData();
		$password = $values['user_password'];
		unset($values['user_password']);
		
		$this->user->saveVars($values);
		$form->withGDOValuesFrom($this->user);
		if (!empty($password))
		{
			$this->user->saveVar('user_password', BCrypt::create($password)->__toString());
			return $this->message('msg_user_password_is_now', [$password])->add(parent::formValidated($form));
		}
		return parent::formValidated($form);
	}
}
