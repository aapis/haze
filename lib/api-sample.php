<?php

	namespace Free;

	class API {
		/**
		 * Your private API key from https://words.bighugelabs.com/
		 * Private so it is read-only
		 * @var string
		 */
		private $_key = "";

		/**
		 * Get the read-only API key
		 * @return string
		 */
		public function getKey(){
			return $this->_key;
		}
	}

?>