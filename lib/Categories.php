<?php

namespace ApeCave\KirbyCommerce;



class Categories extends Resource
{
	public static $requestParams = ['GET', 'catalog/categories'] ;

	/**
	 * formats the array to play nicer with kirby reserved keys
	 * thinks like keys the simply read ID can be remapped here
	 * as kirby doesn't like them
	 * @param  Array  $categories the categories array from bigcommerce api v3
	 * @return Array            an array made nicer for kirby
	 */
	public static function kirbify($categories): Array
    {
    	foreach ($categories as $key => $category) {
    		$array[$key] = Category::kirbify($category);
    	}
        return  $array;
    }
}
