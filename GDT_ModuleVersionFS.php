<?php
namespace GDO\Admin;

use GDO\Core\GDO_Module;
use GDO\Core\GDO;
use GDO\DB\GDT_Int;

final class GDT_ModuleVersionFS extends GDT_Int
{
	/**
	 * @return GDO_Module
	 */
	public function getModule() { return $this->gdo; }
	
	public function gdo(GDO $gdo=null)
	{
	    $this->var = $gdo->module_version;
	    return parent::gdo($gdo);
	}
	
	public function renderCell()
	{
		$module = $this->getModule();
		$class = $module->canUpdate() ? ' class="can-update"' : '';
		return sprintf('<div%s>%.02f</div>', $class, $this->gdo->module_version);
	}

	public function gdoCompare(GDO $a, GDO $b)
	{
		$va = $a->module_version;
		$vb = $b->module_version;
		return $va == $vb ? 0 : ($va < $vb ? -1 : 1);
	}
	
	public function getVar()
	{
	    $m = $this->getModule();
	    return $m ? $m->module_version : '0';
	}

}
