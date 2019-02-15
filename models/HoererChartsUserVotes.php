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
}
