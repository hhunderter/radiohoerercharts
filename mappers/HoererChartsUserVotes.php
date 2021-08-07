<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Mappers;

use Modules\RadioHoererCharts\Models\HoererChartsUserVotes as EntriesModel;

class HoererChartsUserVotes extends \Ilch\Mapper
{
    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     */
    public function checkDB()
    {
        return $this->db()->ifTableExists('[prefix]_radio_hoerercharts_uservotes');
    }

    /**
     * Gets the Entries.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('radio_hoerercharts_uservotes')
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
            $entryModel->setUser_Id($entries['user_id']);
            $entryModel->setSessionId($entries['session_id']);
            $entryModel->setIp($entries['ip_address']);
            $entryModel->setLast_Activity($entries['last_activity']);
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
            'user_id'       => $model->getUser_Id(),
            'session_id'    => $model->getSessionId(),
            'last_activity' => $model->getLast_Activity(),
            'ip_address'    => $model->getIp(),
        ];

        if ($model->getId()) {
            $result = $this->db()->update('radio_hoerercharts_uservotes')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $result = $this->db()->insert('radio_hoerercharts_uservotes')
                ->values($fields)
                ->execute();
        }
        return $result;
    }

    /**
     * Inserts or updates entry by User.
     *
     * @param EntriesModel $model
     * @return bool|integer
     */
    public function save_user(EntriesModel $model)
    {
        $fields = [
            'id' => $model->getId()
        ];

        if ($model->getUser_Id()) {
            $result = $this->db()->update('radio_hoerercharts_uservotes')
                ->values($fields)
                ->where(['user_id' => $model->getUser_Id()])
                ->execute();
        } else {
            $result = $this->db()->insert('radio_hoerercharts_uservotes')
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
    public function delete_user(int $user_id)
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
    public function delete_session(string $session_id)
    {
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['session_id' => $session_id])
            ->execute();
    }

    /**
     * Delete user vote with specific ip_address.
     *
     * @param string|null $ip_address
     * @return boolean
     */
    public function delete_ip(string $ip_address)
    {
        if (!$ip_address) {
            $ip_address = $this->getIp();
        }
        
        return $this->db()->delete('radio_hoerercharts_uservotes')
            ->where(['ip_address' => $ip_address])
            ->execute();
    }
    
    /**
     * get thi ip_address
     *
     * @return string
     */
    public function getIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = '128.0.0.1';
        }
        
        return $ip_address;
    }

    /**
     * Gets the entry by given ID.
     *
     * @param \Modules\User\Models\User $User
     * @return integer
     */
    public function getEntryByUserSessionIp($User = null)
    {
        $ip = $this->getIp();

        $User_Id = 0;
        if ($User) {
            $User_Id = $User->getId();
        }

        $oldsession = session_id();
        if (isset($_COOKIE['RadioHoererCharts_is_voted'])) {
            $oldsession = $_COOKIE['RadioHoererCharts_is_voted'];
            if ($oldsession != session_id()) {
                $this->setIsVotedCookie(session_id());
            }
        }

        $voteId = (int) $this->db()->select('id')
            ->from('radio_hoerercharts_uservotes')
            ->Where(['session_id' => $oldsession])
            ->execute()
            ->fetchCell();
        if (!$voteId && $User_Id > 0) {
            $voteId = (int) $this->db()->select('id')
                ->from('radio_hoerercharts_uservotes')
                ->Where(['user_id >' => 0, 'user_id' => $User_Id])
                ->execute()
                ->fetchCell();
        }
        if (!$voteId && $User_Id === 0) {
            $voteId = (int) $this->db()->select('id')
                ->from('radio_hoerercharts_uservotes')
                ->Where(['ip_address' => $ip])
                ->execute()
                ->fetchCell();
        }

        return $voteId;
    }

    /**
     * Sets the is_voted Cookie.
     *
     * @param String $sessionid
     */
    public function setIsVotedCookie(String $sessionid)
    {
        setcookieIlch('RadioHoererCharts_is_voted', $sessionid, strtotime('+1 days'));
    }

    /**
     * Check if user has already voted or if guests can vote.
     *
     * @param \Modules\User\Models\User $User
     * @return boolean
     */
    public function is_voted($User = null)
    {
        $config = \Ilch\Registry::get('config');

        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));

        $User_Id = 0;
        if ($User) {
            $User_Id = $User->getId();
        }

        $voteId = $this->getEntryByUserSessionIp($User);

        if ($voteId > 0) {
            $returnvalue = true;
            $entryModel = $this->getEntryById($voteId);

            if (!empty($entryModel->getLast_Activity())) {
                $dateentry = new \Ilch\Date($entryModel->getLast_Activity());
            } else {
                $dateentry = clone $datenow;
            }

            if ($config->get('radio_hoerercharts_all_sec_vote') > 0) {
                $dateentryclone = clone $dateentry;
                $dateentryclone->modify('+'.$config->get('radio_hoerercharts_all_sec_vote').' seconds');

                if ($dateentryclone->getTimestamp() < $datenow->getTimestamp()) {
                    $returnvalue = false;
                }
            }

            $entryModel->setLast_Activity($dateentry);
            if ($User_Id) {
                $entryModel->setUser_Id($User_Id);
            }
            $entryModel->setSessionId(session_id());
            $this->save($entryModel);
            return $returnvalue;
        } else {
            return (!$User_Id && !$config->get('radio_hoerercharts_Guest_Allow'));
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
