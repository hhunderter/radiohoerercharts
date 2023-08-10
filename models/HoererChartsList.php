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
    protected $id = 0;

    /**
     * The Entry ID.
     *
     * @var int
     */
    protected $hid = 0;

    /**
     * The Entry ID.
     *
     * @var EntriesModel
     */
    protected $entry = null;

    /**
     * The List ID.
     *
     * @var int
     */
    protected $list = 0;

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): HoererChartsList
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['hid'])) {
            $this->setHId($entries['hid']);
        }
        if (isset($entries['list'])) {
            $this->setList($entries['list']);
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
    public function setId(int $id): HoererChartsList
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the hid.
     *
     * @return int
     */
    public function getHId(): int
    {
        return $this->hid;
    }

    /**
     * Sets the setfree.
     *
     * @param int $hid
     * @return $this
     */
    public function setHId(int $hid): HoererChartsList
    {
        $this->hid = $hid;

        return $this;
    }

    /**
     * Gets the Entry.
     *
     * @return EntriesModel
     */
    public function getEntry(): EntriesModel
    {
        if (!$this->entry) {
            $this->entry = new EntriesModel();
        }
        return $this->entry;
    }
    /**
     * Sets the Entry.
     *
     * @param EntriesModel $entry
     * @return $this
     */
    public function setEntry(EntriesModel $entry): HoererChartsList
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Gets the List.
     *
     * @return int
     */
    public function getList(): int
    {
        return $this->list;
    }

    /**
     * Sets the List.
     *
     * @param int $list
     * @return $this
     */
    public function setList(int $list): HoererChartsList
    {
        $this->list = $list;

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
                'hid'       => $this->getHId(),
                'list'     => $this->getList(),
            ]
        );
    }
}
