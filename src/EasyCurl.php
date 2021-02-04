<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace klebervmv;

/**
 * Description of EasyCurl
 *
 * @author klebertonvilela
 */
class EasyCurl {

    private $curlInit;
    private $curlOpt;
    private $error;
    private $httpCode;
    private $result;
    private $contentType;

    /**
     * EasyCurl constructor.
     * @param string $baseUrl
     * @param bool|bool $sslVerify
     * @param string|string $contentType
     */
    public function __construct(string $baseUrl, bool $sslVerify = true, string $contentType = "json") {

        $this->curlOpt = new \stdClass();
        $this->curlOpt->baseUrl = $baseUrl;
        $this->curlOpt->sslFerify = $sslVerify;
        $this->contentType = $contentType;
        $this->resetHeader();

        $this->curlInit = curl_init();
    }

    /**
     *
     * @param string $Method GET,POST,PUT,DELETE
     * @param string $endPoint /endpoint
     * @param  $postFields array ou stdClass, as it will be converted into a Json
     */
    public function render(string $Method, string $endPoint, $postFields = null): EasyCurl {

        $this->curlOpt->method = $Method;
        $this->curlOpt->endPoint = $endPoint;
        $this->curlOpt->postField = (!empty($postFields)) ? (($this->contentType === "json") ? json_encode($postFields) : $postFields) : null;

        return $this;
    }

    /**
     *
     * @param string $header string for header in "key: value" format
     * can be called several times as the value entered will be inserted into an array
     */
    public function setHeader(string $header): EasyCurl {
        $this->curlOpt->header[] = $header;
        return $this;
    }

    public function setContentType(string $contentType): EasyCurl {
        $this->contentType = $contentType;
        return $this;
    }

    public function resetHeader(): EasyCurl {
        unset($this->curlOpt->header);
        $ct = ($this->contentType === "json") ? "application/json" : (($this->contentType === "xml") ? "text/xml;charset=UTF-8" : "");
        $this->curlOpt->header[] = (!empty($ct)) ? "Content-Type:" . $ct : "";

        return $this;
    }

    public function send() {

        $this->curlOpt->baseUrl = (substr($this->curlOpt->baseUrl, -1) === "/") ? substr_replace($this->curlOpt->baseUrl, "", -1, 1) : $this->curlOpt->baseUrl;

        $separator = (substr($this->curlOpt->endPoint, 0, 1) === "/") ? "" : "/";
        $options = array(CURLOPT_URL => $this->curlOpt->baseUrl . $separator . $this->curlOpt->endPoint,
            CURLOPT_CUSTOMREQUEST => $this->curlOpt->method,
            CURLOPT_POSTFIELDS => $this->curlOpt->postField,
            CURLOPT_HTTPHEADER => $this->curlOpt->header,
            CURLOPT_SSL_VERIFYPEER => $this->curlOpt->sslFerify,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($this->curlInit, $options);

        $responseData = curl_exec($this->curlInit);

        if (curl_errno($this->curlInit)) {
            $this->error = curl_error($this->curlInit);
            return $this;
        }

        $this->httpCode = curl_getinfo($this->curlInit)['http_code'];
        $this->result = $responseData;

        if ($this->contentType === "json") {
            $this->jsDecode();
        }

        return $this;
    }

    function jsDecode() {
        $this->result = json_decode($this->result, true);
    }

    function getHttpCode() {
        return $this->httpCode;
    }

    function getResult() {
        return $this->result;
    }

    function getError() {
        return $this->error;
    }

}
