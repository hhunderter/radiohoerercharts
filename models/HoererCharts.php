<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Models;

class HoererCharts extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int
     */
    protected $id;
    
    /**
     * The setfree.
     *
     * @var int
     */
    protected $setfree;

    /**
     * The Interpret.
     *
     * @var string
     */
    protected $interpret;

    /**
     * The Songtitel.
     *
     * @var string
     */
    protected $songtitel;

    /**
     * The Votes.
     *
     * @var int
     */
    protected $votes;
    
    /**
     * The DateCreate.
     *
     * @var string
     */
    protected $datecreate;
    
    /**
     * The user_id.
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
        $this->id = (int) $id;

        return $this;
    }
    
    /**
     * Gets the setfree.
     *
     * @return int
     */
    public function getSetFree()
    {
        return $this->setfree;
    }
    /**
     * Sets the setfree.
     *
     * @param int $setfree
     * @return $this
     */
    public function setSetFree($setfree)
    {
        $this->setfree = (int) $setfree;

        return $this;
    }

    /**
     * Gets the Interpret.
     *
     * @return string
     */
    public function getInterpret()
    {
        return $this->interpret;
    }
    /**
     * Sets the Interpret.
     *
     * @param String $interpret
     * @return $this
     */
    public function setInterpret($interpret)
    {
        $this->interpret = (string) $interpret;

        return $this;
    }

    /**
     * Gets the SongTitel.
     *
     * @return string
     */
    public function getSongTitel()
    {
        return $this->songtitel;
    }
    /**
     * Sets the SongTitel.
     *
     * @param String $songtitel
     * @return $this
     */
    public function setSongTitel($songtitel)
    {
        $this->songtitel = (string) $songtitel;

        return $this;
    }

    /**
     * Gets the Votes.
     *
     * @return int
     */
    public function getVotes()
    {
        return $this->votes;
    }
    /**
     * Sets the Votes.
     *
     * @param int $votes
     * @return $this
     */
    public function setVotes($votes)
    {
        $this->votes = (int) $votes;

        return $this;
    }
    
    /**
     * Gets the datecreate.
     *
     * @return String
     */
    public function getDateCreate()
    {
        return $this->datecreate;
    }
    /**
     * Sets the datecreate.
     *
     * @param String $datecreate
     * @return $this
     */
    public function setDateCreate($datecreate)
    {
        $this->datecreate = (string) $datecreate;

        return $this;
    }
    
    /**
     * Gets the user_id.
     *
     * @return int
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }
    /**
     * Sets the user_id.
     *
     * @param int $user_id
     * @return $this
     */
    public function setUser_Id($user_id)
    {
        $this->user_id = (int) $user_id;

        return $this;
    }

}
