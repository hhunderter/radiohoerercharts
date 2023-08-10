<?php

/**
 * @copyright Dennis Reilard alias hhunderter
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
    protected $id = 0;

    /**
     * The setfree.
     *
     * @var bool
     */
    protected $setfree = false;

    /**
     * The Interpret.
     *
     * @var string
     */
    protected $interpret = '';

    /**
     * The Songtitel.
     *
     * @var string
     */
    protected $songtitel = '';

    /**
     * The Votes.
     *
     * @var int
     */
    protected $votes = 0;

    /**
     * The DateCreate.
     *
     * @var string
     */
    protected $datecreate = '';

    /**
     * The user_id.
     *
     * @var int
     */
    protected $user_id = 0;

    /**
     * The artworkUrl.
     *
     * @var string
     */
    protected $artworkUrl = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): HoererCharts
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['setfree'])) {
            $this->setSetFree($entries['setfree']);
        }
        if (isset($entries['interpret'])) {
            $this->setInterpret($entries['interpret']);
        }
        if (isset($entries['songtitel'])) {
            $this->setSongTitel($entries['songtitel']);
        }
        if (isset($entries['votes'])) {
            $this->setVotes($entries['votes']);
        }
        if (isset($entries['datecreate'])) {
            $this->setDateCreate($entries['datecreate']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['artworkUrl'])) {
            $this->setArtworkUrl($entries['artworkUrl']);
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
    public function setId(int $id): HoererCharts
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the setfree.
     *
     * @return bool
     */
    public function getSetFree(): bool
    {
        return $this->setfree;
    }
    /**
     * Sets the setfree.
     *
     * @param bool $setfree
     * @return $this
     */
    public function setSetFree(bool $setfree): HoererCharts
    {
        $this->setfree = $setfree;

        return $this;
    }

    /**
     * Gets the Interpret.
     *
     * @return string
     */
    public function getInterpret(): string
    {
        return $this->interpret;
    }
    /**
     * Sets the Interpret.
     *
     * @param String $interpret
     * @return $this
     */
    public function setInterpret(string $interpret): HoererCharts
    {
        $this->interpret = $interpret;

        return $this;
    }

    /**
     * Gets the SongTitel.
     *
     * @return string
     */
    public function getSongTitel(): string
    {
        return $this->songtitel;
    }
    /**
     * Sets the SongTitel.
     *
     * @param String $songtitel
     * @return $this
     */
    public function setSongTitel(string $songtitel): HoererCharts
    {
        $this->songtitel = $songtitel;

        return $this;
    }

    /**
     * Gets the Votes.
     *
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }
    /**
     * Sets the Votes.
     *
     * @param int $votes
     * @return $this
     */
    public function setVotes(int $votes): HoererCharts
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Gets the datecreate.
     *
     * @return String
     */
    public function getDateCreate(): string
    {
        return $this->datecreate;
    }
    /**
     * Sets the datecreate.
     *
     * @param String $datecreate
     * @return $this
     */
    public function setDateCreate(string $datecreate): HoererCharts
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    /**
     * Gets the user_id.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
    /**
     * Sets the user_id.
     *
     * @param int $user_id
     * @return $this
     */
    public function setUserId(int $user_id): HoererCharts
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Gets the artworkUrl.
     *
     * @return string
     */
    public function getArtworkUrl(): string
    {
        return $this->artworkUrl;
    }
    /**
     * Sets the artworkUrl.
     *
     * @param string $artworkUrl
     * @return $this
     */
    public function setArtworkUrl(string $artworkUrl): HoererCharts
    {
        $this->artworkUrl = $artworkUrl;

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
                'setfree'       => $this->getSetFree(),
                'interpret'     => $this->getInterpret(),
                'songtitel'     => $this->getSongTitel(),
                'votes'         => $this->getVotes(),
                'datecreate'    => $this->getDateCreate(),
                'user_id'       => $this->getUserId(),
                'artworkUrl'    => $this->getArtworkUrl(),
            ]
        );
    }
}
