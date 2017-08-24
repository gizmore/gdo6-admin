<?php
namespace GDO\Admin\Method;

use GDO\Admin\MethodAdmin;
use GDO\Form\GDO_AntiCSRF;
use GDO\Form\GDO_Form;
use GDO\Form\GDO_Submit;
use GDO\Form\MethodForm;
use GDO\Template\Response;
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
	
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->renderNavBar()->add(parent::execute());
	}
	
	public function createForm(GDO_Form $form)
	{
		$form->addField(GDO_Submit::make()->label('btn_run_cronjob'));
		$form->addField(GDO_AntiCSRF::make());
	}
	
	public function formValidated(GDO_Form $form)
	{
		try
		{
			ob_start();
			echo "<pre>"; \GDO\Cronjob\Cronjob::run(); echo "</pre>\n<br/>";
			$response = ob_get_contents();
			return $this->renderPage()->add(Response::make($response));
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
