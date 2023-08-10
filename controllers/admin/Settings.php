<?php

/**
 * @copyright Dennis Reilard alias hhunderter
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
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
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

        $this->getView()->set('settings_language', $this->getRequest()->getParam('settings_language'));

        $radio_hoerercharts_Program_Name = $this->getConfig()->get('radio_hoerercharts_Program_Name');

        $Program_Name = ($radio_hoerercharts_Program_Name ?: $this->getTranslator()->trans('hoerercharts'));

        $this->getView()->set('Program_Name', $Program_Name);

        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->isPost()) {
                if (!$this->getRequest()->getParam('settings_language')) {
                    $validation = Validation::create($this->getRequest()->getPost(), [
                        'allsecvote' => 'required|numeric',
                        'program_secduration' => 'required|numeric',
                        'guestallow' => 'required|numeric|min:0|max:1',
                        'showstars' => 'required|numeric|min:0|max:1',
                        'Star1' => 'required|numeric|min:1',
                        'Star2' => 'required|numeric|min:1',
                        'Star3' => 'required|numeric|min:1',
                        'Star4' => 'required|numeric|min:1',
                        'Star5' => 'required|numeric|min:1',
                        'show_artwork' => 'required|numeric|min:0|max:1',
                        'show_registered_by' => 'required|numeric|min:0|max:1',
                    ]);
                } else {
                    $validation = Validation::create($this->getRequest()->getPost(), [
                        'votetext_de' => 'required',
                        'votetext_en' => 'required',
                    ]);
                }

                if ($validation->isValid()) {
                    if (!$this->getRequest()->getParam('settings_language')) {
                        $this->getConfig()->set('radio_hoerercharts_Program_Name', $this->getRequest()->getPost('Program_Name'))
                            ->set('radio_hoerercharts_all_sec_vote', $this->getRequest()->getPost('allsecvote'))
                            ->set('radio_hoerercharts_Program_sec_duration', $this->getRequest()->getPost('program_secduration'))
                            ->set('radio_hoerercharts_Guest_Allow', $this->getRequest()->getPost('guestallow'))
                            ->set('radio_hoerercharts_showstars', $this->getRequest()->getPost('showstars'))
                            ->set('radio_hoerercharts_Star1', $this->getRequest()->getPost('Star1'))
                            ->set('radio_hoerercharts_Star2', $this->getRequest()->getPost('Star2'))
                            ->set('radio_hoerercharts_Star3', $this->getRequest()->getPost('Star3'))
                            ->set('radio_hoerercharts_Star4', $this->getRequest()->getPost('Star4'))
                            ->set('radio_hoerercharts_Star5', $this->getRequest()->getPost('Star5'))
                            ->set('radio_hoerercharts_show_artwork', $this->getRequest()->getPost('show_artwork'))
                            ->set('radio_hoerercharts_show_registered_by', $this->getRequest()->getPost('show_registered_by'));
                    } else {
                        $this->getConfig()->set('radio_hoerercharts_votetext_de', $this->getRequest()->getPost('votetext_de'))
                            ->set('radio_hoerercharts_votetext_en', $this->getRequest()->getPost('votetext_en'));
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(array_merge(['action' => 'index'], ($this->getRequest()->getParam('settings_language') ? ['settings_language' => 'true'] : [])));
                }

                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'index'], ($this->getRequest()->getParam('settings_language') ? ['settings_language' => 'true'] : [])));
            }

            if (!$this->getRequest()->getParam('settings_language')) {
                $this->getView()->set('Program_Name', $this->getConfig()->get('radio_hoerercharts_Program_Name'))
                    ->set('allsecvote', $this->getConfig()->get('radio_hoerercharts_all_sec_vote'))
                    ->set('program_secduration', $this->getConfig()->get('radio_hoerercharts_Program_sec_duration'))
                    ->set('guestallow', $this->getConfig()->get('radio_hoerercharts_Guest_Allow'))
                    ->set('showstars', $this->getConfig()->get('radio_hoerercharts_showstars'))
                    ->set('Star1', $this->getConfig()->get('radio_hoerercharts_Star1'))
                    ->set('Star2', $this->getConfig()->get('radio_hoerercharts_Star2'))
                    ->set('Star3', $this->getConfig()->get('radio_hoerercharts_Star3'))
                    ->set('Star4', $this->getConfig()->get('radio_hoerercharts_Star4'))
                    ->set('Star5', $this->getConfig()->get('radio_hoerercharts_Star5'))
                    ->set('show_artwork', $this->getConfig()->get('radio_hoerercharts_show_artwork'))
                    ->set('show_registered_by', $this->getConfig()->get('radio_hoerercharts_show_registered_by'));
            } else {
                if ($this->getTranslator()->shortenLocale($this->getTranslator()->getLocale()) == 'de') {
                    $language = new \Ilch\Translator('en_EN');
                } else {
                    $language = new \Ilch\Translator('de_DE');
                }

                $language->load(APPLICATION_PATH . '/modules/radiohoerercharts/translations');
                $language_votetext = $language->trans('votetext');

                if ($this->getTranslator()->shortenLocale($this->getTranslator()->getLocale()) == 'de') {
                    $language_de = $this->getTranslator()->trans('votetext');
                    $language_en = $language_votetext;
                } else {
                    $language_de = $language_votetext;
                    $language_en = $this->getTranslator()->trans('votetext');
                }

                $votetext_de = $this->getConfig()->get('radio_hoerercharts_votetext_de') ?? $language_de;
                $votetext_en = $this->getConfig()->get('radio_hoerercharts_votetext_en') ?? $language_en;

                $this->getView()->set('votetext_de', $votetext_de)
                    ->set('votetext_en', $votetext_en);
            }
        }
    }

    public function votedatetimeAction()
    {
        $hoererchartsMapper = new HoererChartsMapper();

        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hoerercharts'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'votedatetime']);

        if ($hoererchartsMapper->checkDB()) {
            if ($this->getRequest()->isPost()) {
                $input = [];
                $rules = [];

                if (trim($this->getRequest()->getPost('start_datetime'))) {
                    $start_datetime = $this->getRequest()->getPost('start_datetime');
                    $input['start_datetime'] = $start_datetime;
                    $rules['start_datetime'] = 'required|date:d.m.Y H\\:i';
                } else {
                    $start_datetime = '';
                }
                if (trim($this->getRequest()->getPost('end_datetime'))) {
                    $end_datetime = $this->getRequest()->getPost('end_datetime');
                    $input['end_datetime'] = $end_datetime;
                    $rules['end_datetime'] = 'required|date:d.m.Y H\\:i';
                } else {
                    $end_datetime = '';
                }

                $validation = Validation::create($input, $rules);

                if ($validation->isValid()) {
                    $this->getConfig()->set('radio_hoerercharts_Start_Datetime', $start_datetime)
                        ->set('radio_hoerercharts_End_Datetime', $end_datetime);

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['action' => 'votedatetime']);
                }

                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'votedatetime']);
            }

            $start_datetime = null;
            if ($this->getConfig()->get('radio_hoerercharts_Start_Datetime')) {
                $start_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_Start_Datetime'));
            }
            $end_datetime = null;
            if ($this->getConfig()->get('radio_hoerercharts_End_Datetime')) {
                $end_datetime = new \Ilch\Date($this->getConfig()->get('radio_hoerercharts_End_Datetime'));
            }

            $this->getView()->set('start_datetime', (($start_datetime) ? $start_datetime->format($this->getTranslator()->trans('datetimeformat')) : ''))
                ->set('end_datetime', (($end_datetime) ? $end_datetime->format($this->getTranslator()->trans('datetimeformat')) : ''));
        }
    }
}
