<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Core\GDT_Hook;
use GDO\Core\GDO_Module;
use GDO\Core\GDO_ModuleVar;
use GDO\DB\Cache;
use GDO\File\GDT_Path;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\DB\GDT_Name;
use GDO\DB\GDT_Version;
use GDO\UI\GDT_Divider;
use GDO\Util\Common;
use GDO\Core\ModuleLoader;
use GDO\Language\Trans;
use GDO\UI\GDT_Paragraph;
use GDO\UI\GDT_Panel;
use GDO\Core\GDT_Response;

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
		# Load
		ModuleLoader::instance()->loadModules(false, true);
		if (!($this->configModule = ModuleLoader::instance()->getModule(Common::getRequestString('module'))))
		{
			return $this->error('err_module')->add(Modules::make()->execMethod());
		}
		
		$response = GDT_Response::make();
		
		# Response for install and configure
		if ($descr = $this->configModule->getModuleDescription())
		{
			$panelDescr = GDT_Panel::makeWith(GDT_Paragraph::withHTML($descr));
			$response->addField($panelDescr);
		}
		
		$response->add($this->renderInstall());
		
		if ($this->configModule->isPersisted())
		{
			$response->add(parent::execute()); # configure
		}
		
		return $response;
	}
	
	public function renderInstall()
	{
		return Install::make()->execMethod();
	}
	
	public function createForm(GDT_Form $form)
	{
		$mod = $this->configModule;
		$this->title(t('ft_admin_configure', [$this->configModule->getName()]));
		$form->addField(GDT_Name::make('module_name')->writable(false));
		$form->addField(GDT_Path::make('module_path')->writable(false)->initial($mod->filePath()));
		$form->addField(GDT_Version::make('module_version')->writable(false));
		$form->addField(GDT_Version::make('version_available')->writable(false)->initial($mod->module_version));
		$form->withGDOValuesFrom($this->configModule);
		if ($config = $mod->getConfigCache())
		{
			$form->addField(GDT_Divider::make('div1')->label('form_div_config_vars'));
			foreach ($config as $gdoType)
			{
// 				if (Trans::hasKey('cfg_' . $gdoType->name) || (!$gdoType->hasName()))
				{
					$gdoType->label('cfg_' . $gdoType->name);
				}
				$key = 'cfg_tt_' . $gdoType->name;
				if (Trans::hasKey($key))
				{
					$gdoType->tooltip($key);
				}
				$form->addField($gdoType->var($mod->getConfigVar($gdoType->name)));
			}
		}
		$form->addField(GDT_Submit::make()->label('btn_save'));
		$form->addField(GDT_AntiCSRF::make());
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
				$info[] = t('msg_modulevar_changed', [$gdoType->displayLabel(), html($gdoType->initial), html($gdoType->getVar())]);
				$moduleVarsChanged = true;
			}
		}
		
		if ($moduleVarsChanged)
		{
			Cache::flush();
			GDT_Hook::callWithIPC('ModuleVarsChanged', $mod);
		}
		
		# Announce
		return $this->message('msg_module_saved', [implode('<br/>', $info)])->add($this->renderPage());
	}
}
