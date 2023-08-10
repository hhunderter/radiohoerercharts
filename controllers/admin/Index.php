<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Controllers\Admin;

use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Modules\RadioHoererCharts\Models\HoererCharts as HoererChartsModel;
use Modules\RadioHoererCharts\Mappers\HoererChartsSuggestion as HoererChartsSuggestionMapper;
use Modules\RadioHoererCharts\Mappers\HoererChartsUserVotes as HoererChartsUserVotesMapper;
use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as HoererChartsUserVotesModel;
use Modules\RadioHoererCharts\Mappers\HoererChartsList as HoererChartsListMapper;
use Modules\RadioHoererCharts\Models\HoererChartsList as HoererChartsListModel;

use Ilch\Validation;
use Modules\RadioHoererCharts\Libs\SearchiTunes;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa-solid fa-trash-can',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() != 'reset') {
            $items[0][0] = [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
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

        $this->getLayout()->addMenu(
            'hoerercharts',
            $items
        );
    }

    public function indexAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        $hoererchartsuservotesMapper = new HoererChartsUserVotesMapper();
        $hoererchartslistMapper = new HoererChartsListMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $this->getView()->set('hoererchartsMapper', $hoererchartsMapper);
        $this->getView()->set('hoererchartslistMapper', $hoererchartslistMapper);

        $start_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) {
            $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
        }
        $end_datetime = null;
        if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) {
            $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
        }

        $this->getView()->set('allowsuggestion', $this->getConfig()->get('radio_hoerercharts_allow_suggestion'));
        $this->getView()->set('suggestion', $this->getRequest()->getParam('suggestion'));
        
        $activelist = $this->getConfig()->get('radio_hoerercharts_active_list');
        $this->getView()->set('activelist', $activelist);
        $this->getView()->set('list', $this->getRequest()->getParam('list'));
        
        $this->getView()->set('filterlist', $this->getRequest()->getParam('filterlist'));
        
        if ($this->getRequest()->getParam('list')) {
            $listentries = $hoererchartslistMapper->getEntryByList($this->getRequest()->getParam('list')) ?? [];
        }

        $this->getView()->set('votedatetime', ((!$start_datetime && !$end_datetime)?$this->getTranslator()->trans('noentriesadmin'):(($start_datetime && $end_datetime)?call_user_func_array([$this->getTranslator(), 'trans'], ['fromto', $start_datetime->format($this->getTranslator()->trans('datetimeformat')),$end_datetime->format($this->getTranslator()->trans('datetimeformat'))]):(($start_datetime)?$this->getTranslator()->trans('from').' '.$start_datetime->format($this->getTranslator()->trans('datetimeformat')):$this->getTranslator()->trans('to').' '.$end_datetime->format($this->getTranslator()->trans('datetimeformat'))))."<br><br>"));
        if ($hoererchartsMapper->checkDB() && $hoererchartssuggestionMapper->checkDB() && $hoererchartsuservotesMapper->checkDB()) {
            if ($this->getRequest()->getPost('check_entries')) {
                if ($this->getRequest()->getPost('action') == 'delete') {
                    foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                        if ($this->getRequest()->getParam('suggestion')) {
                            $hoererchartssuggestionMapper->delete($entryId);
                        } else {
                            $hoererchartsMapper->delete($entryId);
                        }
                    }
                    $this->addMessage('deleteSuccess');
                    $this->redirect(['action' => 'index']);
                } elseif ($this->getRequest()->getPost('action') == 'setfree') {
                    foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                        $hoererchartsModel = $hoererchartssuggestionMapper->getEntryById($entryId);
                        $hoererchartsModel->setId(0);
                        $hoererchartsModel->setSetFree(0);
                        $hoererchartsMapper->save($hoererchartsModel);
                        $hoererchartssuggestionMapper->delete($entryId);
                    }

                    $this->addMessage('updateSuccess');
                    $this->redirect(['action' => 'index']);
                }
            } elseif ($this->getRequest()->getPost('hiddenMenu') && $this->getRequest()->getParam('list')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                foreach ($listentries as $key => $listmodel) {
                    if (!in_array($listmodel->getHId(), $sortItems)) {
                        $hoererchartslistMapper->deleteEntryToList($listmodel->getHId(), $this->getRequest()->getParam('list'));
                        unset($listentries[$key]);
                    } else {
                        $keylist = array_search($listmodel->getHId(), $sortItems);
                        unset($sortItems[$keylist]);
                    }
                }
                foreach($sortItems as $key => $hid) {
                    $id = $hoererchartslistMapper->addEntryToList($hid, $this->getRequest()->getParam('list'));
                    $hoererchartslistModel = new HoererChartsListModel();
                    $hoererchartslistModel->setId($id)
                        ->setHId($hid)
                        ->setList($this->getRequest()->getParam('list'));
                    $listentries[] = $hoererchartslistModel;
                }
                $this->addMessage('updateSuccess');
                
                $this->redirect(['action' => 'index', 'list' => $this->getRequest()->getParam('list')]);
            }

            $columns = ['datecreate', 'user_id', 'songtitel', 'interpret'];
            if (!$this->getRequest()->getParam('suggestion')) {
                $columns[] = 'votes';
            }
            $column = $this->getRequest()->getParam('column') && in_array($this->getRequest()->getParam('column'), $columns) ? $this->getRequest()->getParam('column') : $columns[0];
            $sort_order = $this->getRequest()->getParam('order') && strtolower($this->getRequest()->getParam('order')) == 'asc' ? 'ASC' : 'DESC';
            
            if ($column === 'votes') {
                $suggestionentries = $hoererchartssuggestionMapper->getEntriesby([], [$columns[0] => $sort_order]);
            } else {
                $suggestionentries = $hoererchartssuggestionMapper->getEntriesby([], [$column => $sort_order]);
            }

            if ($this->getRequest()->getParam('list')) {
                $this->getView()->set('listentries', $listentries);
                $chartsentries = $hoererchartsMapper->getEntriesby(['setfree' => 1]) ?? [];
            } else {
                $chartsentries = $hoererchartsMapper->getEntriesby([], [$column => $sort_order]);
                if ($this->getRequest()->getParam('filterlist')) {
                    $chartsentries = $hoererchartsMapper->getEntryByList(['l.list' => $this->getRequest()->getParam('filterlist')], ['h.'.$column => $sort_order]);
                } else {
                    $chartsentries = $hoererchartsMapper->getEntriesby([], [$column => $sort_order]);
                }
            }

            if ($this->getRequest()->getParam('suggestion')) {
                $this->getView()->set('entries', $suggestionentries);
            } else {
                $this->getView()->set('entries', $chartsentries);
            }

            $this->getView()->set('badgeSuggestion', $suggestionentries?count($suggestionentries):0)
                            ->set('sort_column', $column)
                            ->set('sort_order', $sort_order);
        } else {
            $this->addMessage('dbfail');
        }
    }

    public function updateAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        if ($hoererchartsMapper->checkDB() && $this->getRequest()->isSecure()) {
            $hoererchartsMapper->update_setfree($this->getRequest()->getParam('id'), $this->getRequest()->getParam('status_man'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function treatAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        $hoererchartsModel = new HoererChartsModel();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($hoererchartsMapper->checkDB() && $hoererchartssuggestionMapper->checkDB()) {
            if ($this->getRequest()->getParam('id')) {
                $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

                if ($this->getRequest()->getParam('suggestion')) {
                    $hoererchartsModel = $hoererchartssuggestionMapper->getEntryById($this->getRequest()->getParam('id'));
                    $this->getView()->set('entrie', $hoererchartsModel);
                } else {
                    $hoererchartsModel = $hoererchartsMapper->getEntryById($this->getRequest()->getParam('id'));
                    $this->getView()->set('entrie', $hoererchartsModel);
                }
            } else {
                $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
            }

            if ($this->getRequest()->isPost()) {
                $validation = Validation::create($this->getRequest()->getPost(), array_merge([
                    'interpret' => 'required',
                    'songtitel'    => 'required'
                ], (($this->getRequest()->getParam('suggestion'))?[]:['setfree' => 'required|numeric|min:0|max:1'])));

                if ($validation->isValid()) {
                    $date = new \Ilch\Date();
                    $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));
                    
                    if ($this->getRequest()->getParam('id')) {
                        $hoererchartsModel->setId($this->getRequest()->getParam('id'));
                    } else {
                        if ($this->getUser()) {
                            $hoererchartsModel->setUser_Id($this->getUser()->getId());
                        }
                        $hoererchartsModel->setDateCreate($datenow);
                    }

                    if (!$this->getRequest()->getParam('suggestion')) {
                        $hoererchartsModel->setSetFree($this->getRequest()->getPost('setfree'));
                        if ($this->getConfig()->get('radio_hoerercharts_show_artwork')) $hoererchartsModel->setArtworkUrl($this->getRequest()->getPost('artworkUrl'));
                    }
                    if ($hoererchartsModel->getId() && ($hoererchartsModel->getInterpret() != $this->getRequest()->getPost('interpret') || $hoererchartsModel->getSongTitel() != $this->getRequest()->getPost('songtitel'))) {
                        $hoererchartsModel->setVotes(0);
                    }
                    
                    $hoererchartsModel->setInterpret($this->getRequest()->getPost('interpret'))
                        ->setSongTitel($this->getRequest()->getPost('songtitel'));

                    if ($this->getRequest()->getParam('suggestion')) {
                        $hoererchartssuggestionMapper->save($hoererchartsModel);
                    } else {
                        $hoererchartsMapper->save($hoererchartsModel);
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(array_merge(['action' => 'index'], (($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
                }
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')], (($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
            }
        } else {
            $this->redirect(array_merge(['action' => 'index'], (($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
        }
        $this->getView()->set('show_artwork', $this->getConfig()->get('radio_hoerercharts_show_artwork'));
    }
    
    public function searchiTunesAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/ajax');

        if ($this->getRequest()->isSecure()) {
            $searchitunes = new SearchiTunes;
            $searchitunes->setTerm($this->getRequest()->getParam('term'))
                                ->setCountry('DE')
                                ->setMedia('music')
                                //->setEntity('mix')
                                //->setAttribute('mixTerm')
                                ->setLimit(1)
                                ->setLang('de_de')
                                ->setExplicit(false)
                                ->search();

            $this->getView()->set('entrie', ['URL' => $searchitunes->getResult('artworkUrl100', 1)]);
        }
    }

    public function delAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        if ($hoererchartsMapper->checkDB() && $hoererchartssuggestionMapper->checkDB()) {
            if ($this->getRequest()->isSecure()) {
                if ($this->getRequest()->getParam('suggestion')) {
                    $hoererchartssuggestionMapper->delete($this->getRequest()->getParam('id'));
                } else {
                    $hoererchartsMapper->delete($this->getRequest()->getParam('id'));
                }

                $this->addMessage('deleteSuccess');
            }
        }
        $this->redirect(array_merge(['action' => 'index'], (($this->getRequest()->getParam('suggestion'))?['suggestion' => 'true']:[])));
    }

    public function allowsuggestionAction()
    {
        $allowsuggestion = $this->getConfig()->get('radio_hoerercharts_allow_suggestion');
        $this->getConfig()->set('radio_hoerercharts_allow_suggestion', !$allowsuggestion);

        $this->redirect()
            ->withMessage('updateSuccess')
            ->to(['action' => 'index', 'suggestion' => 'true']);
    }
    
    public function activelistAction()
    {
        $this->getConfig()->set('radio_hoerercharts_active_list', $this->getRequest()->getParam('list'));

        $this->redirect()
            ->withMessage('updateSuccess')
            ->to(['action' => 'index', 'list' => $this->getRequest()->getParam('list')]);
    }
    
    public function suggestionenableAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartssuggestionMapper = new HoererChartsSuggestionMapper();
        if ($hoererchartsMapper->checkDB() && $hoererchartssuggestionMapper->checkDB()) {
            if ($this->getRequest()->isSecure()) {
                $hoererchartsModel = $hoererchartssuggestionMapper->getEntryById($this->getRequest()->getParam('id'));
                $hoererchartsModel->setId(0);
                $hoererchartsModel->setSetFree(0);
                $hoererchartsMapper->save($hoererchartsModel);
                $hoererchartssuggestionMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('updateSuccess');
            }
        }
        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hoerercharts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('reset'), ['action' => 'reset']);

        $hoererchartsMapper = new HoererChartsMapper();
        $hoererchartsuservotesMapper = new HoererChartsUserVotesMapper();

        if ($hoererchartsMapper->checkDB() && $hoererchartsuservotesMapper->checkDB()) {
            if ($this->getRequest()->isSecure()) {
                $hoererchartsMapper->reset();
                $hoererchartsuservotesMapper->reset();

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }
        }

        $this->getView()->set('entries', $hoererchartsuservotesMapper->getEntries([]) ?? []);
    }
}
