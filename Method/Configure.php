<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Core\Application;
use GDO\Core\GDO_Hook;
use GDO\Core\Module;
use GDO\Core\ModuleVar;
use GDO\DB\Cache;
use GDO\File\GDO_Path;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\Type\GDO_Name;
use GDO\Type\GDO_Version;
use GDO\UI\GDO_Divider;
use GDO\Util\Common;
use GDO\Core\ModuleLoader;

class Configure extends MethodForm
{
	use MethodAdmin;
	
	/**
	 * @var Module
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
	
	public function createForm(GDO_Form $form)
	{
		$mod = $this->configModule;
		$this->title(t('ft_admin_configure', [sitename(), $this->configModule->getName()]));
		$form->addField(GDO_Name::make('module_name')->writable(false));
		$form->addField(GDO_Path::make('module_path')->writable(false)->initial($mod->filePath()));
		$form->addField(GDO_Version::make('module_version')->writable(false));
		$form->addField(GDO_Version::make('version_available')->writable(false)->value($mod->module_version));
		if ($config = $mod->getConfigCache())
		{
			$form->addField(GDO_Divider::make('div1')->label('form_div_config_vars'));
			foreach ($config as $gdoType)
			{
				$form->addField($gdoType);
			}
		}
		$form->addField(GDO_Submit::make()->label('btn_save'));
		$form->addField(GDO_AntiCSRF::make());
		# Prefill with module
		$form->withGDOValuesFrom($this->configModule);
	}

	public function formValidated(GDO_Form $form)
	{
		$mod = $this->configModule;
		
		# Update config
		$info = [];
		$moduleVarsChanged = false;
		foreach ($form->getFields() as $gdoType)
		{
			if ($gdoType->hasChanged() && $gdoType->writable && $gdoType->editable)
			{
				ModuleVar::createModuleVar($mod, $gdoType);
				$info[] = t('msg_modulevar_changed', [$gdoType->name, html($gdoType->initial), html($gdoType->getVar())]);
				$moduleVarsChanged = true;
			}
		}
		
		if ($moduleVarsChanged)
		{
		    GDO_Hook::call('ModuleVarsChanged', $mod);
		}
		
		
		if (count($info) > 0)
		{
			Cache::unset('gwf_modules');
		}
		
		# Announce
		return $this->message('msg_module_saved', [implode('<br/>', $info)])->add($this->renderPage());
	}
}
