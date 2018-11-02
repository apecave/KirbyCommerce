<?php

# Scratchpad for API Syntax

	
	KirbyCommerce()->product($bcproduct : object) {
		public function get($id)
		{
			# code...
		}
		public function save($arry)
		{
			# code...
		}
		public function sync($id)
		{
			# code...
			# calls get, then to array, then save
		}
		public function toArray($bcproduct, $subkeys = ['images','anotherkey'])
		{
			# code...
		}
		//default return in $bcproduct->name or error
	}

	KirbyCommerce()->category($bccategory : object) {
		public function get($id)
		{
			# code...
		}
		public function save($arry)
		{
			# code...
		}
		public function sync($id)
		{
			# code...
			# calls get, then to array, then save
		}
		public function toArray($bccategory)
		{
			# code...
		}
		//default return in $bccategory->name or error
	}
	KirbyCommerce()->products($bcproducts : array)
	KirbyCommerce()->categories($bcproducts :array )
