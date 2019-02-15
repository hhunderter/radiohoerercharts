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
            $entryModel->setVotes($entries['votes']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
	
	/**
     * Gets the Entries by given ID.
     *
     * @param array $id
     * @return EntriesModel[]
     */
	public function getEntriesById($id = '')
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
			->setInterpret($entryRow['interpret'])
			->setSongTitel($entryRow['songtitel'])
			->setVotes($entryRow['votes']);

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
            $entryModel->setInterpret($entries['interpret']);
            $entryModel->setSongTitel($entries['songtitel']);
            $entryModel->setVotes($entries['votes']);
            $entry[] = $entryModel;
        }

        return $entry;
	}
	
	/**
     * Updates Entrie with given id.
     *
     * @param integer $id
	 * @return boolean
     */
    public function update($id, $votes_man)
    {
		if ($votes_man != -1){
			$votes = (int)$votes_man;
			$status_now = $votes;
		}else{
			$votes = (int) $this->db()->select('votes')
							->from('radio_hoerercharts')
							->where(['id' => $id])
							->execute()
							->fetchCell();
							
			$votes_now = $votes + 1;
		}

		$test = $this->db()->update('radio_hoerercharts')
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
            'interpret' => $model->getInterpret(),
            'songtitel' => $model->getSongTitel(),
            'votes' => $model->getVotes()
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
		$sql = '	UPDATE `[prefix]_radio_hoerercharts` SET votes=0;';
		return $this->db()->queryMulti($sql);
    }
	
	/**
     * Reset the Vote counts.
     *
     * @param integer $votes
	 * @param array $config
	 * @param boolean $showstars
	 * @return boolean
     */
	public function getStars($votes = 0, $config = '', $showstars = false)
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
	
}
