<?php
namespace GDO\Admin\Method;

use GDO\Core\MethodAdmin;
use GDO\Table\MethodQueryTable;
use GDO\DB\GDT_String;
use GDO\UI\GDT_IconButton;
use GDO\User\GDT_Username;
use GDO\User\GDO_User;
/**
 * GDO_User table for admins
 * 
 * @author gizmore
 * @see GDO_User
 * @see GWF_Table
 */
class Users extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function execute()
	{
		$createLink = GDT_IconButton::make()->icon('create')->href(href('Admin', 'UserCreate'))->label('link_create_user');
		return $this->renderNavBar()->add(parent::execute())->addField($createLink);
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
			GDT_IconButton::make('edit_admin')->icon('edit'),
			$gdo->gdoColumn('user_id'),
			$gdo->gdoColumn('user_country')->withName(false),
			GDT_String::make('user_name'),
			$gdo->gdoColumn('user_type'),
// 			$gdo->gdoColumn('user_level'),
			GDT_Username::make('username'),
			$gdo->gdoColumn('user_credits'),
			$gdo->gdoColumn('user_email'),
			$gdo->gdoColumn('user_register_time'),
			$gdo->gdoColumn('user_last_activity'),
		);
	}
	
}
