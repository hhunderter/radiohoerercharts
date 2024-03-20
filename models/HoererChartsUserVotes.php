<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Models;

class HoererChartsUserVotes extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The User_Id.
     *
     * @var int
     */
    protected $user_id = 0;

    /**
     * The Session_Id.
     *
     * @var string
     */
    protected $sessionId = '';

    /**
     * The Ip.
     *
     * @var string
     */
    protected $ip = '';

    /**
     * The last_activity.
     *
     * @var string
     */
    protected $last_activity = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): HoererChartsUserVotes
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['session_id'])) {
            $this->setSessionId($entries['session_id']);
        }
        if (isset($entries['last_activity'])) {
            $this->setLastActivity($entries['last_activity']);
        }
        return $this;
    }

    /**
     * Gets the Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * Sets the Id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): HoererChartsUserVotes
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the User_Id.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
    /**
     * Sets the User_Id.
     *
     * @param int $user_id
     * @return $this
     */
    public function setUserId(int $user_id): HoererChartsUserVotes
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * The php session id of the guest or user.
     * Usefull to better identify a guest/user as there might be
     * more than one guest/user with the same ip-adress.
     *
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Set the php session id of the guest or user.
     *
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Gets the ip.
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
    /**
     * Sets the ip.
     *
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): HoererChartsUserVotes
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Gets the last_activity.
     *
     * @return string
     */
    public function getLastActivity(): string
    {
        return $this->last_activity;
    }
    /**
     * Sets the last_activity.
     *
     * @param string $last_activity
     * @return $this
     */
    public function setLastActivity(string $last_activity): HoererChartsUserVotes
    {
        $this->last_activity = $last_activity;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'user_id'           => $this->getUserId(),
                'session_id'        => $this->getSessionId(),
                'last_activity'     => $this->getLastActivity(),
                'ip_address'        => $this->getIp(),
            ]
        );
    }
}
