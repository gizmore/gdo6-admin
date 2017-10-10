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
use GDO\Core\ModuleLoader;
use GDO\UI\GDT_Bar;

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
		ModuleLoader::instance()->loadModules(true, true);
		
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
		$bar = GDT_Bar::makeWith(
			GDT_Submit::make('install')->label('btn_install'),
			GDT_Submit::make('uninstall')->label('btn_uninstall'),
			GDT_Submit::make('enable')->label('btn_enable'),
			GDT_Submit::make('disable')->label('btn_disable')
		)->horizontal();
		$form->addField($bar);
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function executeButton($button)
	{
		$form = $this->getForm();
		if (!$form->validateForm())
		{
			return parent::formInvalid($form);
		}
		Cache::remove('gdo_modules');
		return call_user_func(array($this, "execute_$button"));
	}
	
	public function execute_install()
	{
		Installer::installModule($this->configModule);
		return $this->message('msg_module_installed', [$this->configModule->getName()]);
	}
	
	public function execute_uninstall()
	{
		Installer::dropModule($this->configModule);
		return $this->message('msg_module_uninstalled', [$this->configModule->getName()]);
	}
	
	public function execute_enable()
	{
		$this->configModule->saveVar('module_enabled', '1');
		Cache::remove('gdo_modules');
		return $this->message('msg_module_enabled', [$this->configModule->getName()]);
	}

	public function execute_disable()
	{
		$this->configModule->saveVar('module_enabled', '0');
		Cache::remove('gdo_modules');
		return $this->message('msg_module_disabled', [$this->configModule->getName()]);
	}
	
}
