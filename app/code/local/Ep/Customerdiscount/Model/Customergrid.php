<?php

class Ep_Customerdiscount_Model_Customergrid extends Mage_Core_Model_Abstract {
	public function _construct()
	{
		parent::_construct();
		$this->_init('customerdiscount/customergrid');
	}
}