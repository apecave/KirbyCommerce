<?php

namespace KirbyCommerce;

use Kirby\Data\Yaml;
use Kirby\Cms\App as Kirby;
$$GLOBALS['a'] = new Kirby([
      'users' => [
          [
              'email' => 'fake@getkirby.com',
              'role' => 'admin'
          ]
      ],
      'user' => 'fake@getkirby.com'
  ]);

class Product extends Resource
{
	public static $endpoint = "Product";
    
    /**
     * [toArray description]
     * @param  [type] $bcproduct [description]
     * @return [type]            [description]
     */
    public static function toArray($bcproduct): array
    {

		$images = [];
		foreach ($bcproduct->images as $key => $image) {
			$image = [
				'image_file' => $image->image_file,
				'zoom_url' => $image->zoom_url,
				'thumbnail_url' => $image->thumbnail_url,
				'standard_url' => $image->standard_url,
			];
			array_push( $images, $image);
		}    	
   		$array = array(
		    'title' => $bcproduct->name,
		    'date'  => $bcproduct->date_modified,
		    'text'  => $bcproduct->description,
		    'slug'  => $bcproduct->custom_url,
		    'sort_order'  => $bcproduct->sort_order,
		    'images' => yaml::encode($images)
		  );

        return $array;
    }

    /**
     * [save description]
     * @param  [type] $pageData [description]
     * @return [type]           [description]
     */
    public static function save($pageData, $kirby): bool
    {
    	   

		try {
		  $kirby->page('blog')->createChild([
		    'content'  => $pageData,
		    'slug' => $pageData['slug'].uniqid(),
		    'isDraft'  => false,
		    'template' => 'article'
		  ]);
		}	catch(DuplicateException $e) {

			  echo $e->getMessage();

			}
        return false;
    }

}
