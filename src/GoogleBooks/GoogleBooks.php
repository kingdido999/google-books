<?php namespace GoogleBooks;

class GoogleBooks {

    public $rootUrl;
    public $apiName;
    public $apiVersion;
    public $resourcePath;
    public $baseUrl;
    public $data;

    private $key;

    public function __construct($key)
    {
        $this->rootUrl = 'https://www.googleapis.com';
        $this->apiName = 'books';
        $this->apiVersion = 'v1';
        $this->resourcePath = 'volumes';
        $this->baseUrl = $this->rootUrl . '/' . $this->apiName . '/' . $this->apiVersion . '/' . $this->resourcePath;
        $this->key = $key;
    }

    public function curl($url) {
        // Call to undefined function curl_init().?
        // http://stackoverflow.com/questions/6382539/call-to-undefined-function-curl-init
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data == false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }

        return $data;
    }

    public function searchByISBN($query)
    {
        $url =  $this->baseUrl . '?q=isbn:' . $query . '&key=' . $this->key;
        $json = $this->curl($url);

        // curl error
        if ($json == false)
        {
            return false;
        }

        $response = json_decode($json);

        // error
        if (isset($response->error))
        {
            return false;
        }

        // item not found
        if ($response->totalItems == 0)
        {
            return false;
        }

        $this->item = $response->items[0];

        return true;
    }

    public function getTitle()
    {
        if (isset($this->item->volumeInfo->title))
        {
            return $this->item->volumeInfo->title;
        }

        return null;
    }

    public function getAuthors()
    {
        if (isset($this->item->volumeInfo->authors))
        {
            return $this->item->volumeInfo->authors;
        }

        return null;
    }

    public function getDescription()
    {
        if (isset($this->item->volumeInfo->description))
        {
            return $this->item->volumeInfo->description;
        }

        return null;
    }

    public function getPageCount()
    {
        if (isset($this->item->volumeInfo->pageCount))
        {
            return $this->item->volumeInfo->pageCount;
        }

        return null;
    }

    public function getImageLinks()
    {
        if (isset($this->item->volumeInfo->imageLinks))
        {
            return $this->item->volumeInfo->imageLinks;
        }

        return null;
    }

    public function getSmallThumbnail()
    {
        $imageLinks = $this->getImageLinks();

        if ($imageLinks)
        {
            return $this->getImageLinks()->smallThumbnail;
        }

        return null;
    }

    public function getThumbnail()
    {
        $imageLinks = $this->getImageLinks();

        if ($imageLinks)
        {
            return $this->getImageLinks()->thumbnail;
        }

        return null;
    }

    public function getLanguageCode()
    {
        if (isset($this->item->volumeInfo->language))
        {
            return $this->item->volumeInfo->language;
        }

        return null;
    }

    public function getLanguage()
    {
        switch ($this->getLanguageCode()) {
            case 'en':
                $language = 'English';
                break;

            default:
                $language = null;
                break;
        }

        return $language;
    }

    public function getIsbn10()
    {
        foreach ($this->item->volumeInfo->industryIdentifiers as $isbn)
        {
            if ($isbn->type == 'ISBN_10')
            {
                return $isbn->identifier;
            }
        }

        return null;
    }

    public function getIsbn13()
    {
        foreach ($this->item->volumeInfo->industryIdentifiers as $isbn)
        {
            if ($isbn->type == 'ISBN_13')
            {
                return $isbn->identifier;
            }
        }

        return null;
    }
}
