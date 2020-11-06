<?php
/**
 * @copyright Dennis Reilard alias hhunderter
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
     * @throws \Ilch\Database\Exception
     */
    public function checkDB()
    {
        return $this->db()->ifTableExists('[prefix]_radio_hoerercharts_suggestion');
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
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id']);
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setDateCreate($entries['datecreate']);
            $entryModel->setUser_Id($entries['user_id']);
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
        return $this->getEntriesBy($where, ['id' => 'DESC'], $pagination);
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
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return boolean|integer
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
            $result = $this->db()->update('radio_hoerercharts_suggestion')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $result = $this->db()->insert('radio_hoerercharts_suggestion')
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * Deletes the entry.
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id)
    {
        return $this->db()->delete('radio_hoerercharts_suggestion')
            ->where(['id' => $id])
            ->execute();
    }
}
