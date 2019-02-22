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
			$entryModel->setSessionId($entries['session_id']);
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
            'user_id' => $model->getUser_Id(),
			'session_id' => $model->getSessionId()
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

    /**
     *
     *
     * @param EntriesModel $model
     * @return bool
     */
    public function save_user(EntriesModel $model)
    {
        $fields = [
            'id' => $model->getId()
        ];

        if ($model->getUser_Id()) {
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

    /**
     * Delete user vote with specific user_id.
     *
     * @param $user_id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function delete_user($user_id)
    {
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['user_id' => $user_id])
            ->execute();
    }
	
	/**
     * Delete user vote with specific session_id.
     *
     * @param $session_id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function delete_session($session_id)
    {
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['session_id' => $session_id])
            ->execute();
    }
	
	/**
     * Delete user vote with specific session_id.
     *
     * @param $session_id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function is_voted($User = null, $guestallow = false)
    {
		$User_Id = 0;
		if ($User) $User_Id = $User->getId();
		
		$voteId = (int) $this->db()->select('id')
            ->from('radio_hoerercharts_uservotes')
            ->where(['user_id >' => 0, 'user_id' => $User_Id])
            ->orWhere(['session_id' => session_id()])
            ->execute()
            ->fetchCell();
			
		if ($voteId){
			$entryModel = new EntriesModel();
			$entryModel->setId($voteId);
			if ($User_Id) $entryModel->setUser_Id($User_Id);
			$entryModel->setSessionId(session_id());
			$this->save($entryModel);	
			
			return true;
		}else{
			if (!$User_Id and !$guestallow)
				return true;
			else
				return false;
		}
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
