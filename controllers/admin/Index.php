<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Controllers\Admin;

use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Modules\RadioHoererCharts\Models\HoererCharts as HoererChartsModel;
use Modules\RadioHoererCharts\Mappers\HoererChartsSuggestion as HoererChartsSuggestionMapper;
use Modules\RadioHoererCharts\Mappers\HoererChartsUserVotes as HoererChartsUserVotesMapper;
use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as HoererChartsUserVotesModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
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
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() != 'reset') {
            $items[0][0] = [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ];
            if ($this->getRequest()->getActionName() == 'treat') {
                $items[0][0]['active'] = true;
            } else {
                $items[0]['active'] = true;
            }
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'hoerercharts',
            $items
        );
    }

    public function indexAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $this->getView()->set('hoererchartsMapper', $hoererchartsMapper);

        $start_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
        $end_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
        $formatdatetime = 'd.m.Y H:i';

        $hoererchartsconfig = array('start_datetime'=>$start_datetime,
                                    'allowsuggestion'=>$this->getConfig()->get('radio_hoerercharts_allow_suggestion'),
                                    'end_datetime'=>$end_datetime,
                                    'showstars'=>$this->getConfig()->get('radio_hoerercharts_showstars'),
                                    'Star1'=>$this->getConfig()->get('radio_hoerercharts_Star1'),
                                    'Star2'=>$this->getConfig()->get('radio_hoerercharts_Star2'),
                                    'Star3'=>$this->getConfig()->get('radio_hoerercharts_Star3'),
                                    'Star4'=>$this->getConfig()->get('radio_hoerercharts_Star4'),
                                    'Star5'=>$this->getConfig()->get('radio_hoerercharts_Star5'));
        $this->getView()->set('config', $hoererchartsconfig);
        $this->getView()->set('suggestion', $this->getRequest()->getParam('suggestion'));

        $this->getView()->set('votedatetime', $this->getTranslator()->trans('votedatetime').((!$hoererchartsconfig['start_datetime'] and !$hoererchartsconfig['end_datetime'])?$this->getTranslator()->trans('notset'):(($hoererchartsconfig['start_datetime'] and $hoererchartsconfig['end_datetime'])?call_user_func_array([$this->getTranslator(), 'trans'], array('fromto', $hoererchartsconfig['start_datetime']->format($formatdatetime),$hoererchartsconfig['end_datetime']->format($formatdatetime))):(($hoererchartsconfig['start_datetime'])?$this->getTranslator()->trans('from').' '.$hoererchartsconfig['start_datetime']->format($formatdatetime):$this->getTranslator()->trans('to').' '.$hoererchartsconfig['end_datetime']->format($formatdatetime))).""));
        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->getPost('check_entries')) {
                if ($this->getRequest()->getPost('action') == 'delete') {
                    foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                        if ($this->getRequest()->getParam('suggestion')) $hoererchartssuggestionMapper->delete($entryId);
                        else $hoererchartsMapper->delete($entryId);
                    }
                    $this->addMessage('deleteSuccess');
                    $this->redirect(['action' => 'index']);
                } elseif ($this->getRequest()->getPost('action') == 'setfree') {
                    foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                        $hoererchartsModel = $hoererchartssuggestionMapper->getEntryById($entryId);
                        $hoererchartssuggestionMapper->delete($entryId);
                        $hoererchartsModel->setId(null);
                        $hoererchartsModel->setSetFree(0);
                        $hoererchartsMapper->save($hoererchartsModel);
                    }

                    $this->addMessage('updateSuccess');
                    $this->redirect(['action' => 'index']);
                }
            }
            $suggestionentries = $hoererchartssuggestionMapper->getEntries([]);

            if ($this->getRequest()->getParam('suggestion')) $this->getView()->set('entries', $suggestionentries);
            else $this->getView()->set('entries', $hoererchartsMapper->getEntries([]));
            $this->getView()->set('badgeSuggestion', count($suggestionentries));
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $hoererchartsMapper = new HoererChartsMapper();
            $hoererchartsMapper->update_setfree($this->getRequest()->getParam('id'),$this->getRequest()->getParam('status_man'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function treatAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();

        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->getParam('id')) {
                $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('manage'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

                if ($this->getRequest()->getParam('suggestion')) {
                    $hoererchartsModel = new HoererChartsModel();
                    $this->getView()->set('entrie', $hoererchartssuggestionMapper->getEntryById($this->getRequest()->getParam('id')));
                } else {
                    $hoererchartsModel = $hoererchartsMapper->getEntryById($this->getRequest()->getParam('id'));
                    $this->getView()->set('entrie', $hoererchartsModel);
                }
            }  else {
                $hoererchartsModel = new HoererChartsModel();
                $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('manage'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
            }

            if ($this->getRequest()->isPost()) {
                
                $validation = Validation::create($this->getRequest()->getPost(), array_merge([
                    'interpret' => 'required',
                    'songtitel'    => 'required'
                ],(($this->getRequest()->getParam('suggestion'))?[]:['setfree' => 'required|numeric|min:0|max:1'])));

                if ($validation->isValid()) {
                    $date = new \Ilch\Date();
                    $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s",true));
                    
                    if ($this->getRequest()->getParam('id')) {
                        $hoererchartsModel->setId($this->getRequest()->getParam('id'));
                    } else {
                        if ($this->getUser()) $hoererchartsModel->setUser_Id($this->getUser()->getId());
                        $hoererchartsModel->setDateCreate($datenow);
                    }

                    if (!$this->getRequest()->getParam('suggestion')) $hoererchartsModel->setSetFree($this->getRequest()->getPost('setfree'));
                    $hoererchartsModel->setInterpret($this->getRequest()->getPost('interpret'))
                        ->setSongTitel($this->getRequest()->getPost('songtitel'))
                        ->setVotes(0);

                    if ($this->getRequest()->getParam('suggestion')) $hoererchartssuggestionMapper->save($hoererchartsModel);
                    else $hoererchartsMapper->save($hoererchartsModel);

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(array_merge(['action' => 'index'],(($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
                }
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')],(($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
            }
        } else {
            $this->redirect(array_merge(['action' => 'index'],(($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
        }
    }

    public function delAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        if ($hoererchartsMapper->checkDB()){
            if ($this->getRequest()->isSecure()) {
                if ($this->getRequest()->getParam('suggestion')) $hoererchartssuggestionMapper->delete($this->getRequest()->getParam('id'));
                else $hoererchartsMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('deleteSuccess');
            }
        }
        $this->redirect(array_merge(['action' => 'index'],(($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
    }

    public function allowsuggestionAction()
    {
        $allowsuggestion = $this->getConfig()->get('radio_hoerercharts_allow_suggestion');
        if ($allowsuggestion)
            $this->getConfig()->set('radio_hoerercharts_allow_suggestion', 0);
        else
            $this->getConfig()->set('radio_hoerercharts_allow_suggestion', 1);

        $this->addMessage('updateSuccess');

        $this->redirect(['action' => 'index', 'suggestion' => 'true']);
    }
    
    public function suggestionenableAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->isSecure()) {
                $hoererchartsModel = $hoererchartssuggestionMapper->getEntryById($this->getRequest()->getParam('id'));
                $hoererchartssuggestionMapper->delete($this->getRequest()->getParam('id'));
                $hoererchartsModel->setId(null);
                $hoererchartsModel->setSetFree(0);
                $hoererchartsMapper->save($hoererchartsModel);

                $this->addMessage('updateSuccess');
            }
        }
        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartsuservotesMapper = new HoererChartsUserVotesMapper();

        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->isSecure()) {
                $hoererchartsMapper->reset();
                $hoererchartsuservotesMapper->reset();

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }
        }

        $this->getView()->set('entries', $hoererchartsuservotesMapper->getEntries([]));
    }
}
