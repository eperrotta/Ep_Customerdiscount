<?php
/*
 * Form block
 */
class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('customerdiscount_form', array(
				'legend' => Mage::helper('customerdiscount')->__('Discount information')
		));
		
		$fieldset->addField('discount_value', 'text', array(
				'label' => Mage::helper('customerdiscount')->__('Discount amount'),
				'required' => false,
				'name' => 'discount_value'
		));
		
		$fieldset->addField('discount_type', 'select', array(
				'label' => Mage::helper('customerdiscount')->__('Discount type'),
				'name' => 'discount_type',
				'values' => array(
						array(
								'value' => 'to_fixed',
								'label' => Mage::helper('customerdiscount')->__('Imposta prezzo')
						),						
						array(
								'value' => 'to_percent',
								'label' => Mage::helper('customerdiscount')->__('Imposta prezzo in percentuale')
						),						
						array(
								'value' => 'by_fixed',
								'label' => Mage::helper('customerdiscount')->__('Sconto fisso')
						),						
						array(
								'value' => 'by_percent',
								'label' => Mage::helper('customerdiscount')->__('Sconto percentuale')
						)
				)
		));
		
		$fieldset->addField('exec_order', 'text', array(
				'label' => Mage::helper('customerdiscount')->__('Execution order'),
				'required' => false,
				'name' => 'exec_order'
		));
		
		if(Mage::getSingleton('adminhtml/session')->getCustomerdiscountData()) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getCustomerdiscountData());
			Mage::getSingleton('adminhtml/session')->setCustomerdiscountData(null);
		} elseif(Mage::registry('customerdiscount_data')) {
			$form->setValues(Mage::registry('customerdiscount_data')->getData());
		}
		return parent::_prepareForm();
	}
}