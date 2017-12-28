<?php
namespace GDO\Admin;

use GDO\UI\GDT_Link;

final class GDT_ModuleNameLink extends GDT_Link
{
	public function renderCell()
	{
		$this->label($this->gdo->getName());
		$this->href(href('Admin', 'Configure', "&module=".$this->gdo->getName()));
		return parent::renderCell();
	}
}
