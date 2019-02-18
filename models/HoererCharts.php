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
        $this->interpret = $interpret;

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
        $this->songtitel = (string)$songtitel;

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
        $this->votes = (int)$votes;

        return $this;
    }

}
