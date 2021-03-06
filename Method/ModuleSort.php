<?php
namespace GDO\Admin\Method;

use GDO\Core\Method;
use GDO\Core\GDO_Module;
use GDO\DB\Cache;
use GDO\Table\MethodSort;

/**
 * Drag and drop sorting of modules.
 * 
 * @author gizmore
 * @version 6.10.1
 * @since 5.0.0
 */
final class ModuleSort extends MethodSort
{
	/**
	 * Only staff may sort modules for navbar appearance.
	 * {@inheritDoc}
	 * @see Method::getPermission()
	 */
	public function getPermission() { return 'staff'; }

	public function gdoSortObjects() { return GDO_Module::table(); }

	public function afterExecute()
	{
		Cache::remove('gdo_modules');
	}

}
