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
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
				[
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
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
            'menuEvents',
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
					'showstars'     => 'required|numeric|min:0|max:1',
					'Star1'         => 'required|numeric|min:1',
					'Star2'         => 'required|numeric|min:1',
					'Star3'         => 'required|numeric|min:1',
					'Star4'         => 'required|numeric|min:1',
					'Star5'         => 'required|numeric|min:1',
				]);

				if ($validation->isValid()) {
					$this->getConfig()->set('radio_hoerercharts_showstars', $this->getRequest()->getPost('showstars'))
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

			$this->getView()->set('showstars', $this->getConfig()->get('radio_hoerercharts_showstars'))
				->set('Star1', $this->getConfig()->get('radio_hoerercharts_Star1'))
				->set('Star2', $this->getConfig()->get('radio_hoerercharts_Star2'))
				->set('Star3', $this->getConfig()->get('radio_hoerercharts_Star3'))
				->set('Star4', $this->getConfig()->get('radio_hoerercharts_Star4'))
				->set('Star5', $this->getConfig()->get('radio_hoerercharts_Star5'));
        }
	}
}
