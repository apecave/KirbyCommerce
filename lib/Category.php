<?php

namespace ApeCave\KirbyCommerce;


class Category extends Resource
{
	public static $endpoint = "Category";
    
    /**
     * formats the array to play nicer with kirby reserved keys
     * thinks like keys the simply read ID can be remapped here
     * as kirby doesn't like them
     * @param  Array  $category  the category array from bigcommerce api v3
     * @return Array            an array made nicer for kirby
     */
    public static function kirbify($category): Array
    {
            $array = static::change_key($category, 'id', 'category_id');
    		return $array;
    }
}
