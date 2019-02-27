<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Controllers\Admin;

use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
			[
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa fa-trash',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'hoerercharts',
            $items
        );
    }

    public function indexAction() 
    {
		$hoererchartsMapper = new HoererChartsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hoerercharts'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

		if ($hoererchartsMapper->checkDB()){
			if ($this->getRequest()->isPost()) {
				$validation = Validation::create($this->getRequest()->getPost(), [
					'Program_Name'     => 'required',
					'guestallow'     => 'required|numeric|min:0|max:1',
					'showstars'     => 'required|numeric|min:0|max:1',
					'Star1'         => 'required|numeric|min:1',
					'Star2'         => 'required|numeric|min:1',
					'Star3'         => 'required|numeric|min:1',
					'Star4'         => 'required|numeric|min:1',
					'Star5'         => 'required|numeric|min:1',
				]);

				if ($validation->isValid()) {
					$this->getConfig()->set('radio_hoerercharts_Program_Name', $this->getRequest()->getPost('Program_Name'))
						->set('radio_hoerercharts_Guest_Allow', $this->getRequest()->getPost('guestallow'))
						->set('radio_hoerercharts_showstars', $this->getRequest()->getPost('showstars'))
						->set('radio_hoerercharts_Star1', $this->getRequest()->getPost('Star1'))
						->set('radio_hoerercharts_Star2', $this->getRequest()->getPost('Star2'))
						->set('radio_hoerercharts_Star3', $this->getRequest()->getPost('Star3'))
						->set('radio_hoerercharts_Star4', $this->getRequest()->getPost('Star4'))
						->set('radio_hoerercharts_Star5', $this->getRequest()->getPost('Star5'));

					$this->redirect()
						->withMessage('saveSuccess')
						->to(['action' => 'index']);
				}

				$this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
				$this->redirect()
					->withInput()
					->withErrors($validation->getErrorBag())
					->to(['action' => 'index']);
			}

			$this->getView()->set('Program_Name', $this->getConfig()->get('radio_hoerercharts_Program_Name'))
				->set('guestallow', $this->getConfig()->get('radio_hoerercharts_Guest_Allow'))
				->set('showstars', $this->getConfig()->get('radio_hoerercharts_showstars'))
				->set('Star1', $this->getConfig()->get('radio_hoerercharts_Star1'))
				->set('Star2', $this->getConfig()->get('radio_hoerercharts_Star2'))
				->set('Star3', $this->getConfig()->get('radio_hoerercharts_Star3'))
				->set('Star4', $this->getConfig()->get('radio_hoerercharts_Star4'))
				->set('Star5', $this->getConfig()->get('radio_hoerercharts_Star5'));
        }
	}
	
	public function votedatetimeAction() 
    {
		$hoererchartsMapper = new HoererChartsMapper();
		
		$this->getLayout()->setFile('modules/admin/layouts/iframe');
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hoerercharts'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'votedatetime']);

		if ($hoererchartsMapper->checkDB()){
			if ($this->getRequest()->isPost()) {
				if (trim($this->getRequest()->getPost('start_datetime'))) $this->getConfig()->set('radio_hoerercharts_Start_Datetime', new \Ilch\Date($this->getRequest()->getPost('start_datetime')));
				else $this->getConfig()->set('radio_hoerercharts_Start_Datetime', '');
				if (trim($this->getRequest()->getPost('end_datetime'))) $this->getConfig()->set('radio_hoerercharts_End_Datetime', new \Ilch\Date($this->getRequest()->getPost('end_datetime')));
				else $this->getConfig()->set('radio_hoerercharts_End_Datetime', '');

				$this->redirect()
				->withMessage('saveSuccess')
				->to(['action' => 'votedatetime']);
			}
			
			$start_datetime = null;
			if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
			$end_datetime = null;
			if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
			$formatdatetime = 'd.m.Y H:i';

			$this->getView()->set('start_datetime', (($start_datetime)?$start_datetime->format($formatdatetime):''))
				->set('end_datetime', (($end_datetime)?$end_datetime->format($formatdatetime):''));
        }
	}
}
