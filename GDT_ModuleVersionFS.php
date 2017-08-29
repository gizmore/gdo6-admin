<?php
namespace GDO\Admin;

use GDO\Core\Module;
use GDO\DB\GDO;
use GDO\Type\GDT_Int;

final class GDT_ModuleVersionFS extends GDT_Int
{
	/**
	 * @return Module
	 */
	public function getModule() { return $this->gdo; }
	
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
		return $va == $vb ? true : ($va < $vb ? -1 : 1);
	}
}
