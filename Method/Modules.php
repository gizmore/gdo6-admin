<?php
namespace GDO\Admin\Method;
use GDO\Admin\GDT_ModuleVersionFS;
use GDO\Core\MethodAdmin;
use GDO\Core\GDO_Module;
use GDO\DB\ArrayResult;
use GDO\DB\GDT_Id;
use GDO\Table\GDT_Table;
use GDO\Table\MethodTable;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Decimal;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Button;
use GDO\Core\ModuleLoader;
use GDO\Table\GDT_Sort;
use GDO\Admin\GDT_ModuleNameLink;
use GDO\Admin\GDT_ModuleAdminButton;
/**
 * Overview of modules
 * @author gizmore
 * @since 3.00
 * @version 6.05
 */
class Modules extends MethodTable
{
	use MethodAdmin;
	
	public function isFiltered() { return true; }
	public function isPaginated() { return false; }
	
	public function getDefaultOrder() { return 'module_name'; }
	
	public function getPermission() { return 'staff'; }
	
	/**
	 * @var GDO_Module[]
	 */
	private $modules;
	
	public function execute()
	{
		$this->modules = ModuleLoader::instance()->loadModules(true, true);
		$response = parent::execute();
		$navbar = $this->renderNavBar();
		return $navbar->add($response);
	}
	
	public function getResult()
	{
		return ArrayResult::filtered($this->modules, GDO_Module::table(), $this->getHeaders());
	}
	
	public function getResultCount()
	{
		return count($this->modules);
	}
	
	public function getHeaders()
	{
		return array(
			GDT_Sort::make('module_sort')->label('sort'),
// 			GDT_Int::make('module_priority')->unsigned()->label('priority'),
			GDT_Checkbox::make('module_enabled')->label('enabled'),
			GDT_Decimal::make('module_version')->label('version_db'),
			GDT_ModuleVersionFS::make('module_version_fs')->label('version_fs'),
			GDT_ModuleNameLink::make('module_name')->label('name'),
// 			GDT_Button::make('configure_module')->label('btn_configure'),
			GDT_ModuleAdminButton::make('administrate_module')->label('btn_admin'),
		);
	}
	
	public function createTable(GDT_Table $table)
	{
		$table->sortable(href('Admin', 'ModuleSort'));
	}
	
}
