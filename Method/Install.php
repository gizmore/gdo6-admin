<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Core\GDO_Module;
use GDO\DB\Cache;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Util\Common;
use GDO\Install\Installer;
use GDO\Template\Message;
use GDO\Core\ModuleLoader;

class Install extends MethodForm
{
	use MethodAdmin;
	
	public function getPermission() { return 'admin'; }
	
	/**
	 * @var GDO_Module
	 */
	private $configModule;
	
	public function execute()
	{
		ModuleLoader::instance()->loadModules(false, true);
		
		if ($this->configModule = ModuleLoader::instance()->getModule(Common::getRequestString('module')))
		{
			$buttons = ['install', 'uninstall', 'enable', 'disable'];
			foreach ($buttons as $button)
			{
				if (isset($_POST[$button]))
				{
					return $this->executeButton($button)->add($this->renderPage());
				}
			}
			return $this->renderPage();
		}
	}
	
	public function createForm(GDT_Form $form)
	{
		$this->title(t('ft_admin_install', [sitename(), $this->configModule->getName()]));
		$form->addField(GDT_Submit::make('install')->label('btn_install'));
// 		$form->addField(GDT_Submit::make('wipe')->label('btn_module_wipe'));
		$form->addField(GDT_Submit::make('uninstall')->label('btn_uninstall'));
		$form->addField(GDT_Submit::make('enable')->label('btn_enable'));
		$form->addField(GDT_Submit::make('disable')->label('btn_disable'));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function executeButton(string $button)
	{
		$form = $this->getForm();
		if (!$form->validateForm())
		{
			return parent::formInvalid($form);
		}
		Cache::unset('gdo_modules');
		return call_user_func(array($this, "execute_$button"));
	}
	
	public function execute_install()
	{
		Installer::installModule($this->configModule);
		return Message::message('msg_module_installed', [$this->configModule->getName()]);
	}
	
	public function execute_uninstall()
	{
		Installer::dropModule($this->configModule);
		return Message::message('msg_module_uninstalled', [$this->configModule->getName()]);
	}
	
	public function execute_enable()
	{
		$this->configModule->saveVar('module_enabled', '1');
		Cache::unset('gdo_modules');
		return Message::message('msg_module_enabled', [$this->configModule->getName()]);
	}

	public function execute_disable()
	{
		$this->configModule->saveVar('module_enabled', '0');
		Cache::unset('gdo_modules');
		return Message::message('msg_module_disabled', [$this->configModule->getName()]);
	}
	
}
