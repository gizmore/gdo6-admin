<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Core\GDT_Hook;
use GDO\Core\Method;
use GDO\DB\Cache;
use GDO\File\FileUtil;
use GDO\Util\MinifyJS;

final class ClearCache extends Method
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		Cache::flush();
		GDT_Hook::call('ClearCache');
		FileUtil::removeDir(MinifyJS::tempDirS());
		return $this->renderNavBar()->add($this->message('msg_cache_flushed'));
	}
}
