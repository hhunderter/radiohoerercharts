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
		
		$start_datetime = null;
		if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
		$end_datetime = null;
		if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
		$formatdatetime = 'd.m.Y H:i';
		
		$hoererchartsconfig = array(	'guestallow'=>$this->getConfig()->get('radio_hoerercharts_Guest_Allow'),
										'start_datetime'=>$start_datetime,
										'end_datetime'=>$end_datetime,
										'showstars'=>$this->getConfig()->get('radio_hoerercharts_showstars'),
										'Star1'=>$this->getConfig()->get('radio_hoerercharts_Star1'),
										'Star2'=>$this->getConfig()->get('radio_hoerercharts_Star2'),
										'Star3'=>$this->getConfig()->get('radio_hoerercharts_Star3'),
										'Star4'=>$this->getConfig()->get('radio_hoerercharts_Star4'),
										'Star5'=>$this->getConfig()->get('radio_hoerercharts_Star5'),
										'Program_Name'=>(($this->getConfig()->get('radio_hoerercharts_Program_Name'))?$this->getConfig()->get('radio_hoerercharts_Program_Name'):$this->getTranslator()->trans('notset')));
										
		$this->getView()->set('config', $hoererchartsconfig);

		$this->getView()->set('votedatetime', ((!$hoererchartsconfig['start_datetime'] and !$hoererchartsconfig['end_datetime'])?'':$this->getTranslator()->trans('votedatetime').(($hoererchartsconfig['start_datetime'] and $hoererchartsconfig['end_datetime'])?call_user_func_array([$this->getTranslator(), 'trans'], array('fromto', $hoererchartsconfig['start_datetime']->format($formatdatetime),$hoererchartsconfig['end_datetime']->format($formatdatetime))):(($hoererchartsconfig['start_datetime'])?$this->getTranslator()->trans('from').' '.$hoererchartsconfig['start_datetime']->format($formatdatetime):$this->getTranslator()->trans('to').' '.$hoererchartsconfig['end_datetime']->format($formatdatetime)))."<br><br>"));
		if ($hoererchartsMapper->checkDB()){
			if ($hoererchartsuservotesMapper->is_voted($this->getUser(), $hoererchartsconfig['guestallow']) or !$hoererchartsMapper->vote_allowed($hoererchartsconfig['start_datetime'], $hoererchartsconfig['end_datetime'])){
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
				$this->getView()->set('entries', $hoererchartsMapper->getEntries([]));
			}
			
			$this->getView()->set('gettext', (!empty($this->getRequest()->getParam('copy'))?$hoererchartsMapper->gettext():''));
		}
    }
}
