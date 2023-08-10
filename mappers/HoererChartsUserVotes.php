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
     * @var string
     */
    public $tablename = 'radio_hoerercharts_uservotes';

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
     * Gets the Entries.
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

        foreach ($entriesArray as $entryArray) {
            $entryModel = new EntriesModel();
            $entryModel->setByArray($entryArray);

            $entries[] = $entryModel;
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
        return $this->getEntriesBy($where, ['id' => 'DESC'], $pagination);
    }

    /**
     * Gets the entry by given ID.
     *
     * @param int $id
     * @return null|EntriesModel
     */
    public function getEntryById(int $id): ?EntriesModel
    {
        $entries = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return int
     */
    public function save(EntriesModel $model): int
    {
        $fields = $model->getArray(false);

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
        return $result;
    }

    /**
     * Inserts or updates entry by User.
     *
     * @param EntriesModel $model
     * @return int
     */
    public function saveUser(EntriesModel $model): int
    {
        $fields = [
            'id' => $model->getId()
        ];

        if ($model->getUserId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['user_id' => $model->getUserId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
        return $result;
    }

    /**
     * Deletes the entry.
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete user vote with specific user_id.
     *
     * @param integer $user_id
     * @return bool
     */
    public function deleteUser(int $user_id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['user_id' => $user_id])
            ->execute();
    }

    /**
     * Delete user vote with specific session_id.
     *
     * @param string $session_id
     * @return bool
     */
    public function deleteSession(string $session_id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['session_id' => $session_id])
            ->execute();
    }

    /**
     * Delete user vote with specific ip_address.
     *
     * @param string|null $ip_address
     * @return bool
     */
    public function deleteIp(?string $ip_address): bool
    {
        if (!$ip_address) {
            $ip_address = $this->getIp();
        }

        return $this->db()->delete($this->tablename)
            ->where(['ip_address' => $ip_address])
            ->execute();
    }

    /**
     * get thi ip_address
     *
     * @return string
     */
    public function getIp(): string
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
     * @param \Modules\User\Models\User|null $User
     * @return int
     */
    public function getEntryByUserSessionIp(?\Modules\User\Models\User $User = null): int
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
            ->from($this->tablename)
            ->Where(['session_id' => $oldsession])
            ->execute()
            ->fetchCell();
        if (!$voteId && $User_Id > 0) {
            $voteId = (int) $this->db()->select('id')
                ->from($this->tablename)
                ->Where(['user_id >' => 0, 'user_id' => $User_Id])
                ->execute()
                ->fetchCell();
        }
        if (!$voteId && $User_Id === 0) {
            $voteId = (int) $this->db()->select('id')
                ->from($this->tablename)
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
    public function setIsVotedCookie(string $sessionid)
    {
        setcookieIlch('RadioHoererCharts_is_voted', $sessionid, strtotime('+1 days'));
    }

    /**
     * Check if user has already voted or if guests can vote.
     *
     * @param \Modules\User\Models\User|null $User
     * @return bool
     */
    public function isVoted(?\Modules\User\Models\User $User = null): bool
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

            if (!empty($entryModel->getLastActivity())) {
                $dateentry = new \Ilch\Date($entryModel->getLastActivity());
            } else {
                $dateentry = clone $datenow;
            }

            if ($config->get('radio_hoerercharts_all_sec_vote') > 0) {
                $dateentryclone = clone $dateentry;
                $dateentryclone->modify('+' . $config->get('radio_hoerercharts_all_sec_vote') . ' seconds');

                if ($dateentryclone->getTimestamp() < $datenow->getTimestamp()) {
                    $returnvalue = false;
                }
            }

            $entryModel->setLastActivity($dateentry);
            if ($User_Id) {
                $entryModel->setUserId($User_Id);
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
     * @return bool
     * @throws \Ilch\Database\Exception
     */
    public function reset(): bool
    {
        $this->db()->truncate($this->tablename);
        return $this->db()->queryMulti('ALTER TABLE `[prefix]_' . $this->tablename . '` auto_increment = 1;');
    }
}
