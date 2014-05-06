<?php
	
	namespace Free;

	use \SpaceLord\Generic;
	use \SpaceLord\GenericList;

	require "generic.php";
	require "genericlist.php";
	require "api.php";

	class Haze {
		/**
		 * Stores configuration data
		 * @var Generic object
		 */
		private $_config;

		/**
		 * Stores the API class data
		 * @var API object
		 */
		private $_api;

		/**
		 * The pool of words used to generate the password
		 * @var array
		 */
		private $_pool = array();

		/**
		 * The final password
		 * @var string
		 */
		private $_password;

		/**
		 * Initialize the object and build the initial pool of words
		 * @param array $config Configuration options
		 */
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

		/**
		 * Request a new pool from the API service
		 * @param  string $word A word to base the entire pool of words off of
		 * @return array
		 */
		private function _request($word = "free"){
			$str = sprintf("https://words.bighugelabs.com/api/2/%s/%s/%s", $this->_api->getKey(), $word, $this->_config->get("format"));

			try {
				if($word != null){
					if($_request = @file_get_contents($str)){

						if(strlen($_request) > 0){
							return (array) json_decode($_request);
						}
					}else {
						throw new \Exception("Rate Limit Exceeded!  No more API requests for a while.");
					}
				}
			}catch(\Exception $e){
				die($e->getMessage());
			}
		}

		/**
		 * Process the pool and pull a random word from it
		 * @return string
		 */
		private function _getRandomWordFromPool(){
			if(sizeof($this->_pool) > 0){
				//flatten data array into a searchable format
				$flattened = array();

				foreach($this->_pool as $d0){
					foreach($d0 as $key => $value){
						$flattened = array_merge($flattened, $value);
					}
				}

				//ensure there are no duplicates
				$flattened = array_unique($flattened);

				//choose a random value from the flattened array and process
				//it to remove spaces (if there are any)
				$random_word = $flattened[rand(0, (sizeof($flattened) - 1))];
				
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

		/**
		 * Generate the password from the length provided in the configuration
		 * settings and join the words together with a separator (if required)
		 * @return string  The final password
		 */
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

		/**
		 * Override method
		 * @return string  The final password
		 */
		public function __toString(){
			return $this->_password;
		}
	}

?>