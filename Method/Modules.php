<?php
namespace GDO\Admin\Method;

use GDO\Admin\GDO_ModuleVersionFS;
use GDO\Admin\MethodAdmin;
use GDO\Core\Application;
use GDO\Core\Module;
use GDO\DB\ArrayResult;
use GDO\DB\GDO_Id;
use GDO\Table\GDO_Table;
use GDO\Table\MethodTable;
use GDO\Template\GDO_Box;
use GDO\Type\GDO_Checkbox;
use GDO\Type\GDO_Decimal;
use GDO\Type\GDO_Int;
use GDO\Type\GDO_Name;
use GDO\UI\GDO_Button;
use GDO\Core\ModuleLoader;
/**
 * Overview of modules
 * 
 * @author gizmore
 * 
 */
class Modules extends MethodTable
{
	use MethodAdmin;
	
	public function isPaginated() { return false; }
	
	public function getPermission() { return 'staff'; }
	
	/**
	 * @var Module[]
	 */
	private $modules;
	
	public function execute()
	{
		$this->modules = ModuleLoader::instance()->loadModules(false, true);
		$this->modules = Module::table()->sort($this->modules, 'module_sort');
		$response = parent::execute();
		$navbar = $this->renderNavBar();
		$info = $this->renderInfoBox();
		return $navbar->add($info)->add($response);
	}
	
	public function renderInfoBox()
	{
		return GDO_Box::make()->html(t('msg_there_are_updates'))->render();
	}
	
	public function getResult()
	{
		return new ArrayResult($this->modules, Module::table());
	}
	
	public function getResultCount()
	{
		return count($this->modules);
	}
	
	public function getHeaders()
	{
		return array(
// 			GDO_DeleteButton::make(),
			GDO_Id::make('module_id'),
			GDO_Int::make('module_priority')->unsigned()->label('priority'),
			GDO_Checkbox::make('module_enabled')->label('enabled'),
			GDO_Name::make('module_name')->label('name'),
			GDO_Decimal::make('module_version')->label('version_db'),
			GDO_ModuleVersionFS::make('module_version_fs')->label('version_fs'),
// 			GDO_Button::make('install_module')->label('btn_install'),
			GDO_Button::make('configure_module')->label('btn_configure'),
			GDO_Button::make('administrate_module')->label('btn_admin'),
		);
	}
	
	public function createTable(GDO_Table $table)
	{
		$table->sortable(href('Admin', 'ModuleSort'));
	}
	
}
