<?php

/**
 * Class for accessing the OpenStreetMap API, without having to worry
 * about HTTP requests.
 */
class OpenStreetMap
{
    private $baseUri;
    private $apiVersion;
    private $apiBaseUri;

    /**
    * Create a new instance of the OSM API class.
    *
    * @param string $baseUri Base URI for OSM (no trailing slash).
    * @param double $apiVersion API version.
    */
    public function __construct($baseUri, $apiVersion) {
        $this->baseUri = $baseUri;
        $this->apiVersion = $apiVersion;
        $this->apiBaseUri = $this->baseUri . '/' . $this->apiVersion;
    }

    /**
    * Make a request to the OSM API.
    *
    * @param string $path
    * @param array $parameters
    */
    private function getRequest($path, $parameters = array()) {
        $uri = $this->apiBaseUri . $path;

        $params = array();
        foreach ($parameters as $key => $value) {
            $params[] = $key . '=' . $value;
        }

        $uri .= '?' . implode('&', $params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $uri
        ));
        $response = curl_exec($curl);

        $xml = simplexml_load_string($response);
        $json = json_encode($xml);

        return json_decode($json,TRUE);
    }

    /**
     * Get the details of a particular node.
     *
     * @param int $id Node ID.
     * @param array $parameters
     *
     * @return
     */
    public function getNode($id, $parameters = array()) {
        $path = '/node/' . $id;

        $body = $this->getRequest($path, $parameters);

        return $body;
    }
}
