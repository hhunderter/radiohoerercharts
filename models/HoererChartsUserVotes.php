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
    protected $id;

    /**
     * The User_Id.
     *
     * @var int
     */
    protected $user_id;

    /**
     * The Session_Id.
     *
     * @var string
     */
    protected $sessionId;

    /**
     * The last_activity.
     *
     * @var string
     */
    protected $last_activity;

    /**
     * Gets the Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Sets the Id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Gets the User_Id.
     *
     * @return int
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }
    /**
     * Sets the User_Id.
     *
     * @param int $user_id
     * @return $this
     */
    public function setUser_Id(int $user_id)
    {
        $this->user_id = (int) $user_id;

        return $this;
    }

    /**
     * The php session id of the guest or user.
     * Usefull to better identify a guest/user as there might be
     * more than one guest/user with the same ip-adress.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the php session id of the guest or user.
     *
     * @param string $sessionId
     */
    public function setSessionId(String $sessionId)
    {
        $this->sessionId = (string) $sessionId;
    }

    /**
     * Gets the last_activity.
     *
     * @return string
     */
    public function getLast_Activity()
    {
        return $this->last_activity;
    }
    /**
     * Sets the last_activity.
     *
     * @param string $last_activity
     * @return $this
     */
    public function setLast_Activity(String $last_activity)
    {
        $this->last_activity = (string) $last_activity;

        return $this;
    }
}
