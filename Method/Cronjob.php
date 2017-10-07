<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Core\GDT_Response;
use Exception;
/**
 * Development aid for testing cronjobs.
 * 
 * @author gizmore
 * 
 */
class Cronjob extends MethodForm
{
	use MethodAdmin;
	
	public function isTransactional() { return false; }
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function createForm(GDT_Form $form)
	{
		$form->addField(GDT_Submit::make()->label('btn_run_cronjob'));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		try
		{
			ob_start();
			echo "<pre>"; \GDO\Core\Cronjob::run(); echo "</pre>\n<br/>";
			$response = ob_get_contents();
			return $this->renderPage()->add(GDT_Response::make($response));
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
		finally
		{
			ob_end_clean();
		}
	}
}
