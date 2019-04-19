<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Modules\RadioHoererCharts\Models\HoererCharts as EntriesModel;

class HoererChartsSuggestion extends \Ilch\Mapper
{
    /**
     * returns if the module is installed.
     *
     * @return boolean
     */
    public function checkDB()
    {
        return $this->db()->ifTableExists('[prefix]_radio_hoerercharts_suggestion');
    }
    
    /**
     * Gets the Entries.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]|array
     */
    public function getEntries($where = [], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('radio_hoerercharts_suggestion')
            ->where($where)
            ->order(['id' => 'DESC']);
        
        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id']);
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setDateCreate($entries['datecreate']);
            $entryModel->setUser_Id($entries['user_id']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the entry by given ID.
     *
     * @param int $id
     * @return null|EntriesModel
     */
    public function getEntryById($id)
    {
        $entryRow = $this->db()->select('*')
            ->from('radio_hoerercharts_suggestion')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($entryRow)) {
            return null;
        }

        $entryModel = new EntriesModel();
        $entryModel->setId($entryRow['id'])
            ->setInterpret($entryRow['interpret'])
            ->setSongTitel($entryRow['songtitel'])
            ->setDateCreate($entryRow['datecreate'])
            ->setUser_Id($entryRow['user_id']);

        return $entryModel;
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]|array
     */
    public function getEntriesBy($where = [], $orderBy = ['id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('radio_hoerercharts_suggestion')
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
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id']);
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setDateCreate($entries['datecreate']);
            $entryModel->setUser_Id($entries['user_id']);
            $entry[] = $entryModel;
        }
        return $entry;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return boolean
     */
    public function save(EntriesModel $model)
    {
        $fields = [
            'interpret'     => $model->getInterpret(),
            'songtitel'     => $model->getSongTitel(),
            'datecreate'    => $model->getDateCreate(),
            'user_id'       => $model->getUser_Id()
                            
        ];

        if ($model->getId()) {
            $this->db()->update('radio_hoerercharts_suggestion')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('radio_hoerercharts_suggestion')
                ->values($fields)
                ->execute();
        }

        return true;
    }

    /**
     * Deletes the entry.
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->db()->delete('radio_hoerercharts_suggestion')
            ->where(['id' => $id])
            ->execute();
    }
    
}
