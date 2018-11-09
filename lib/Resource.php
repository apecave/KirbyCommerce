<?php

namespace ApeCave\KirbyCommerce;

use GuzzleHttp\Client;
use Kirby\Data\Json;


abstract class Resource
{
	public static $requestParams = null;

	/**
     * gets a resource endpoint
     * @param  Array $args request parameters overrides
     * @return Array the resrouce as a php array
     */
    public static function get($args = null) : Array
    {
        $bcconfig = option('bcconfig');
        $client = new Client([
            'base_uri' => 'http://api.bigcommerce.com/stores/'.$bcconfig['store_hash'].'/v3/',
            'timeout'  => 2.0,
            'headers' => [
                'X-store_hash' => $bcconfig['store_hash'],
                'X-Auth-Client' => $bcconfig['client_id'],
                'X-Auth-Token' => $bcconfig['auth_token']

            ]
        ]);
        $args = $args ?? static::$requestParams;
        $response = $client->request(...$args); //splat the array to unpack args https://wiki.php.net/rfc/argument_unpacking
        $json = $response->getBody()->getContents();
        $resource = Json::decode( $json );
        $resourceArray = $resource['data'];

        // if ($url = $resource['meta']['pagination']['links']['next'] ?? false) {
        //      dump($resource); die;
        // }
       
        $array = static::kirbify($resourceArray);

        return $array;

    }

    public static function put(): Boolean   
    {
    	//foreach -> put logic
        return  false;
    }

    /**
     * changes a key in an array while keeping it in place
     * @param  Array  $array   the array to be manipulated
     * @param  String $old_key key to be replaced
     * @param  String $new_key key to replace with
     * @return Array           the new array with changed keys
     */
    public static function change_key( $array, $old_key, $new_key ) : Array {

        if( ! array_key_exists( $old_key, $array ) )
            return $array;

        $keys = array_keys( $array );
        $keys[ array_search( $old_key, $keys ) ] = $new_key;

        return array_combine( $keys, $array );
    }
}
