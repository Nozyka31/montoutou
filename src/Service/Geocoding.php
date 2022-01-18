<?php



namespace App\Service;

/**
 * Geocoding
 * @Author: Adnan Hussain Turki
 * @Website: www.myphpnotes.com
=====================================
   PROPERTY OF WWW.MYPHPNOTES.COM
 */

class Geocoding
{
    protected $api_key;
    protected $debug;
    protected $callurl = "https://maps.googleapis.com/maps/api/geocode/json";
    function __construct()
    {
        $this->api_key = "AIzaSyAWz2NqbKTUjl1thF9BsWLTU7mW5EJ5RvY";
        $this->debug = 0;    
    }
    public function request($url, $parameters)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($parameters));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        return $result;
    }
    public function getAddress($latitude, $longitude)
    {
        $data = [
            'latlng' => "$latitude,$longitude",
            'key' => $this->api_key
        ];
        $addressData = $this->request($this->callurl, $data);
        return (json_decode($addressData)->results[0]->formatted_address);
    }
    public function getCoordinates($address, $id)
    {
        $data = [
            'address' => $address,
            'key' => $this->api_key
        ];
        $addressData = $this->request($this->callurl, $data);
        $location = json_decode($addressData)->results[0]->geometry->location;
        return [ 'latitude' => $location->lat, 'longitude' => $location->lng, 'id' =>$id];
    }
}