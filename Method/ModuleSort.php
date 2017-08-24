<?php
namespace GDO\Admin\Method;

use GDO\Core\Method;
use GDO\Core\Module;
use GDO\DB\Cache;
use GDO\Table\MethodSort;
/**
 * Drag and drop sorting of modules.
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
final class ModuleSort extends MethodSort
{
	/**
	 * Only staff may sort modules for navbar appearance.
	 * {@inheritDoc}
	 * @see Method::getPermission()
	 */
	public function getPermission() { return 'staff'; }

	public function gdoSortObjects() { return Module::table(); }

	public function execute()
	{
		$response = parent::execute();
		Cache::unset('gwf_modules');
		return $response;
	}
}
