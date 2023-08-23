<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Ilch\Date;
use Ilch\Pagination;
use Modules\RadioHoererCharts\Models\HoererCharts as EntriesModel;

class HoererCharts extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'radio_hoerercharts';
    /**
     * @var string
     */
    public $tablenameList = 'radio_hoerercharts_list';

    /**
     * returns if the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return EntriesModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
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

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entry) {
            $entryModel = new EntriesModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Entries.
     *
     * @param array $where
     * @param Pagination|null $pagination
     * @return EntriesModel[]|null
     */
    public function getEntries(array $where = [], ?Pagination $pagination = null): ?array
    {
        return $this->getEntriesBy($where, ['setfree' => 'DESC', 'id' => 'DESC'], $pagination);
    }

    /**
     * Gets the entry by given ID.
     *
     * @param int $id
     * @return null|EntriesModel
     */
    public function getEntryById(int $id): ?EntriesModel
    {
        $entries = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets the entry by given ID.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return null|EntriesModel[]
     */
    public function getEntryByList(array $where = [], array $orderBy = ['h.id' => 'DESC'], ?Pagination $pagination = null): ?array
    {
        $select = $this->db()->select()
            ->fields(['h.id', 'h.setfree', 'h.interpret', 'h.songtitel', 'h.votes', 'h.datecreate', 'h.user_id', 'h.artworkUrl'])
            ->from(['h' => $this->tablename])
            ->join(['l' => $this->tablenameList], 'h.id = l.hid', 'LEFT', ['l.list'])
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

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entryArray) {
            $entryModel = new EntriesModel();
            $entryModel->setByArray($entryArray);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return int
     */
    public function save(EntriesModel $model): int
    {
        $fields = $model->getArray(false);
        if ((0 === substr_compare($this->tablename, 'suggestion', - 10))) {
            unset($fields['setfree'], $fields['votes'], $fields['artworkUrl']);
        }

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * Updates Entrie setfree with given id.
     *
     * @param int $id
     * @param int $setFreeMan
     * @return bool
     */
    public function updateSetFree(int $id, int $setFreeMan): bool
    {
        if ($setFreeMan != -1) {
            $setFreeNow = $setFreeMan;
        } else {
            $setFree = (int) $this->db()->select('setfree')
                                ->from($this->tablename)
                                ->where(['id' => $id])
                                ->execute()
                                ->fetchCell();

            if ($setFree == 1) {
                $setFreeNow = 0;
            } else {
                $setFreeNow = 1;
            }
        }
        return $this->db()->update($this->tablename)
            ->values(['setfree' => $setFreeNow])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates Entrie with given id.
     *
     * @param int $id
     * @param int $votes_man
     * @return bool
     */
    public function update(int $id, int $votes_man): bool
    {
        if ($votes_man != -1) {
            $votes_now = $votes_man;
        } else {
            $votes = (int) $this->db()->select('votes')
                            ->from($this->tablename)
                            ->where(['id' => $id])
                            ->execute()
                            ->fetchCell();

            $votes_now = $votes + 1;
        }

        return $this->db()->update($this->tablename)
            ->values(['votes' => $votes_now])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Gets the hidden copyright text.
     *
     * @return String
     */
    public function gettext(): string
    {
        $configClass = '\\Modules\\' . ucfirst('RadioHoererCharts') . '\\Config\\Config';
        $config = new $configClass();
        return " -> &copy; by Dennis Reilard alias hhunderter (Version: " . $config->config['version'] . ")";
    }

    /**
     * Deletes the entry.
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Reset the Vote counts.
     *
     * @return bool
     */
    public function reset(): bool
    {
        return $this->db()->update($this->tablename)
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
            } else {
                $starcount = 0;
            }

            $stars = '';
            for ($i = 1; $i <= $starcount; $i++) {
                $stars .= '<i class="fa-solid fa-star text-warning"></i>';
            }
            for ($i = 1; $i <= 5 - $starcount; $i++) {
                $stars .= '<i class="fa-regular fa-star text-secondary"></i>';
            }
        } else {
            $stars = $votes;
        }
        return $stars;
    }

    /**
     * Checks if voting is allowed.
     *
     * @param Date|null $startDatetime
     * @param Date|null $endDatetime
     * @return bool
     */
    public function voteAllowed(?Date $startDatetime = null, ?Date $endDatetime = null): bool
    {
        $date = new Date();
        $datenow = new Date($date->format("Y-m-d H:i:s", true));

        if (!$startDatetime && !$endDatetime) {
            return true;
        } else {
            if ($startDatetime) {
                if (!$endDatetime) {
                    return ($datenow->getTimestamp() >= $startDatetime->getTimestamp());
                }
            }
            if ($endDatetime) {
                if (!$startDatetime) {
                    return ($datenow->getTimestamp() <= $endDatetime->getTimestamp());
                }
            }
            return ($datenow->getTimestamp() >= $startDatetime->getTimestamp() && $datenow->getTimestamp() <= $endDatetime->getTimestamp());
        }
    }

    /**
     * Checks if Time to show the Final list.
     *
     * @param Date|null $end_datetime
     * @return bool
     */
    public function isShowSortedList(?Date $end_datetime = null): bool
    {
        $config = \Ilch\Registry::get('config');
        $program_secduration = $config->get('radio_hoerercharts_Program_sec_duration');

        $date = new Date();
        $datenow = new Date($date->format("Y-m-d H:i:s", true));

        if (!$end_datetime) {
            return true;
        } else {
            return ($datenow->getTimestamp() >= ($end_datetime->getTimestamp() + $program_secduration));
        }
    }

    /**
     * Get the Votetext
     *
     * @return String
     */
    public function getVoteText(): string
    {
        $config = \Ilch\Registry::get('config');
        $translator = \Ilch\Registry::get('translator');
        if ($translator->shortenLocale($translator->getLocale()) == 'de') {
            $votetext = $config->get('radio_hoerercharts_votetext_de') ?? $translator->trans('votetext');
        } else {
            $votetext = $config->get('radio_hoerercharts_votetext_en') ?? $translator->trans('votetext');
        }
        $radio_hoerercharts_Program_Name = $config->get('radio_hoerercharts_Program_Name');

        $Program_Name = ($radio_hoerercharts_Program_Name ?: $translator->trans('hoerercharts'));
        return str_replace(['--user--', '--name--'], [((!$config->get('guestallow')) ? $translator->trans('votetextuser') : $translator->trans('votetextguest')), $Program_Name], $votetext);
    }
}
