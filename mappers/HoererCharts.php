<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Modules\RadioHoererCharts\Models\HoererCharts as EntriesModel;

class HoererCharts extends \Ilch\Mapper
{
    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     */
    public function checkDB()
    {
        return $this->db()->ifTableExists('[prefix]_radio_hoerercharts');
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('radio_hoerercharts')
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id'])
                ->setSetFree($entries['setfree'])
                ->setInterpret($entries['interpret'])
                ->setSongTitel($entries['songtitel'])
                ->setVotes($entries['votes'])
                ->setDateCreate($entries['datecreate'])
                ->setUser_Id($entries['user_id'])
                ->setArtworkUrl($entries['artworkUrl']);
            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Entries.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntries($where = [], $pagination = null)
    {
        return $this->getEntriesBy($where, ['setfree' => 'DESC', 'id' => 'DESC'], $pagination);
    }

    /**
     * Gets the entry by given ID.
     *
     * @param int $id
     * @return null|EntriesModel
     */
    public function getEntryById(int $id)
    {
        $entrys = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }
        
        return null;
    }
    
    /**
     * Gets the entry by given ID.
     *
     * @param array $where
     * @param array $orderBy_
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getEntryByList($where = [], $orderBy = ['h.id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['h.id', 'h.setfree', 'h.interpret', 'h.songtitel', 'h.votes', 'h.datecreate', 'h.user_id', 'h.artworkUrl'])
            ->from(['h' => 'radio_hoerercharts'])
            ->join(['l' => 'radio_hoerercharts_list'], 'h.id = l.hid', 'LEFT', ['l.list'])
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id'])
                ->setSetFree($entries['setfree'])
                ->setInterpret($entries['interpret'])
                ->setSongTitel($entries['songtitel'])
                ->setVotes($entries['votes'])
                ->setDateCreate($entries['datecreate'])
                ->setUser_Id($entries['user_id'])
                ->setArtworkUrl($entries['artworkUrl']);
            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return boolean|integer
     */
    public function save(EntriesModel $model)
    {
        $fields = [
            'setfree'       => $model->getSetFree(),
            'interpret'     => $model->getInterpret(),
            'songtitel'     => $model->getSongTitel(),
            'votes'         => $model->getVotes(),
            'datecreate'    => $model->getDateCreate(),
            'user_id'       => $model->getUser_Id(),
            'artworkUrl'    => $model->getArtworkUrl(),
        ];

        if ($model->getId()) {
            $result = $this->db()->update('radio_hoerercharts')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $result = $this->db()->insert('radio_hoerercharts')
                ->values($fields)
                ->execute();
        }

        return $result;
    }
    
    /**
     * Updates Entrie setfree with given id.
     *
     * @param int $id
     * @param int $setfree_man
     * @return boolean
     */
    public function update_setfree(int $id, int $setfree_man)
    {
        if ($setfree_man != -1) {
            $setfree_now = (int)$setfree_man;
        } else {
            $setfree = (int) $this->db()->select('setfree')
                                ->from('radio_hoerercharts')
                                ->where(['id' => (int)$id])
                                ->execute()
                                ->fetchCell();

            if ($setfree == 1) {
                $setfree_now = 0;
            } else {
                $setfree_now = 1;
            }
        }
        $result = $this->db()->update('radio_hoerercharts')
            ->values(['setfree' => $setfree_now])
            ->where(['id' => (int)$id])
            ->execute();

        return $result;
    }

    /**
     * Updates Entrie with given id.
     *
     * @param int $id
     * @param int $votes_man
     * @return boolean
     */
    public function update(int $id, int $votes_man)
    {
        if ($votes_man != -1) {
            $votes_now = (int)$votes_man;
        } else {
            $votes = (int) $this->db()->select('votes')
                            ->from('radio_hoerercharts')
                            ->where(['id' => (int)$id])
                            ->execute()
                            ->fetchCell();

            $votes_now = $votes + 1;
        }

        $result = $this->db()->update('radio_hoerercharts')
            ->values(['votes' => $votes_now])
            ->where(['id' => (int)$id])
            ->execute();

        return $result;
    }

    /**
     * Gets the hidden copyright text.
     *
     * @return String
     */
    public function gettext()
    {
        $configClass = '\\Modules\\'.ucfirst('RadioHoererCharts').'\\Config\\Config';
        $config = new $configClass();
        return " -> &copy; by Dennis Reilard alias hhunderter (Version: ".$config->config['version'].")";
    }

    /**
     * Deletes the entry.
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id)
    {
        return $this->db()->delete('radio_hoerercharts')
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Reset the Vote counts.
     *
     * @return boolean
     */
    public function reset()
    {
        return $this->db()->update('radio_hoerercharts')
            ->values(['votes' => 0])
            ->execute();
    }

    /**
     * Get the Vote counts.
     *
     * @param integer $votes
     * @param boolean $showstars
     * @return string
     */
    public function getStars(int $votes = 0, bool $showstars = false)
    {
        $config = \Ilch\Registry::get('config');

        if ($config->get('radio_hoerercharts_showstars') || $showstars) {
            if (empty($votes)) {
                $starcount = 0;
            } elseif ($votes >= $config->get('radio_hoerercharts_Star5')) {
                $starcount = 5;
            } elseif ($votes >= $config->get('radio_hoerercharts_Star4')) {
                $starcount = 4;
            } elseif ($votes >= $config->get('radio_hoerercharts_Star3')) {
                $starcount = 3;
            } elseif ($votes >= $config->get('radio_hoerercharts_Star2')) {
                $starcount = 2;
            } elseif ($votes >= $config->get('radio_hoerercharts_Star1')) {
                $starcount = 1;
            } elseif ($votes < $config->get('radio_hoerercharts_Star1')) {
                $starcount = 0;
            }

            $stars = '';
            for ($i = 1; $i <= $starcount; $i++) $stars .= '<i class="fa-solid fa-star text-warning"></i>';
            for ($i = 1; $i <= 5-$starcount; $i++) $stars .= '<i class="fa-regular fa-star text-secondary"></i>';
        } else {
            $stars = $votes;
        }
        return $stars;
    }

    /**
     * Checks if voting is allowed.
     *
     * @param null|\Ilch\Date $start_datetime
     * @param null|\Ilch\Date $end_datetime
     * @return boolean
     */
    public function vote_allowed($start_datetime = null, $end_datetime = null)
    {
        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));

        if (!$start_datetime && !$end_datetime) {
            return true;
        } else {
            if ($start_datetime) {
                if (!$end_datetime) {
                    return ($datenow->getTimestamp() >= $start_datetime->getTimestamp());
                }
            }
            if ($end_datetime) {
                if (!$start_datetime) {
                    return ($datenow->getTimestamp() <= $end_datetime->getTimestamp());
                }
            }
            return ($datenow->getTimestamp() >= $start_datetime->getTimestamp() && $datenow->getTimestamp() <= $end_datetime->getTimestamp());
        }
    }

    /**
     * Checks if Time to show the Final list.
     *
     * @param null|\Ilch\Date $end_datetime
     * @return boolean
     */
    public function is_showsortedlist($end_datetime = null)
    {
        $config = \Ilch\Registry::get('config');
        $program_secduration = $config->get('radio_hoerercharts_Program_sec_duration');

        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));

        if (!$end_datetime) {
            return true;
        } else {
            return ($datenow->getTimestamp() >= ($end_datetime->getTimestamp()+$program_secduration));
        }
    }

    /**
     * Get the Votetext
     *
     * @return String
     */
    public function getvotetext()
    {
        $config = \Ilch\Registry::get('config');
        $translator = \Ilch\Registry::get('translator');
        if ($translator->shortenLocale($translator->getLocale()) == 'de') {
            $votetext = $config->get('radio_hoerercharts_votetext_de') ?? $translator->trans('votetext');
        } else {
            $votetext = $config->get('radio_hoerercharts_votetext_en') ?? $translator->trans('votetext');
        }
        $radio_hoerercharts_Program_Name = $config->get('radio_hoerercharts_Program_Name');

        $Program_Name = ($radio_hoerercharts_Program_Name ? $radio_hoerercharts_Program_Name : $translator->trans('hoerercharts'));
        return str_replace(['--user--', '--name--'], [((!$config->get('guestallow'))?$translator->trans('votetextuser'):$translator->trans('votetextguest')), $Program_Name], $votetext);
    }
}
