<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Models;

use Modules\RadioHoererCharts\Models\HoererCharts as EntriesModel;

class HoererChartsList extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int
     */
    protected $id;

    /**
     * The Entry ID.
     *
     * @var int
     */
    protected $hid;
    
    /**
     * The Entry ID.
     *
     * @var EntriesModel
     */
    protected $entry;

    /**
     * The List ID.
     *
     * @var int
     */
    protected $list;
    

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
     * Gets the hid.
     *
     * @return int
     */
    public function getHId()
    {
        return $this->hid;
    }
    /**
     * Sets the setfree.
     *
     * @param int $setfree
     * @return $this
     */
    public function setHId(int $hid)
    {
        $this->hid = (int) $hid;

        return $this;
    }

    /**
     * Gets the Entry.
     *
     * @return EntriesModel
     */
    public function getEntry()
    {
        return $this->entry;
    }
    /**
     * Sets the Entry.
     *
     * @param EntriesModel $entry
     * @return $this
     */
    public function setEntry(EntriesModel $entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Gets the List.
     *
     * @return int
     */
    public function getList()
    {
        return $this->list;
    }
    /**
     * Sets the List.
     *
     * @param int $list
     * @return $this
     */
    public function setList(string $list)
    {
        $this->list = (int) $list;

        return $this;
    }

}
