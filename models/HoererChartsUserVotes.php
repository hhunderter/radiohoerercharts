<?php
/**
 * @copyright Dennis Reilard
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
    public function setId($id)
    {
        $this->id = (int)$id;

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
    public function setUser_Id($user_id)
    {
        $this->user_id = (int)$user_id;

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
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }
}
