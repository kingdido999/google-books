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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
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
        if ($response->error)
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
        return $this->item->volumeInfo->title;
    }

    public function getAuthors()
    {
        return $this->item->volumeInfo->authors;
    }

    public function getDescription()
    {
        return $this->item->volumeInfo->description;
    }

    public function getPageCount()
    {
        return $this->item->volumeInfo->pageCount;
    }

    public function getImageLinks()
    {
        return $this->item->volumeInfo->imageLinks;
    }

    public function getSmallThumbnail()
    {
        return $this->getImageLinks()->smallThumbnail;
    }

    public function getThumbnail()
    {
        return $this->getImageLinks()->thumbnail;
    }

    public function getLanguageCode()
    {
        return $this->item->volumeInfo->language;
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
