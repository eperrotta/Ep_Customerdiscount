<?php
class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
	public function __construct() {
		parent::__construct();
		$this->setId('customerdiscount_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('customerdiscount')->__('Item Information'));
	}
	protected function _beforeToHtml() {
		$this->addTab('form_section', array(
				'label' => Mage::helper('customerdiscount')->__('Item Information'),
				'title' => Mage::helper('customerdiscount')->__('Item Information'),
				'content' => $this->getLayout()->createBlock('customerdiscount/adminhtml_customerdiscount_edit_tab_form')->toHtml()
		));
		
		$this->addTab('grid_section', array(
				'label' => Mage::helper('customerdiscount')->__('Customer List'),
				'title' => Mage::helper('customerdiscount')->__('Customer List'),
				'url' => $this->getUrl('*/*/customergrid', array(
						'_current' => true
				)),
				'class' => 'ajax'
		));
		
		$this->addTab('condition_section', array(
				'label' => Mage::helper('customerdiscount')->__('Conditions'),
				'title' => Mage::helper('customerdiscount')->__('Conditions'),
				'content' => $this->getLayout()->createBlock('customerdiscount/adminhtml_customerdiscount_edit_tab_condition')->toHtml()
		));
		
		
		return parent::_beforeToHtml();
	}
}