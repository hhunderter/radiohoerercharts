<?php

namespace Modules\RadioHoererCharts\Libs;

class SearchiTunes {
    
    const REQUEST_SEARCH = 1;
    const REQUEST_LOOKUP = 2;

    /**
     * The API url
     *
     * @var string
     */
    protected $apiUrl =      'https://itunes.apple.com/';
    
    protected $parameters = null;
    protected $debug = 0;
    protected $result = null;
    protected $url = '';
    
    
    protected $term = null;
    protected $country = null; // US
    protected $media = null; // https://developer.apple.com/library/archive/documentation/AudioVideo/Conceptual/iTuneSearchAPI/Searching.html#//apple_ref/doc/uid/TP40017632-CH5-SW3
    protected $entity = null;
    protected $attribute = null;
    protected $limit = null; // 1 to 200 
    protected $lang = null; // en_us
    protected $explicit = null; // Yes, No 
    
    protected $lookup = 'id'; // id, amgArtistId, amgAlbumId, upc, amgVideoId, isbn
    protected $sort = 'recent';


    /**
     * SearchiTunes constructor.
     *
     * @param $term       string|null  Wargaming Application API Key
     * @param $country    string|null  Wargaming Application API Secret
     * @param $media      string|null  Wargaming Application API Secret
     * @param $entity     string|null  OAuth Token
     * @param $attribute  string|null  OAuth Token Secret
     * @param $limit      int|null     The callback Url
     * @param $lang       string|null  The callback Url
     * @param $explicit   bool|null    The callback Url
     */
    public function __construct($term = null, $entity = null)
    {
        if ($term) $this->setTerm($term);
        if ($entity) $this->setTerm($entity);
        return $this;
    }

    /**
     * max 20 calls per minute
     */
    public function search($term = null)
    {
        if ($term) $this->setTerm($term);

        if ($this->getTerm()) {
            $this->makeRequest(self::REQUEST_SEARCH);
            return $this;
        } else {
            return false;
        }
    }
    
    /**
     * max 20 calls per minute
     */
    public function lookup($term = null)
    {
        if ($term) $this->setTerm($term);

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
     * @param $type
     *
     * @throws Exception
     *
     * @return void
     */
    protected function makeRequest($type)
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
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = (string) $apiUrl;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $apiUrl
     */
    public function setUrl(string $url)
    {
        $this->url = (string) $url;
        return $this;
    }
    
    /**
     * @param string $apiUrl
     */
    public function makeUrl($type)
    {
        $this->setUrl('');
        if ($this->getParameters()) {
            switch($type) {
                case self::REQUEST_SEARCH:
                    $this->setUrl($this->getApiUrl().'search?'.http_build_query($this->getParameters()));
                    break;
                case self::REQUEST_LOOKUP:
                    $this->setUrl($this->getApiUrl().'lookup?'.http_build_query($this->getParameters()));
                    break;
            }
        }
        
        return $this;
    }
    
    /**
     * Obtains the needed tokens
     */
    public function makeParameters($type)
    {
        $this->setParameters(null);
        
        switch($type) {
            case self::REQUEST_SEARCH:
                if ($this->getTerm() && $this->getCountry()) {
                    if ($this->getTerm())      $this->setParameter('term',      $this->getTerm());
                    if ($this->getCountry())   $this->setParameter('country',   $this->getCountry());
                    if ($this->getMedia())     $this->setParameter('media',     $this->getMedia());
                    if ($this->getEntity())    $this->setParameter('entity',    $this->getEntity());
                    if ($this->getAttribute()) $this->setParameter('attribute', $this->getAttribute());
                    if ($this->getLimit())     $this->setParameter('limit',     $this->getLimit());
                    if ($this->getLang())      $this->setParameter('lang',      $this->getLang());
                    if ($this->getExplicit())  $this->setParameter('explicit',  ($this->getExplicit()?'Yes':'No'));
                }
                break;
            case self::REQUEST_LOOKUP:
                if ($this->getLookup() && $this->getTerm()) {
                    if ($this->getTerm())      $this->setParameter($this->getLookup(),  $this->getTerm());
                    if ($this->getEntity())    $this->setParameter('entity',            $this->getEntity());
                    if ($this->getLimit())     $this->setParameter('limit',             $this->getLimit());
                }
                break;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasParameters()
    {
        return !empty($this->getParameters());
    }

    /**
     * @return bool
     */
    public function isDebug()
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
     * @return mixed
     */
    public function getResult($key = null, $id = null)
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
                
        if ($id && isset($this->result[$id-1])){
            if ($key) {
                return $this->result[$id-1][$key] ?? '';
            } else {
                return $this->result[$id-1];
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
    
    
    
    


    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm(string $term)
    {
        $this->term = (string) $term;
        return $this;
    }
    
    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(string $country)
    {
        $this->country = (string) $country;
        return $this;
    }
    
    public function getMedia()
    {
        return $this->media;
    }

    public function setMedia(string $media)
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
            $this->media = (string) $media;
        } else {
            $this->media = null;
        }
        return $this;
    }
    
    public function getEntity()
    {
        if ($this->getMedia()) {
            return $this->entity;
        }
    }

    public function setEntity(string $entity)
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
            $this->entity = (string) $entity;
        } else {
            $this->entity = '';
        }
        return $this;
    }
    
    public function getAttribute()
    {
        if ($this->getMedia()) {
            return $this->attribute;
        }
    }

    public function setAttribute(string $attribute)
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
            $this->attribute = (string) $attribute;
        } else {
            $this->attribute = '';
        }
        return $this;
    }
    
    
    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit(int $limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }
    
    public function getLang()
    {
        return $this->lang;
    }

    public function setLang(string $lang)
    {
        $this->lang = (string) $lang;
        return $this;
    }
    
    public function getExplicit()
    {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit)
    {
        $this->explicit = $explicit;
        return $this;
    }
    
    public function getLookup()
    {
        return $this->lookup;
    }

    public function setLookup(string $lookup)
    {
        $keys = [   'id',
                    'amgArtistId',
                    'amgAlbumId',
                    'upc',
                    'amgVideoId',
                    'isbn',
                ];
        if ($lookup && in_array($lookup, $keys)) {
            $this->lookup = (string) $lookup;
        } else {
            $this->lookup = null;
        }
        return $this;
    }

}
