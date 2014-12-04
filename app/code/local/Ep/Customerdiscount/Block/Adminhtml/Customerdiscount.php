<?php

class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount extends Mage_Adminhtml_Block_Widget_Grid_Container {
	
	public function __construct() {
		$this->_controller = 'adminhtml_customerdiscount';
		$this->_blockGroup = 'customerdiscount';
		$this->_headerText = Mage::helper('customerdiscount')->__('Discount Manager');
		$this->_addButtonLabel = Mage::helper('customerdiscount')->__('Add Duscount');
		parent::__construct();		
	}
}