<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Table\MethodQueryTable;
use GDO\Type\GDO_String;
use GDO\UI\GDO_IconButton;
use GDO\User\GDO_Username;
use GDO\User\User;
/**
 * User table for admins
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
		return User::table();
	}
	
	public function getQuery()
	{
		return $this->getGDO()->select('*');
	}
	
	public function getHeaders()
	{
		$gdo = $this->getGDO();
		return array(
// 			GDO_RowNum::make(),
			GDO_IconButton::make('edit_admin')->icon('edit'),
			$gdo->gdoColumn('user_id'),
			$gdo->gdoColumn('user_country'),
		    GDO_String::make('user_name'),
			$gdo->gdoColumn('user_type'),
			$gdo->gdoColumn('user_level'),
		    GDO_Username::make('username'),
		    $gdo->gdoColumn('user_credits'),
			$gdo->gdoColumn('user_email'),
		);
	}
	
}
