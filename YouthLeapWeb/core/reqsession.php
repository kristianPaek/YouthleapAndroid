<?php

	class reqsession
	{
		private $_props;
		private $_viewHelper;
		private $session_prefix;
		private $clear_session;

		public function __construct($session_prefix, $clear_session=false)
		{
			$this->session_prefix = $session_prefix;
			$this->clear_session = $clear_session;

			$this->_viewHelper = new viewHelper($this);
			$this->_props = array();
		}

		public function __get($prop) {
			global $_REQUEST;

			if ($prop == "props")
				return $this->_props;
			else {
				if (!array_key_exists($prop, $this->_props)) {
					$exists = array_key_exists($prop, $_REQUEST);
					$val = $exists ? $_REQUEST[$prop] : null;
					if ($val == null) {
						// for single checkbox
						$val = isset($_REQUEST[$prop . "_@@@"]) ? $_REQUEST[$prop . "_@@@"] : null;
						if (is_array($val)) {
							$val = _arr2bits($val) . "";
							$exists = true;
						}
					}
					if ($val !== null) {
						_session($this->session_prefix . $prop, $val);
					}
					else if ($exists || $this->clear_session) {
						_session($this->session_prefix . $prop, null);
					}
					$this->_props[$prop] = _session($this->session_prefix . $prop);
				}

				return $this->_props[$prop];
			}
		}

		public function __set($prop, $val) {
			$this->_props[$prop] = $val;
		}

		public function __call($method, $params) {
			if (method_exists($this->_viewHelper, $method)) {
				call_user_func_array(array($this->_viewHelper, $method), $params);
			}
		}
	};
?>