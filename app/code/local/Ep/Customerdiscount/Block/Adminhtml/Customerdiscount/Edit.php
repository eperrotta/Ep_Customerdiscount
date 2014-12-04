<?php

class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'customerdiscount';
        $this->_controller = 'adminhtml_customerdiscount';
        
        $this->_updateButton('save', 'label', Mage::helper('customerdiscount')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('customerdiscount')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

    }

    public function getHeaderText()
    {
        if( Mage::registry('customerdiscount_data') && Mage::registry('customerdiscount_data')->getId() ) {
            return Mage::helper('customerdiscount')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('customerdiscount_data')->getTitle()));
        } else {
            return Mage::helper('customerdiscount')->__('Add Item');
        }
    }
}