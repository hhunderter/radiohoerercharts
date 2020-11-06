<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Modules\RadioHoererCharts\Models\HoererCharts as HoererChartsModel;
use Modules\RadioHoererCharts\Mappers\HoererChartsSuggestion as HoererChartsSuggestionMapper;
use Modules\RadioHoererCharts\Mappers\HoererChartsUserVotes as HoererChartsUserVotesMapper;
use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as HoererChartsUserVotesModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        $hoererchartsuservotesMapper = new HoererChartsUserVotesMapper();
        $userMapper = new UserMapper;

        $radio_hoerercharts_Program_Name = $this->getConfig()->get('radio_hoerercharts_Program_Name');

        $hoererchartsconfig = [ 'guestallow' => $this->getConfig()->get('radio_hoerercharts_Guest_Allow'),
            'allowsuggestion' => $this->getConfig()->get('radio_hoerercharts_allow_suggestion'),
            'Program_Name' => ($radio_hoerercharts_Program_Name ? $radio_hoerercharts_Program_Name : $this->getTranslator()->trans('hoerercharts')),
            'show_artwork' => $this->getConfig()->get('radio_hoerercharts_show_artwork')];

        $this->getLayout()->getTitle()
            ->add($hoererchartsconfig['Program_Name']);
        $this->getLayout()->getHmenu()
                ->add($hoererchartsconfig['Program_Name'], ['action' => 'index']);

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
        $this->getView()->set('hoererchartsMapper', $hoererchartsMapper);

        $start_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) {
            $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
        }
        $end_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) {
            $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
        }

        $this->getView()->set('config', $hoererchartsconfig);
        $this->getView()->set('userMapper', $userMapper);

        $this->getView()->set('votedatetime', ((!$start_datetime && !$end_datetime)?'':$this->getTranslator()->trans('votedatetime').(($start_datetime && $end_datetime)?call_user_func_array([$this->getTranslator(), 'trans'], ['fromto', $start_datetime->format($this->getTranslator()->trans('datetimeformat')),$end_datetime->format($this->getTranslator()->trans('datetimeformat'))]):(($start_datetime)?$this->getTranslator()->trans('from').' '.$start_datetime->format($this->getTranslator()->trans('datetimeformat')):$this->getTranslator()->trans('to').' '.$end_datetime->format($this->getTranslator()->trans('datetimeformat'))))."<br><br>"));
        $vote_allowed = $hoererchartsMapper->vote_allowed($start_datetime, $end_datetime);
        $this->getView()->set('vote_allowed', $vote_allowed);
        $show_sortedlist = $hoererchartsMapper->is_showsortedlist($end_datetime);
        $this->getView()->set('show_sortedlist', $show_sortedlist);

        if ($hoererchartsMapper->checkDB() && $hoererchartssuggestionMapper->checkDB() && $hoererchartsuservotesMapper->checkDB()) {
            if ($hoererchartsuservotesMapper->is_voted($this->getUser()) || !$vote_allowed) {
                $this->getView()->set('voted', true);
                
                if ($show_sortedlist) {
                    $this->getView()->set('entries', $hoererchartsMapper->getEntryByList(['l.list' => $this->getConfig()->get('radio_hoerercharts_active_list')], ['h.votes' => 'DESC','h.id' => 'DESC']));
                } else {
                    $this->getView()->set('entries', $hoererchartsMapper->getEntryByList(['l.list' => $this->getConfig()->get('radio_hoerercharts_active_list')], ['h.datecreate' => 'ASC','h.id' => 'DESC']));
                }
            } else {
                if ($this->getRequest()->getPost('saveHoererCharts')) {
                    $validation_indb = Validation::create($this->getRequest()->getPost(), ['hoerercharts-d' => 'required|unique:radio_hoerercharts,id']);
                    $validation = Validation::create($this->getRequest()->getPost(), ['hoerercharts-d' => 'required|numeric']);

                    if ($validation->isValid() && !$validation_indb->isValid()) {
                        $hoererchartsMapper->update($this->getRequest()->getPost('hoerercharts-d'), -1);

                        $voteId = $hoererchartsuservotesMapper->getEntryByUserSession($this->getUser());

                        $date = new \Ilch\Date();
                        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));

                        $model = new HoererChartsUserVotesModel();
                        if ($voteId) {
                            $model->setId($voteId);
                        }
                        if ($this->getUser()) {
                            $model->setUser_Id($this->getUser()->getId());
                        }
                        $model->setSessionId(session_id());
                        $model->setLast_Activity($datenow);
                        $hoererchartsuservotesMapper->save($model);

                        $hoererchartsuservotesMapper->setIsVotedCookie(session_id());

                        $this->redirect()
                            ->withMessage('saveSuccess')
                            ->to(['action' => 'index']);
                    }
                    if ($validation->isValid() && $validation_indb->isValid()) {
                        $validation->getErrorBag()->addError('hoerercharts-d', 'Manipulation');
                    }
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'index']);
                }
                $this->getView()->set('entries', $hoererchartsMapper->getEntryByList(['l.list' => $this->getConfig()->get('radio_hoerercharts_active_list')]));
            }

            $this->getView()->set('gettext', (!empty($this->getRequest()->getParam('copy'))?$hoererchartsMapper->gettext():''));
        } else {
            $this->addMessage('dbfail');
        }
    }
    
    public function treatAction()
    {
        if ($this->getConfig()->get('radio_hoerercharts_allow_suggestion') && ((!$this->getUser() && $this->getConfig()->get('radio_hoerercharts_Guest_Allow')) || $this->getUser())) {
            $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
            $captchaNeeded = captchaNeeded();

            $radio_hoerercharts_Program_Name = $this->getConfig()->get('radio_hoerercharts_Program_Name');

            $Program_Name = ($radio_hoerercharts_Program_Name ? $radio_hoerercharts_Program_Name : $this->getTranslator()->trans('hoerercharts'));

            if ($hoererchartssuggestionMapper->checkDB()) {
                $this->getLayout()->getTitle()
                    ->add($Program_Name);
                $this->getLayout()->getHmenu()
                    ->add($Program_Name, ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);

                $this->getView()->set('captchaNeeded', $captchaNeeded);

                if ($this->getRequest()->isPost()) {
                    $validation = Validation::create($this->getRequest()->getPost(), array_merge([
                        'interpret'     => 'required',
                        'songtitel'     => 'required'
                    ], ($captchaNeeded?['captcha' => 'captcha']:[])));

                    if ($validation->isValid()) {
                        $hoererchartsModel = new HoererChartsModel();
                        $date = new \Ilch\Date();
                        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));

                        if ($this->getUser()) {
                            $hoererchartsModel->setUser_Id($this->getUser()->getId());
                        }

                        $hoererchartsModel->setInterpret($this->getRequest()->getPost('interpret'))
                            ->setSongTitel($this->getRequest()->getPost('songtitel'))
                            ->setVotes(0)
                            ->setDateCreate($datenow);

                        $hoererchartssuggestionMapper->save($hoererchartsModel);

                        $this->redirect()
                            ->withMessage('saveSuccess')
                            ->to(['action' => 'index']);
                    }
                    $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'treat']);
                }
            } else {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->redirect(['action' => 'index']);
        }
    }
}
