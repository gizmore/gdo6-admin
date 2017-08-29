<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Table\MethodQueryTable;
use GDO\Type\GDT_String;
use GDO\UI\GDT_IconButton;
use GDO\User\GDT_Username;
use GDO\User\GDO_User;
/**
 * GDO_User table for admins
 * 
 * @author gizmore
 * @see User
 * @see GWF_Table
 */
class Users extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function getGDO()
	{
		return GDO_User::table();
	}
	
	public function getQuery()
	{
		return $this->getGDO()->select('*');
	}
	
	public function getHeaders()
	{
		$gdo = $this->getGDO();
		return array(
// 			GDT_RowNum::make(),
			GDT_IconButton::make('edit_admin')->icon('edit'),
			$gdo->gdoColumn('user_id'),
			$gdo->gdoColumn('user_country'),
		    GDT_String::make('user_name'),
			$gdo->gdoColumn('user_type'),
			$gdo->gdoColumn('user_level'),
		    GDT_Username::make('username'),
		    $gdo->gdoColumn('user_credits'),
			$gdo->gdoColumn('user_email'),
		);
	}
	
}
