<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Core\GDT_Hook;
use GDO\Core\Method;
use GDO\DB\Cache;
use GDO\File\FileUtil;
use GDO\Util\MinifyJS;
use GDO\Core\Module_Core;
use GDO\Core\Website;

/**
 * Clears all client and server caches.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.01
 */
final class ClearCache extends Method
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		# Flush memcached and gdo cache.
		Cache::flush();
		# Call hook
		GDT_Hook::callWithIPC('ClearCache');
		# Remove minified JS
		FileUtil::removeDir(MinifyJS::tempDirS());
		# Retrigger assets
		$core = Module_Core::instance();
		$assetVersion = $core->cfgAssetVersion() + 0.01;
		$core->saveConfigVar('asset_revision', $assetVersion);
		# Done
		return $this->renderNavBar()->add($this->message('msg_cache_flushed'))->add(Website::redirectBack(12));
	}

}
