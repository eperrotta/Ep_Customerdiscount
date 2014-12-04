<?php

class Ep_Customerdiscount_Model_Mysql4_Customerdiscount_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	public function _construct() {
		parent::_construct();
		$this->_init('customerdiscount/customerdiscount');
	}
}