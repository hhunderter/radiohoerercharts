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
            $entryModel->setLast_Activity($entries['last_activity']);
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
            ->from('radio_hoerercharts_uservotes')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($entryRow)) {
            return null;
        }

        $entryModel = new EntriesModel();
        $entryModel->setId($entryRow['id']);
        $entryModel->setUser_Id($entryRow['user_id']);
        $entryModel->setSessionId($entryRow['session_id']);
        $entryModel->setLast_Activity($entryRow['last_activity']);

        return $entryModel;
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
            'user_id'       => $model->getUser_Id(),
            'session_id'    => $model->getSessionId(),
            'last_activity' => $model->getLast_Activity()
                            
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
     * @param integer $user_id
     * @return boolean
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
     * @param string $session_id
     * @return boolean
     */
    public function delete_session($session_id)
    {
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['session_id' => $session_id])
            ->execute();
    }

    /**
     * Gets the entry by given ID.
     *
     * @param \Modules\User\Models\User $User
     * @return integer
     */
    public function getEntryByUserSession($User = null)
    {
        $User_Id = 0;
        if ($User) $User_Id = $User->getId();

        $oldsession = session_id();
        if (isset($_COOKIE['RadioHoererCharts_is_voted'])) {
            $oldsession = $_COOKIE['RadioHoererCharts_is_voted'];
            if ($oldsession != session_id()) {
                setcookie('RadioHoererCharts_is_voted', session_id(), strtotime( '+1 days' ), '/', $_SERVER['SERVER_NAME'], (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'), true);
            }
        }

        $voteId = (int) $this->db()->select('id')
            ->from('radio_hoerercharts_uservotes')
            ->Where(['session_id' => $oldsession])
            ->execute()
            ->fetchCell();
        if (!$voteId and $User_Id > 0) {
            $voteId = (int) $this->db()->select('id')
            ->from('radio_hoerercharts_uservotes')
            ->Where(['user_id >' => 0, 'user_id' => $User_Id])
            ->execute()
            ->fetchCell();
        }

        return $voteId;
    }
    
    /**
     * Check if user has already voted or if guests can vote.
     *
     * @param \Modules\User\Models\User $User
     * @param boolean $guestallow
     * @param integer $timediff
     * @return boolean
     */
    public function is_voted($User = null, $guestallow = false, $timediff = 30)
    {
        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s",true));
        //$datenow->modify('+1 hours');

        $User_Id = 0;
        if ($User) $User_Id = $User->getId();

        $voteId = $this->getEntryByUserSession($User);

        if ($voteId > 0) {

            $returnvalue = true;
            $entryModel = $this->getEntryById($voteId);

            if (!empty($entryModel->getLast_Activity())) $dateentry = new \Ilch\Date($entryModel->getLast_Activity());
            else $dateentry = clone $datenow;

            if ($timediff > 0) {

                $dateentryclone = clone $dateentry;
                $dateentryclone->modify('+'.$timediff.' seconds');

                if ($dateentryclone->getTimestamp() < $datenow->getTimestamp()) {
                    $returnvalue = false;
                }
            }

            $entryModel->setLast_Activity($dateentry);
            if ($User_Id) $entryModel->setUser_Id($User_Id);
            $entryModel->setSessionId(session_id());
            $this->save($entryModel);  
            return $returnvalue;
        } else {
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
     * @throws \Ilch\Database\Exception
     */
    public function reset()
    {
        $this->db()->truncate('[prefix]_radio_hoerercharts_uservotes');
        return $this->db()->queryMulti('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` auto_increment = 1;');
    }
}
