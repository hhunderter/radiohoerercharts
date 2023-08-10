<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Modules\RadioHoererCharts\Mappers\HoererCharts as EntriesMapper;
use Modules\RadioHoererCharts\Models\HoererChartsList as EntriesModel;

class HoererChartsList extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'radio_hoerercharts_list';

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
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
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

        $entriesMapper = new EntriesMapper();

        foreach ($entriesArray as $entryArray) {
            $hoererChartsModel = $entriesMapper->getEntryById($entryArray['hid']);
            if ($hoererChartsModel && $hoererChartsModel->getSetFree()) {
                $entryModel = new EntriesModel();
                $entryModel->setByArray($entryArray)
                    ->setEntry($hoererChartsModel);

                $entries[] = $entryModel;
            } else {
                $this->db()->delete($this->tablename)
                    ->where(['id' => $entryArray['id']])
                    ->execute();
            }
        }
        return $entries;
    }

    /**
     * Gets the Entries.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]|null
     */
    public function getEntries(array $where = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getEntriesBy($where, ['list' => 'DESC', 'id' => 'DESC'], $pagination);
    }

    /**
     * Gets the entry by given List ID.
     *
     * @param int $id
     * @return EntriesModel[]|null
     */
    public function getEntryByList(int $id): ?array
    {
        $entries = $this->getEntriesBy(['list' => $id], []);

        if (!empty($entries)) {
            return $entries;
        }

        return null;
    }

    /**
     * Add H ID to a List.
     *
     * @param int $hID
     * @param int $listId
     * @return bool|int
     */
    public function addEntryToList(int $hID, int $listId)
    {
        if (!$this->getEntriesBy(['hid' => $hID, 'list' => $listId])) {
            return $this->db()->insert($this->tablename)
                ->values(['hid' => $hID, 'list' => $listId])
                ->execute();
        }
        return false;
    }

    /**
     * Delete H ID to a List.
     *
     * @param int $hID
     * @param int $listId
     * @return bool|int
     */
    public function deleteEntryToList(int $hID, int $listId)
    {
        if ($this->getEntriesBy(['hid' => $hID, 'list' => $listId])) {
            return $this->db()->delete($this->tablename)
                ->where(['hid' => $hID, 'list' => $listId])
                ->execute();
        }
        return false;
    }
}
