<?php
namespace GDO\Admin;

use GDO\UI\GDT_Button;

class GDT_ModuleAdminButton extends GDT_Button
{
	/**
	 * @return \GDO\Core\GDO_Module
	 */
	private function module()
	{
		return $this->gdo;
	}
	
	public function renderCell()
	{
		if ($href = $this->module()->href_administrate_module())
		{
		    $this->href($href);
			return parent::renderCell();
		}
		return '';
	}
	
}
