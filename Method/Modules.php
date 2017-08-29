<?php
namespace GDO\Admin\Method;

use GDO\Admin\GDT_ModuleVersionFS;
use GDO\Admin\MethodAdmin;
use GDO\Core\Application;
use GDO\Core\Module;
use GDO\DB\ArrayResult;
use GDO\DB\GDT_Id;
use GDO\Table\GDT_Table;
use GDO\Table\MethodTable;
use GDO\Template\GDT_Box;
use GDO\Type\GDT_Checkbox;
use GDO\Type\GDT_Decimal;
use GDO\Type\GDT_Int;
use GDO\Type\GDT_Name;
use GDO\UI\GDT_Button;
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
		return GDT_Box::make()->html(t('msg_there_are_updates'))->render();
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
// 			GDT_DeleteButton::make(),
			GDT_Id::make('module_id'),
			GDT_Int::make('module_priority')->unsigned()->label('priority'),
			GDT_Checkbox::make('module_enabled')->label('enabled'),
			GDT_Name::make('module_name')->label('name'),
			GDT_Decimal::make('module_version')->label('version_db'),
			GDT_ModuleVersionFS::make('module_version_fs')->label('version_fs'),
// 			GDT_Button::make('install_module')->label('btn_install'),
			GDT_Button::make('configure_module')->label('btn_configure'),
			GDT_Button::make('administrate_module')->label('btn_admin'),
		);
	}
	
	public function createTable(GDT_Table $table)
	{
		$table->sortable(href('Admin', 'ModuleSort'));
	}
	
}
