<?php
	
	namespace Free;

	use \SpaceLord\Generic;
	use \SpaceLord\GenericList;

	include "generic.php";
	include "genericlist.php";

	include "api.php";

	class Haze extends Generic {
		protected $api;
		protected $format = "json";

		private $_pool;
		private $_password;

		public function __construct($config = array()){
			$this->api = new API();

			$this->_pool = $this->_request();

			return $this->_generatePassword($config);
		}

		private function _request($word = "free"){
			$str = sprintf("https://words.bighugelabs.com/api/2/%s/%s/%s", $this->api->getKey(), $word, $this->format);

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

		private function _resetPool($base = "free"){
			$this->_pool = $this->_request($base);

			return $this;
		}

		private function _generatePassword($config){
			$list = new GenericList();
			$list->push($this->_getRandomWordFromPool());

			$i = $list->length;
			while($config["length"] > $i){
				$this->_resetPool($list->indexOf($i));

				$list->push($this->_getRandomWordFromPool());
				
				$config["length"]--;
				$i++;
			}

			$this->_password = $list->join($config["separator"], true);

			return $this->_password;
		}

		public function toString(){
			return $this->_password;
		}
	}

?>