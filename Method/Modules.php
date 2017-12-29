<?php
namespace GDO\Admin\Method;
use GDO\Admin\GDT_ModuleVersionFS;
use GDO\Core\MethodAdmin;
use GDO\Core\GDO_Module;
use GDO\DB\ArrayResult;
use GDO\DB\GDT_Id;
use GDO\Table\GDT_Table;
use GDO\Table\MethodTable;
use GDO\UI\GDT_Panel;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Decimal;
use GDO\DB\GDT_Int;
use GDO\DB\GDT_Name;
use GDO\UI\GDT_Button;
use GDO\Core\ModuleLoader;
use GDO\Table\GDT_Sort;
use GDO\Core\GDT_Fields;
use GDO\Core\GDT_Template;
use GDO\Admin\GDT_ModuleNameLink;
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
		$info = $this->renderInfoBox();
		return $navbar->addField($info)->add($response);
	}
	
	public function renderInfoBox()
	{
		return GDT_Panel::make()->html(t('msg_there_are_updates'));
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
// 			GDT_DeleteButton::make(),
		    GDT_Id::make('module_id'),
		    GDT_Sort::make('module_sort')->label('sort'),
		    GDT_Int::make('module_priority')->unsigned()->label('priority'),
			GDT_Checkbox::make('module_enabled')->label('enabled'),
			GDT_ModuleNameLink::make('module_name')->label('name'),
			GDT_Decimal::make('module_version')->label('version_db'),
			GDT_ModuleVersionFS::make('module_version_fs')->label('version_fs'),
// 			GDT_Button::make('install_module')->label('btn_install'),
// 			GDT_Template::make()->tem
			GDT_Button::make('configure_module')->label('btn_configure'),
			GDT_Button::make('administrate_module')->label('btn_admin'),
		);
	}
	
	public function createTable(GDT_Table $table)
	{
		$table->sortable(href('Admin', 'ModuleSort'));
	}
	
}
