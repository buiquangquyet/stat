<?php
namespace common\components\Cambridge;
/*
 * Copyright (c) 2012, IDM
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted
 * provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright notice, this list of
 *       conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice, this list
 *       of conditions and the following disclaimer in the documentation and/or other materials
 *       provided with the distribution.
 *     * Neither the name of the IDM nor the names of its contributors may be used to endorse or
 *       promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Author: Arnaud de Bossoreille
 */

interface SkPublishAPIRequestHandler {

    public function prepareGetRequest($curl, $uri, &$headers);

}

class SkPublishAPI {

    function __construct($baseUrl, $accessKey) {
        $this->setBaseUrl($baseUrl);
        $this->setAccessKey($accessKey);
    }

    public function getAccessKey() {
        return $this->accessKey;
    }

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function getDictionaries() {
        $curl = $this->prepareGetRequest($this->baseUrl."dictionaries");
        $response = curl_exec($curl);
        return $response;
    }

    public function getDictionary($dictionaryCode) {
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $curl = $this->prepareGetRequest($this->baseUrl."dictionaries/".$dictionaryCode);
        $response = curl_exec($curl);
        return $response;
    }

    public function getEntry($dictionaryCode, $entryId, $format) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/entries/';
        $uri .= urlencode($entryId);
        $c = '?';
        if($format) {
            if(!$this->isValidEntryFormat($format))
                return null;
            $uri .= $c.'format='.$format;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getEntryPronunciations($dictionaryCode, $entryId, $lang = null) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/entries/';
        $uri .= urlencode($entryId);
        $uri .= '/pronunciations';
        $c = '?';
        if($lang) {
            if(!$this->isValidEntryLang($lang))
                return null;
            $uri .= $c.'lang='.$lang;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getNearbyEntries($dictionaryCode, $entryId, $entryNumber = null) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/entries/';
        $uri .= urlencode($entryId);
        $uri .= '/nearbyentries';
        $c = '?';
        if($entryNumber) {
            $uri .= $c.'entrynumber='.$entryNumber;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getRelatedEntries($dictionaryCode, $entryId) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/entries/';
        $uri .= urlencode($entryId);
        $uri .= '/relatedentries';
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getRequestHandler() {
        return $this->requestHandler;
    }

    public function getThesaurusList($dictionaryCode) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/topics/';
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getTopic($dictionaryCode, $thesName, $topicId) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/topics/';
        $uri .= urlencode($thesName);
        $uri .= '/';
        $uri .= urlencode($topicId);
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getWordOfTheDay($dictionaryCode = null, $day = null, $format = null) {
        $uri = $this->baseUrl;
        if($dictionaryCode) {
            if(!$this->isValidDictionaryCode($dictionaryCode))
                return null;
            $uri .= 'dictionaries/'.$dictionaryCode.'/';
        }
        $uri .= 'wordoftheday';
        $c = '?';
        if($day) {
            if(!$this->isValidWotdDay($day))
                return null;
            $uri .= $c.'day='.$day;
            $c = '&';
        }
        if($format) {
            if(!$this->isValidEntryFormat($format))
                return null;
            $uri .= $c.'format='.$format;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function getWordOfTheDayPreview($dictionaryCode = null, $day = null) {
        $uri = $this->baseUrl;
        if($dictionaryCode) {
            if(!$this->isValidDictionaryCode($dictionaryCode))
                return null;
            $uri .= 'dictionaries/'.$dictionaryCode.'/';
        }
        $uri .= 'wordoftheday/preview';
        $c = '?';
        if($day) {
            if(!$this->isValidWotdDay($day))
                return null;
            $uri .= $c.'day='.$day;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    private function isValidDictionaryCode($code) {
        if(strlen($code) < 1)
            return false;
        for($i = 0; $i < strlen($code); ++$i) {
            $c = substr($code, $i, 1);
            // Make sure no param are injected
            if($c == '/' || $c == '%')
                return false;
            if($c == '*' || $c == '$')
                return false;
        }
        return true;
    }

    private function isValidEntryFormat($format) {
        for($i = 0; $i < strlen($format); ++$i) {
            $c = substr($format, $i, 1);
            # Make sure no param are injected
            if($c == '/' || $c == '%')
                return false;
        }
        return true;
    }

    private function isValidEntryLang($lang) {
        for($i = 0; $i < strlen($lang); ++$i) {
            $c = substr($lang, $i, 1);
            # Make sure no param are injected
            if($c == '/' || $c == '%')
                return false;
        }
        return true;
    }

    private function isValidWotdDay($day) {
        for($i = 0; $i < strlen($day); ++$i) {
            $c = substr($day, $i, 1);
            # Make sure no param are injected
            if($c == '/' || $c == '%')
                return false;
        }
        return true;
    }

    private function prepareGetRequest($uri) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $uri);
        $headers = array();
        $headers[] = "accessKey: ".$this->accessKey;
        if($this->requestHandler) {
            $this->requestHandler->prepareGetRequest($curl, $uri, $headers);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        return $curl;
    }

    public function search($dictionaryCode, $searchWord, $pageSize = null, $pageIndex = null) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/search?q=';
        $uri .= urlencode($searchWord);
        $c = '&';
        if($pageSize) {
            $uri .= $c.'pagesize='.$pageSize;
            $c = '&';
        }
        if($pageIndex) {
            $uri .= $c.'pageindex='.$pageIndex;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function searchFirst($dictionaryCode, $searchWord, $format = null) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/search/first?q=';
        $uri .= urlencode($searchWord);
        $c = '&';
        if($format) {
            if(!$this->isValidEntryFormat($format))
                return null;
            $uri .= $c.'format='.$format;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function didYouMean($dictionaryCode, $searchWord, $entryNumber = null) {
        $uri = $this->baseUrl;
        if(!$this->isValidDictionaryCode($dictionaryCode))
            return null;
        $uri .= 'dictionaries/'.$dictionaryCode.'/search/didyoumean?q=';
        $uri .= urlencode($searchWord);
        $c = '&';
        if($entryNumber) {
            $uri .= $c.'entrynumber='.$entryNumber;
            $c = '&';
        }
        $curl = $this->prepareGetRequest($uri);
        $response = curl_exec($curl);
        return $response;
    }

    public function setAccessKey($accessKey) {
        $this->accessKey = $accessKey;
    }

    public function setBaseUrl($baseUrl) {
        if(substr($baseUrl, -1) == '/')
            $this->baseUrl = $baseUrl;
        else
            $this->baseUrl = $baseUrl."/";
    }

    public function setRequestHandler($requestHandler) {
        $this->requestHandler = $requestHandler;
    }

}

?>
