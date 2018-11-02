<?php

namespace KirbyCommerce;

use Bigcommerce\Api\Client as Bigcommerce;


Bigcommerce::configure(option('bcconfig'));


abstract class Resource
{
	public static $endpoint = "null";
	public static $kirby;


	/**
	 * gets a resource endpoint
	 * @return [type] [description]
	 */
    public static function get($args = null) : array
    {
    	$bc = new Bigcommerce;
    	$method = 'get'.static::$endpoint;
    	
    	$resource = $bc->$method($args);

    	// ensure return is array of given resources
    	// even if its a singleton
    	$array = is_array($resource) ? $resource : [$resource];

        return  $array;
    }

    public static function put(): bool
    {
    	//foreach -> put logic
        return  false;
    }

    /**
     * [save description]
     * @param  [type] $pagesData [description]
     * @return [type]            [description]
     */
    public abstract static function save($pagesData, $kirby);

    /**
     * [sync description]
     * @return [type] [description]
     */
    public static function sync(): bool
    {
    	$resource = static::get();
    	$array = static::toArray($resource);
    	static::save($array);

        return  false;
    }

    /**
     * [toArray description]
     * @return [type] [description]
     */
    public abstract static function toArray($resource);


}
