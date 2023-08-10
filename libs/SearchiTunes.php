<?php

namespace Modules\RadioHoererCharts\Libs;

class SearchiTunes
{
    /**
     * @var int
     */
    public const REQUEST_SEARCH = 1;
    /**
     * @var int
     */
    public const REQUEST_LOOKUP = 2;

    /**
     * The API url
     *
     * @var string
     */
    protected $apiUrl =      'https://itunes.apple.com/';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var bool
     */
    protected $debug = false;
    /**
     * @var mixed
     */
    protected $result = null;
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $term = '';
    /**
     * @var string
     */
    protected $country = ''; // US
    /**
     * @var string|null
     */
    protected $media = null; // https://developer.apple.com/library/archive/documentation/AudioVideo/Conceptual/iTuneSearchAPI/Searching.html#//apple_ref/doc/uid/TP40017632-CH5-SW3
    /**
     * @var string
     */
    protected $entity = '';
    /**
     * @var string
     */
    protected $attribute = '';
    /**
     * @var int
     */
    protected $limit = 0; // 1 to 200
    /**
     * @var string
     */
    protected $lang = ''; // en_us
    /**
     * @var bool
     */
    protected $explicit = false; // Yes, No

    /**
     * @var string|null
     */
    protected $lookup = 'id'; // id, amgArtistId, amgAlbumId, upc, amgVideoId, isbn

    /**
     * SearchiTunes constructor.
     *
     * @param string|null $term
     * @param string|null $entity
     */
    public function __construct(?string $term = null, ?string $entity = null)
    {
        if ($term) {
            $this->setTerm($term);
        }
        if ($entity) {
            $this->setEntity($entity);
        }
        return $this;
    }

    /**
     * max 20 calls per minute
     * @param string|null $term
     * @return false|SearchiTunes
     */
    public function search(?string $term = null)
    {
        if ($term) {
            $this->setTerm($term);
        }

        if ($this->getTerm()) {
            $this->makeRequest(self::REQUEST_SEARCH);
            return $this;
        } else {
            return false;
        }
    }

    /**
     * max 20 calls per minute
     * @param string|null $term
     * @return false|SearchiTunes
     */
    public function lookup(?string $term = null)
    {
        if ($term) {
            $this->setTerm($term);
        }

        if ($this->getTerm()) {
            $this->makeRequest(self::REQUEST_LOOKUP);
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Makes the actual request
     *
     * @param int $type
     *
     * @return void
     */
    protected function makeRequest(int $type)
    {
        $this->makeParameters($type);
        $this->makeUrl($type);
        if ($this->getUrl()) {
            $request = curl_init();

            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_URL, $this->getUrl());

            if ($this->isDebug()) {
                curl_setopt($request, CURLOPT_VERBOSE, true);
            }

            $response = curl_exec($request);
            $httpStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);

            if ($httpStatusCode !== 200) {
                $this->setResults(null);
            } else {
                $response = json_decode($response, true);
                $this->setResults($response['results']);
            }
        } else {
            $this->setResults(null);
        }
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return SearchiTunes
     */
    public function setApiUrl(string $apiUrl): SearchiTunes
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return SearchiTunes
     */
    public function setUrl(string $url): SearchiTunes
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param int $type
     * @return SearchiTunes
     */
    public function makeUrl(int $type): SearchiTunes
    {
        $this->setUrl('');
        if ($this->getParameters()) {
            switch ($type) {
                case self::REQUEST_SEARCH:
                    $this->setUrl($this->getApiUrl() . 'search?' . http_build_query($this->getParameters()));
                    break;
                case self::REQUEST_LOOKUP:
                    $this->setUrl($this->getApiUrl() . 'lookup?' . http_build_query($this->getParameters()));
                    break;
            }
        }

        return $this;
    }

    /**
     * Obtains the needed tokens
     * @param int $type
     * @return SearchiTunes
     */
    public function makeParameters(int $type): SearchiTunes
    {
        $this->setParameters(null);

        switch ($type) {
            case self::REQUEST_SEARCH:
                if ($this->getTerm() && $this->getCountry()) {
                    if ($this->getTerm()) {
                        $this->setParameter('term', $this->getTerm());
                    }
                    if ($this->getCountry()) {
                        $this->setParameter('country', $this->getCountry());
                    }
                    if ($this->getMedia()) {
                        $this->setParameter('media', $this->getMedia());
                    }
                    if ($this->getEntity()) {
                        $this->setParameter('entity', $this->getEntity());
                    }
                    if ($this->getAttribute()) {
                        $this->setParameter('attribute', $this->getAttribute());
                    }
                    if ($this->getLimit()) {
                        $this->setParameter('limit', $this->getLimit());
                    }
                    if ($this->getLang()) {
                        $this->setParameter('lang', $this->getLang());
                    }
                    $this->setParameter('explicit', ($this->getExplicit() ? 'Yes' : 'No'));
                }
                break;
            case self::REQUEST_LOOKUP:
                if ($this->getLookup() && $this->getTerm()) {
                    if ($this->getTerm()) {
                        $this->setParameter($this->getLookup(), $this->getTerm());
                    }
                    if ($this->getEntity()) {
                        $this->setParameter('entity', $this->getEntity());
                    }
                    if ($this->getLimit()) {
                        $this->setParameter('limit', $this->getLimit());
                    }
                }
                break;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array|null $parameters
     * @return SearchiTunes
     */
    public function setParameters(?array $parameters): SearchiTunes
    {
        if (!$parameters) {
            $parameters = [];
        }
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return SearchiTunes
     */
    public function setParameter($key, $value): SearchiTunes
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParameters(): bool
    {
        return count($this->getParameters()) > 0;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResults($result)
    {
        $this->result = $result;
    }

    /**
     * @param string|null $key
     * @param int|null $id
     * @return mixed
     */
    public function getResult(?string $key = null, ?int $id = null)
    {
        if (!$key && !$id) {
            return $this->getResults();
        }

        $keys = [   'wrapperType',
                    'collectionType',
                    'artistId',
                    'collectionId',
                    'amgArtistId',
                    'artistName',
                    'collectionName',
                    'collectionCensoredName',
                    'artistViewUrl',
                    'collectionViewUrl',
                    'artworkUrl60',
                    'artworkUrl100',
                    'collectionPrice',
                    'collectionExplicitness',
                    'trackCount',
                    'copyright',
                    'country',
                    'currency',
                    'releaseDate',
                    'primaryGenreName',
                ];

        if ($key && !in_array($key, $keys)) {
            return false;
        }

        if ($id && isset($this->result[$id - 1])) {
            if ($key) {
                return $this->result[$id - 1][$key] ?? '';
            } else {
                return $this->result[$id - 1];
            }
        } elseif (!$id) {
            $result = [];
            foreach ($this->getResults() as $resarray) {
                $result[] = ($resarray[$key] ?? '');
            }
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getTerm(): string
    {
        return $this->term;
    }

    /**
     * @param string $term
     * @return SearchiTunes
     */
    public function setTerm(string $term): SearchiTunes
    {
        $this->term = $term;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return SearchiTunes
     */
    public function setCountry(string $country): SearchiTunes
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMedia(): ?string
    {
        return $this->media;
    }

    /**
     * @param string|null $media
     * @return SearchiTunes
     */
    public function setMedia(?string $media): SearchiTunes
    {
        $keys = [   'movie',
                    'podcast',
                    'music',
                    'musicVideo',
                    'audiobook',
                    'shortFilm',
                    'tvShow',
                    'software',
                    'ebook',
                    'all',
                ];
        if ($media && in_array($media, $keys)) {
            $this->media = $media;
        } else {
            $this->media = null;
        }
        return $this;
    }

    public function getEntity(): string
    {
        if ($this->getMedia()) {
            return $this->entity;
        }
        return '';
    }

    /**
     * @param string $entity
     * @return SearchiTunes
     */
    public function setEntity(string $entity): SearchiTunes
    {
        $keys = [   'movie' => [     'movieArtist',
                                     'movie',
                                ],
                    'podcast' => [   'podcastAuthor',
                                     'podcast',
                                ],
                    'music' => [     'musicArtist',
                                     'musicTrack',
                                     'album',
                                     'musicVideo',
                                     'mix',
                                     'song',
                                ],
                    'musicVideo' => ['musicArtist',
                                     'musicVideo',
                                ],
                    'audiobook' => [ 'audiobookAuthor',
                                     'audiobook',
                                ],
                    'shortFilm' => [ 'shortFilmArtist',
                                     'shortFilm',
                                ],
                    'tvShow' => [    'tvEpisode',
                                     'tvSeason',
                                ],
                    'software' => [  'software',
                                     'iPadSoftware',
                                     'macSoftware',
                                ],
                    'ebook' => [     'ebook',
                                ],
                    'all' => [       'movie',
                                     'album',
                                     'allArtist',
                                     'podcast',
                                     'musicVideo',
                                     'mix',
                                     'audiobook',
                                     'tvSeason',
                                     'allTrack',
                                ],
                ];
        if ($this->getMedia() && $entity && isset($keys[$this->getMedia()]) && in_array($entity, $keys[$this->getMedia()])) {
            $this->entity = $entity;
        } else {
            $this->entity = '';
        }
        return $this;
    }

    public function getAttribute(): string
    {
        if ($this->getMedia()) {
            return $this->attribute;
        }
        return '';
    }

    public function setAttribute(string $attribute): SearchiTunes
    {
        $keys = [   'movie' => [     'actorTerm',
                                     'genreIndex',
                                     'artistTerm',
                                     'shortFilmTerm',
                                     'producerTerm',
                                     'ratingTerm',
                                     'directorTerm',
                                     'releaseYearTerm',
                                     'featureFilmTerm',
                                     'movieArtistTerm',
                                     'movieTerm',
                                     'ratingIndex',
                                     'descriptionTerm',
                                ],
                    'podcast' => [   'titleTerm',
                                     'languageTerm',
                                     'authorTerm',
                                     'genreIndex',
                                     'artistTerm',
                                     'ratingIndex',
                                     'keywordsTerm',
                                     'descriptionTerm',
                                ],
                    'music' => [     'mixTerm',
                                     'genreIndex',
                                     'artistTerm',
                                     'composerTerm',
                                     'albumTerm',
                                     'ratingIndex',
                                     'songTerm',
                                ],
                    'musicVideo' => ['genreIndex',
                                     'artistTerm',
                                     'albumTerm',
                                     'ratingIndex',
                                     'songTerm',
                                ],
                    'audiobook' => [ 'titleTerm',
                                     'authorTerm',
                                     'genreIndex',
                                     'ratingIndex',
                                ],
                    'shortFilm' => [ 'genreIndex',
                                     'artistTerm',
                                     'shortFilmTerm',
                                     'ratingIndex',
                                     'descriptionTerm',
                                ],
                    'tvShow' => [    'genreIndex',
                                     'tvEpisodeTerm',
                                     'showTerm',
                                     'tvSeasonTerm',
                                     'ratingIndex',
                                     'descriptionTerm',
                                ],
                    'software' => [  'softwareDeveloper',
                                ],
                    'all' => [       'actorTerm',
                                     'languageTerm',
                                     'allArtistTerm',
                                     'tvEpisodeTerm',
                                     'shortFilmTerm',
                                     'directorTerm',
                                     'releaseYearTerm',
                                     'titleTerm',
                                     'featureFilmTerm',
                                     'ratingIndex',
                                     'keywordsTerm',
                                     'descriptionTerm',
                                     'authorTerm',
                                     'genreIndex',
                                     'mixTerm',
                                     'allTrackTerm',
                                     'artistTerm',
                                     'composerTerm',
                                     'tvSeasonTerm',
                                     'producerTerm',
                                     'ratingTerm',
                                     'songTerm',
                                     'movieArtistTerm',
                                     'showTerm',
                                     'movieTerm',
                                     'albumTerm',
                                ],
                ];
        if ($this->getMedia() && $attribute && isset($keys[$this->getMedia()]) && in_array($attribute, $keys[$this->getMedia()])) {
            $this->attribute = $attribute;
        } else {
            $this->attribute = '';
        }
        return $this;
    }


    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): SearchiTunes
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return SearchiTunes
     */
    public function setLang(string $lang): SearchiTunes
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return bool
     */
    public function getExplicit(): bool
    {
        return $this->explicit;
    }

    /**
     * @param bool $explicit
     * @return SearchiTunes
     */
    public function setExplicit(bool $explicit): SearchiTunes
    {
        $this->explicit = $explicit;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLookup(): ?string
    {
        return $this->lookup;
    }

    /**
     * @param string $lookup
     * @return SearchiTunes
     */
    public function setLookup(string $lookup): SearchiTunes
    {
        $keys = [   'id',
                    'amgArtistId',
                    'amgAlbumId',
                    'upc',
                    'amgVideoId',
                    'isbn',
                ];
        if ($lookup && in_array($lookup, $keys)) {
            $this->lookup = $lookup;
        } else {
            $this->lookup = null;
        }
        return $this;
    }
}
