<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as EntriesModel;

class HoererChartsUserVotes extends \Ilch\Mapper
{
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
            ->from('radio_hoerercharts_uservotes')
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
            'user_id' => $model->getUser_Id()
        ];

        if ($model->getId()) {
            $this->db()->update('radio_hoerercharts_uservotes')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('radio_hoerercharts_uservotes')
                ->values($fields)
                ->execute();
        }
		return true;
    }
	public function save_user(EntriesModel $model)
    {
        $fields = [
            'id' => $model->getId()
        ];

        if ($model->getId()) {
            $this->db()->update('radio_hoerercharts_uservotes')
                ->values($fields)
                ->where(['user_id' => $model->getUser_Id()])
                ->execute();
        } else {
            $this->db()->insert('radio_hoerercharts_uservotes')
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
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['id' => $id])
            ->execute();
    }
	public function delete_user($user_id)
    {
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['user_id' => $user_id])
            ->execute();
    }
	
	/**
     * Reset the Vote counts.
     *
	 * @return boolean
     */
	public function reset()
    {
		$this->db()->truncate('[prefix]_radio_hoerercharts_uservotes');
		return $this->db()->queryMulti('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` auto_increment = 1;');
    }
}
