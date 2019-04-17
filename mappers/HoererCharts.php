<?php
/**
 * @copyright Dennis Reilard
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
     */
	public function checkDB()
    {
		return $this->db()->ifTableExists('[prefix]_radio_hoerercharts');
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
            ->from('radio_hoerercharts')
            ->where($where)
            ->order(['setfree' => 'DESC', 'id' => 'DESC']);
        
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
			$entryModel->setSetFree($entries['setfree']);
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setVotes($entries['votes']);
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
            ->from('radio_hoerercharts')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

		if (empty($entryRow)) {
            return null;
        }

		$entryModel = new EntriesModel();
		$entryModel->setId($entryRow['id'])
			->setSetFree($entryRow['setfree'])
			->setInterpret($entryRow['interpret'])
			->setSongTitel($entryRow['songtitel'])
			->setVotes($entryRow['votes'])
			->setDateCreate($entryRow['datecreate'])
			->setUser_Id($entryRow['user_id']);;

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
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setId($entries['id']);
			$entryModel->setSetFree($entries['setfree']);
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setVotes($entries['votes']);
			$entryModel->setDateCreate($entries['datecreate']);
			$entryModel->setUser_Id($entries['user_id']);
            $entry[] = $entryModel;
		}
        return $entry;
	}
	
	/**
     * Updates Entrie setfree with given id.
     *
     * @param int $id
     * @param int $setfree_man
     * @return boolean
     */
    public function update_setfree($id, $setfree_man)
    {
		if ($setfree_man != -1){
            $setfree_now = (int)$setfree_man;
		}else{
			$setfree = (int) $this->db()->select('setfree')
							->from('radio_hoerercharts')
							->where(['id' => $id])
							->execute()
							->fetchCell();

			if ($setfree == 1) $setfree_now = 0;
			else  $setfree_now = 1;
		}
		$test = $this->db()->update('radio_hoerercharts')
			->values(['setfree' => $setfree_now])
			->where(['id' => $id])
			->execute();

		return true;
    }

    /**
     * Updates Entrie with given id.
     *
     * @param int $id
     * @param int $votes_man
     * @return boolean
     */
    public function update($id, $votes_man)
    {
		if ($votes_man != -1){
            $votes_now = (int)$votes_man;
		}else{
			$votes = (int) $this->db()->select('votes')
							->from('radio_hoerercharts')
							->where(['id' => $id])
							->execute()
							->fetchCell();

			$votes_now = $votes + 1;
		}

		$this->db()->update('radio_hoerercharts')
			->values(['votes' => $votes_now])
			->where(['id' => $id])
			->execute();

		return true;
    }

	/**
     * Gets the hidden copyright text.
     *
     * @return String
     */
	public function gettext()
    {
		return " -> &copy; by Dennis Reilard alias hhunderter";
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
			'setfree' => $model->getSetFree(),
            'interpret' => $model->getInterpret(),
            'songtitel' => $model->getSongTitel(),
            'votes' => $model->getVotes(),
            'datecreate' => $model->getDateCreate(),
            'user_id' => $model->getUser_Id()
        ];

        if ($model->getId()) {
            $this->db()->update('radio_hoerercharts')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('radio_hoerercharts')
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
        return $this->db()->delete('radio_hoerercharts')
            ->where(['id' => $id])
            ->execute();
    }

	/**
     * Reset the Vote counts.
     *
     * @return boolean
     */
	public function reset()
    {
		$sql = 'UPDATE `[prefix]_radio_hoerercharts` SET votes=0;';
		return $this->db()->queryMulti($sql);
    }

	/**
     * Reset the Vote counts.
     *
     * @param integer $votes
	 * @param array $config
	 * @param boolean $showstars
	 * @return string
     */
	public function getStars($votes = 0, $config = null, $showstars = false)
    {
		if (is_array($config) and ($config['showstars'] or $showstars)){
			if (empty($votes)) { $stars = '<span style="color:#CCCCCC; font-size:18px; font-weight:bold;">*****</span>'; }
			elseif ($votes >= $config['Star5']) { $stars = '<span style="color:#FFCC00; font-size:18px; font-weight:bold;">*****</span>'; }
			elseif ($votes >= $config['Star4']) { $stars = '<span style="color:#FFCC00; font-size:18px; font-weight:bold;">****</span><span style="color:#CCCCCC; font-size:18px; font-weight:bold;">*</span></div>'; }
			elseif ($votes >= $config['Star3']) { $stars = '<span style="color:#FFCC00; font-size:18px; font-weight:bold;">***</span><span style="color:#CCCCCC; font-size:18px; font-weight:bold;">**</span></div>'; }
			elseif ($votes >= $config['Star2']) { $stars = '<span style="color:#FFCC00; font-size:18px; font-weight:bold;">**</span><span style="color:#CCCCCC; font-size:18px; font-weight:bold;">***</span></div>'; }
			elseif ($votes >= $config['Star1']) { $stars = '<span style="color:#FFCC00; font-size:18px; font-weight:bold;">*</span><span style="color:#CCCCCC; font-size:18px; font-weight:bold;">****</span></div>'; }
			elseif ($votes < $config['Star1']) { $stars = '<span style="color:#CCCCCC; font-size:18px; font-weight:bold;">*****</span>'; }
		}else{
			$stars = $votes;
		}
		return $stars;
    }
	
	/**
     * Checks if voting is allowed.
     *
     * @param string $start_datetime
	 * @param string $end_datetime
     * @return boolean
     */
    public function vote_allowed($start_datetime = null, $end_datetime = null)
    {
		$date = new \Ilch\Date();
		
		$datenow = new \Ilch\Date($date->format("Y-m-d H:i:s",true));
		
		if (!$start_datetime and !$end_datetime){
			return true;
		}else{
			if ($start_datetime){
				if (!$end_datetime){
					return (($datenow->getTimestamp() >= $start_datetime->getTimestamp()) ? true : false);
				}
			}
			if ($end_datetime){
				if (!$start_datetime){
					return (($datenow->getTimestamp() <= $end_datetime->getTimestamp()) ? true : false);
				}
			}
			//var_dump($datenow,$start_datetime,$end_datetime);
			return (($datenow->getTimestamp() >= $start_datetime->getTimestamp() && $datenow->getTimestamp() <= $end_datetime->getTimestamp()) ? true : false);
		}
    }
	
}
