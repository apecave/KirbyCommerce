<?php

namespace KirbyCommerce;


class Products extends Resource
{
	public static $endpoint = "Products";

    /**
     * [toArray description]
     * @param  [type] $bcproducts [description]
     * @return [type]             [description]
     */
    public static function toArray($bcproducts): array
    {
   		$array = [];
        foreach ($bcproducts as $key => $bcproduct) {
        	$array[$key] = Product::toArray($bcproduct);
        }

        return $array;
    }

    /**
     * [toArray description]
     * @param  [type] $pagesData [description]
     * @return [type]             [description]
     */
    public static function save($pagesData, $kirby): bool
    {
   		$array = [];

        foreach ($pagesData as $key => $pageData) {
        	Product::save($pageData, $kirby);
        }

        return false;
    }
}
