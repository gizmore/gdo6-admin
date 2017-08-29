<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Core\GDT_Hook;
use GDO\Core\GDO_Module;
use GDO\Core\GDO_ModuleVar;
use GDO\DB\Cache;
use GDO\File\GDT_Path;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Type\GDT_Name;
use GDO\Type\GDT_Version;
use GDO\UI\GDT_Divider;
use GDO\Util\Common;
use GDO\Core\ModuleLoader;

class Configure extends MethodForm
{
	use MethodAdmin;
	
	/**
	 * @var GDO_Module
	 */
	private $configModule;
	
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
	    ModuleLoader::instance()->loadModules(false, true);
	    if (!($this->configModule = ModuleLoader::instance()->getModule(Common::getRequestString('module'))))
		{
			return $this->error('err_module')->add($this->execMethod('Modules'));
		}
		
		return $this->renderNavBar()->add($this->renderInstall()->add(parent::execute()));
	}
	
	public function renderInstall()
	{
		return $this->execMethod('Install');
	}
	
	public function createForm(GDT_Form $form)
	{
		$mod = $this->configModule;
		$this->title(t('ft_admin_configure', [sitename(), $this->configModule->getName()]));
		$form->addField(GDT_Name::make('module_name')->writable(false));
		$form->addField(GDT_Path::make('module_path')->writable(false)->initial($mod->filePath()));
		$form->addField(GDT_Version::make('module_version')->writable(false));
		$form->addField(GDT_Version::make('version_available')->writable(false)->value($mod->module_version));
		if ($config = $mod->getConfigCache())
		{
			$form->addField(GDT_Divider::make('div1')->label('form_div_config_vars'));
			foreach ($config as $gdoType)
			{
				$form->addField($gdoType);
			}
		}
		$form->addField(GDT_Submit::make()->label('btn_save'));
		$form->addField(GDT_AntiCSRF::make());
		# Prefill with module
		$form->withGDOValuesFrom($this->configModule);
	}

	public function formValidated(GDT_Form $form)
	{
		$mod = $this->configModule;
		
		# Update config
		$info = [];
		$moduleVarsChanged = false;
		foreach ($form->getFields() as $gdoType)
		{
			if ($gdoType->hasChanged() && $gdoType->writable && $gdoType->editable)
			{
			    GDO_ModuleVar::createModuleVar($mod, $gdoType);
				$info[] = t('msg_modulevar_changed', [$gdoType->name, html($gdoType->initial), html($gdoType->getVar())]);
				$moduleVarsChanged = true;
			}
		}
		
		if ($moduleVarsChanged)
		{
		    GDT_Hook::call('ModuleVarsChanged', $mod);
		}
		
		
		if (count($info) > 0)
		{
			Cache::unset('gdo_modules');
		}
		
		# Announce
		return $this->message('msg_module_saved', [implode('<br/>', $info)])->add($this->renderPage());
	}
}