<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Controllers;

use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Modules\RadioHoererCharts\Mappers\HoererChartsUserVotes as HoererChartsUserVotesMapper;
use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as HoererChartsUserVotesModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
		$hoererchartsMapper = new HoererChartsMapper();
		$hoererchartsuservotesMapper = new HoererChartsUserVotesMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('hoerercharts'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index']);

		$this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
		$this->getView()->set('hoererchartsMapper', $hoererchartsMapper);
		$hoererchartsconfig = array(	'guestallow'=>$this->getConfig()->get('radio_hoerercharts_Guest_Allow'),
										'showstars'=>$this->getConfig()->get('radio_hoerercharts_showstars'),
										'Star1'=>$this->getConfig()->get('radio_hoerercharts_Star1'),
										'Star2'=>$this->getConfig()->get('radio_hoerercharts_Star2'),
										'Star3'=>$this->getConfig()->get('radio_hoerercharts_Star3'),
										'Star4'=>$this->getConfig()->get('radio_hoerercharts_Star4'),
										'Star5'=>$this->getConfig()->get('radio_hoerercharts_Star5'));
		$this->getView()->set('config', $hoererchartsconfig);

		if ($hoererchartsMapper->checkDB()){
			if ($hoererchartsuservotesMapper->is_voted($this->getUser(), $hoererchartsconfig['guestallow'])){
				$this->getView()->set('voted', true);
				$this->getView()->set('entries', $hoererchartsMapper->getEntriesBy([], ['votes' => 'DESC','id' => 'DESC']));
			}else{
				if ($this->getRequest()->getPost('saveHoererCharts')) {
					$validation_indb = Validation::create($this->getRequest()->getPost(), ['hoerercharts-d' => 'required|unique:radio_hoerercharts,id']);
					$validation = Validation::create($this->getRequest()->getPost(), ['hoerercharts-d' => 'required|numeric']);

					if ($validation->isValid() and !$validation_indb->isValid()) {
						$hoererchartsMapper->update($this->getRequest()->getPost('hoerercharts-d'), -1);

						$model = new HoererChartsUserVotesModel();
						if ($this->getUser()) $model->setUser_Id($this->getUser()->getId());
						$model->setSessionId(session_id());
						$hoererchartsuservotesMapper->save($model);

						$this->redirect()
							->withMessage('saveSuccess')
							->to(['action' => 'index']);
					}
					if ($validation->isValid() and $validation_indb->isValid()){
						$validation->getErrorBag()->addError('hoerercharts-d','Manipulation');
					}
					$this->redirect()
						->withInput()
						->withErrors($validation->getErrorBag())
						->to(['action' => 'index']);
				}

				$this->getView()->set('gettext', (!empty($this->getRequest()->getParam('copy'))?$hoererchartsMapper->gettext():''));
				$this->getView()->set('entries', $hoererchartsMapper->getEntries([]));
			}
		}
    }
}
