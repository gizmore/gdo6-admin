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
use GDO\Core\ModuleLoader;
use GDO\Language\Trans;
use GDO\UI\GDT_Panel;
use GDO\Core\GDT_Response;
use GDO\Core\GDT_Module;

/**
 * Configure a module.
 * @TODO: Move to core or make admin a core module?
 * @author gizmore
 * @version 6.10.1
 * @since 3.4.0
 */
class Configure extends MethodForm
{
	use MethodAdmin;
	
	/**
	 * @var GDO_Module
	 */
	private $configModule;
	
	public function getPermission() { return 'admin'; }
	
	public function gdoParameters()
	{
	    return [
	        GDT_Module::make('module')->notNull(),
	    ];
	}
	
	public function init()
	{
	    # Load
	    ModuleLoader::instance()->loadModules(false, true);
	    $this->configModule = $this->gdoParameterValue('module');
	}
	
	public function execute()
	{
	    # Response
		$response = GDT_Response::make();
		
		# Response for install and configure
		if ($descr = $this->configModule->getModuleDescription())
		{
			$panelDescr = GDT_Panel::make()->textRaw($descr);
			$response->addField($panelDescr);
		}
		
		# Response for install panel
		$response->add($this->renderInstall());
		
		# Configuration if installed
		if ($this->configModule->isPersisted())
		{
			$response->add(parent::execute()); # configure
		}
		
		# Respond
		return $response;
	}
	
	public function renderInstall()
	{
		return Install::make()->executeWithInit();
	}
	
	public function getTitle()
	{
	    return t('ft_admin_configure', [$this->configModule->displayName()]);
	}
	
	public function getDescription()
	{
	    return t('mdescr_admin_configure', [$this->configModule->displayName()]);
	}
	
	public function createForm(GDT_Form $form)
	{
		$mod = $this->configModule;
// 		$form->tit
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
				$gdoType->label('cfg_' . $gdoType->name);
				$key = 'cfg_tt_' . $gdoType->name;
				if (Trans::hasKey($key))
				{
					$gdoType->tooltip($key);
				}
				$form->addField($gdoType); #->var($mod->getConfigVar($gdoType->name)));
			}
		}
		$form->actions()->addField(GDT_Submit::make()->label('btn_save'));
		$form->actions()->addField(GDT_AntiCSRF::make());
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
				$info[] = t('msg_modulevar_changed',
				    [$gdoType->displayLabel(),
				        $gdoType->displayValue($gdoType->initial),
				        $gdoType->displayValue($gdoType->getVar())]);
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
