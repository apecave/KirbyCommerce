<?php

namespace ApeCave\KirbyCommerce;



class Products extends Resource
{
	public static $requestParams = ['GET', 'catalog/products',['query' => ['include' => 'images']]] ;

	/**
	 * formats the array to play nicer with kirby reserved keys
	 * thinks like keys the simply read ID can be remapped here
	 * as kirby doesn't like them
	 * @param  Array  $products the products array from bigcommerce api v3
	 * @return Array            an array made nicer for kirby
	 */
	public static function kirbify($products): Array
    {
    	foreach ($products as $key => $product) {
    		$array[$key] = Product::kirbify($product);
    	}
        return  $array;
    }
}
