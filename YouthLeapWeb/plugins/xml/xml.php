<?php

	class xml {
		private $xml;

		public function __construct($datafile){
			$path = SITE_ROOT . "/" . $datafile;
			$fp = @fopen($path, "r");
			if ($fp != null) {
				$xmlstr = @fread($fp, filesize($path));
				@fclose($fp);

				$this->xml = new SimpleXMLElement($xmlstr);
			}
		}

		public function __get($prop) {
			return $this->xml->$prop;
		}

	};