<?php
namespace GDO\Admin\Method;

use GDO\Core\GDT_Hook;
use GDO\Core\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\User\GDO_User;
use GDO\Util\BCrypt;
use GDO\Core\Website;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\Form\GDT_DeleteButton;
use GDO\UI\GDT_Page;
use GDO\User\GDT_User;

/**
 * Edit a user.
 * 
 * @author gizmore
 * @version 6.10.1
 * @since 3.0.4
 * @see GDO_User
 */
class UserEdit extends MethodForm
{
	use MethodAdmin; # admin protection
	
	/**
	 * @var GDO_User
	 */
	private $user;
	
	public function gdoParameters()
	{
	    return [
	        GDT_User::make('user')->notNull(),
	    ];
	}
	
	public function init()
	{
	    $this->user = $this->gdoParameterValue('user');
	}
	
	public function beforeExecute()
	{
	    $this->renderNavBar();
	    $barPermissions = GDT_Bar::make()->horizontal();
	    $barPermissions->addField(GDT_Link::make('link_edit_permissions')->href(href('Admin', 'PermissionGrant', '&form[perm_user_id]='.$this->user->getID())));
	    GDT_Page::$INSTANCE->topTabs->addField($barPermissions);
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
		$form->actions()->addField(GDT_Submit::make());
		$form->actions()->addField(GDT_DeleteButton::make());
		$form->addField(GDT_AntiCSRF::make());
		
		# Fill form values with user data
		$form->withGDOValuesFrom($this->user);

		# Patch columns a bit
		$form->getField('user_name')->pattern(null);
		$form->getField('user_password')->notNull(false)->initial('');
		$form->getField('user_id')->writable(false);
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
		return parent::formValidated($form)->add($this->renderPage());
	}
	
	public function onSubmit_btn_delete(GDT_Form $form)
	{
		$this->user->delete();
		GDT_Hook::callWithIPC("UserDeleted", $this->user);
		return Website::redirectMessage('msg_user_deleted', [$this->user->displayNameLabel()], href('Admin', 'Users'));
	}
	
}
