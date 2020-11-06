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
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     */
    public function checkDB()
    {
        return $this->db()->ifTableExists('[prefix]_radio_hoerercharts_list');
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
            ->from('radio_hoerercharts_list')
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
        
        $entriesMapper = new EntriesMapper();

        foreach ($entryArray as $entries) {
            $entriesmapperModel = $entriesMapper->getEntryById($entries['hid']);
            if ($entriesmapperModel && $entriesmapperModel->getSetFree() === 1) {
                $entryModel = new EntriesModel();
                $entryModel->setId($entries['id'])
                    ->setHId($entries['hid'])
                    ->setEntry($entriesMapper->getEntryById($entries['hid']))
                    ->setList($entries['list']);
                $entrys[] = $entryModel;
            } else {
                $this->db()->delete('radio_hoerercharts_list')
                ->where(['id' => $entries['id']])
                ->execute();
            }
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
        return $this->getEntriesBy($where, ['list' => 'DESC', 'id' => 'DESC'], $pagination);
    }
    
    /**
     * Gets the entry by given List ID.
     *
     * @param int $id
     * @return null|EntriesModel
     */
    public function getEntryByList(int $id)
    {
        $entrys = $this->getEntriesBy(['list' => (int) $id], []);

        if (!empty($entrys)) {
            return $entrys;
        }
        
        return null;
    }

    /**
     * Add H ID to a List.
     *
     * @param int $hId
     * @param int $listId
     */
    public function addEntryToList(int $hID, int $listId)
    {
        if (!$this->getEntriesBy(['hid' => (int) $hID, 'list' => (int) $listId])) {
            return $this->db()->insert('radio_hoerercharts_list')
                ->values(['hid' => (int) $hID, 'list' => (int) $listId])
                ->execute();
        }
        return false;
    }

    /**
     * Delete H ID to a List.
     *
     * @param int $hId
     * @param int $listId
     */
    public function deleteEntryToList(int $hID, int $listId)
    {
        if ($this->getEntriesBy(['hid' => (int) $hID, 'list' => (int) $listId])) {
            return $this->db()->delete('radio_hoerercharts_list')
                ->where(['hid' => (int) $hID, 'list' => (int) $listId])
                ->execute();
        }
        return false;
    }

}
