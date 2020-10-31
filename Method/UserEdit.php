<?php
namespace GDO\Admin\Method;

use GDO\Core\GDT_Hook;
use GDO\Core\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\GDO_User;
use GDO\Util\Common;
use GDO\Util\BCrypt;
use GDO\Core\Website;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\Form\GDT_DeleteButton;
use GDO\UI\GDT_Page;

/**
 * Edit a user.
 * 
 * @author gizmore
 * @see User
 */
class UserEdit extends MethodForm
{
	use MethodAdmin;
	
	/**
	 * @var GDO_User
	 */
	private $user;
	
	public function execute()
	{
		if (!($this->user = GDO_User::getById(Common::getRequestString('id'))))
		{
			return $this->error('err_user')->add(Users::make()->execMethod());
		}
		
		$barPermissions = GDT_Bar::make()->horizontal();
		$barPermissions->addField(GDT_Link::make('link_edit_permissions')->href(href('Admin', 'PermissionGrant', '&form[perm_user_id]='.$this->user->getID())));
		
		GDT_Page::$INSTANCE->topTabs->addField($barPermissions);
		
		return parent::execute();
	}
	
	public function createForm(GDT_Form $form)
	{
		# Set title
		$this->title(t('ft_admin_useredit', [$this->user->displayNameLabel()]));
		
		# Add all columns
		foreach ($this->user->gdoColumnsCache() as $gdoType)
		{
			$form->addField($gdoType);
		}
		
		# Add buttons
		$form->addField(GDT_Submit::make());
		$form->addField(GDT_DeleteButton::make());
		$form->addField(GDT_AntiCSRF::make());
		
		# Fill form values with user data
		$form->withGDOValuesFrom($this->user);

		# Patch columns a bit
		$form->getField('user_name')->pattern(null);
		$form->getField('user_password')->notNull(false);
		$form->getField('user_id')->writable(false);
		$form->getField('user_password')->initial(''); # no pass initially
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
	
	public function onSubmit_btn_delete(GDT_Form $form)
	{
		$this->user->delete();
		GDT_Hook::callWithIPC("UserDeleted", $this->user);
		return $this->message('msg_user_deleted', [$this->user->displayName()])->
			add(Website::redirect(href('Admin', 'Users')));
		
	}
}
