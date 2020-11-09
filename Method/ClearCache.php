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
	
	public function saveLastUrl() { return false; }
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		# Flush memcached.
		Cache::flush();
		# Remove minified JS
		FileUtil::removeDir(MinifyJS::tempDirS());
		# Call hook
		GDT_Hook::callWithIPC('ClearCache');
		# Retrigger assets
		$core = Module_Core::instance();
		$assetVersion = $core->cfgAssetVersion() + 0.01;
		$core->saveConfigVar('asset_revision', sprintf('%.02f', round($assetVersion, 2)));
		# Done
		Website::redirectMessage('msg_cache_flushed', null, Website::hrefBack());
	}

}
