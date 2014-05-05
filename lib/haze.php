<?php
	
	namespace Free;

	use \SpaceLord\Generic;
	use \SpaceLord\GenericList;

	include "generic.php";
	include "genericlist.php";

	include "api.php";

	class Haze extends Generic {
		private $_config;
		private $_api;
		private $_pool = array();
		private $_password;

		public function __construct($config = array()){
			//setup the configuration object
			$this->_config = new Generic();
			$this->_config->setProperties($config);
			$this->_config->set("format", "json");

			//get the API key and any other API info
			$this->_api = new API();
			//start with a pool based on a default keyword
			$this->_pool = $this->_request();

			$this->_generatePassword();

			return $this->__toString();
		}

		private function _request($word = "free"){
			$str = sprintf("https://words.bighugelabs.com/api/2/%s/%s/%s", $this->_api->getKey(), $word, $this->_config->get("format"));

			if($word != null){
				$_request = file_get_contents($str);

				if(strlen($_request) > 0){
					return (array) json_decode($_request);
				}
			}

			return false;
		}

		private function _getRandomWordFromPool(){
			if(sizeof($this->_pool) > 0){
				//flatten data array into a searchable format
				$flattened = array();

				foreach(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->_pool)) as $k=>$v){
					$flattened[$k] = $v;
				}

				//ensure there are no duplicates
				$flattened = array_unique($flattened);

				//choose a random value from the flattened array and process
				//it to remove spaces (if there are any)
				$random_word = $flattened[rand(0, (sizeof($flattened) - 1))];
				//$random_word = str_replace(" ", "", $random_word);

				return $random_word;
			}

			return null;
		}

		/**
		 * Reset the pool of words based on a "base" word
		 * @param  string $base 
		 * @return array
		 */
		private function _resetPool($base = "free"){
			$this->_pool = $this->_request($base);

			return $this->_pool;
		}

		private function _generatePassword(){
			$list = new GenericList();
			$list->push($this->_getRandomWordFromPool());

			$i = $list->length;
			$len = $this->_config->get("length");
			while($len > $i){
				//base the next random word on the next item in the list
				$this->_resetPool($list->indexOf($i));

				$list->push($this->_getRandomWordFromPool());
				
				$len--;
				$i++;
			}

			$this->_password = $list->join($this->_config->get("separator"), true);

			return $this->_password;
		}

		public function __toString(){
			return $this->_password;
		}
	}

?>