<?php
namespace GDO\Admin;

use GDO\UI\GDT_Link;
use GDO\DB\GDT_String;

/**
 * Is searchable, filterable, orderarble because it's the modulename.
 * @author gizmore
 */
final class GDT_ModuleNameLink extends GDT_Link
{
    public $orderable = true;
    public $filterable = true;
    public $searchable = true;
    
	public function renderCell()
	{
		$this->label($this->gdo->getName());
		$this->href(href('Admin', 'Configure',
		    "&module=".$this->gdo->getName()));
		return parent::renderCell();
	}
	
}
