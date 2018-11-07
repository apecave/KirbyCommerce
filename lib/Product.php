<?php

namespace ApeCave\KirbyCommerce;


class Product extends Resource
{
	public static $endpoint = "Product";
    
    /**
     * formats the array to play nicer with kirby reserved keys
     * thinks like keys the simply read ID can be remapped here
     * as kirby doesn't like them
     * @param  Array  $product  the product array from bigcommerce api v3
     * @return Array            an array made nicer for kirby
     */
    public static function kirbify($product): Array
    {
    	   	$array = static::change_key($product, 'id', 'product_id');
    		return $array;
    }
}
