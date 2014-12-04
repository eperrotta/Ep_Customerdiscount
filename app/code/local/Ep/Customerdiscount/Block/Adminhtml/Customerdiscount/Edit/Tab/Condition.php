<?php
class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount_Edit_Tab_Condition extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$model = Mage::registry('current_promo_catalog_rule');
		
		//$form = new Varien_Data_Form(array('id' => 'edit_form1', 'action' => $this->getData('action'), 'method' => 'post'));
		$form = new Varien_Data_Form();
		
		$form->setHtmlIdPrefix('rule_');
		
		$renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
		->setTemplate('promo/fieldset.phtml')
		->setNewChildUrl(Mage::helper("adminhtml")->getUrl('adminhtml/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));
		
		$fieldset = $form->addFieldset('conditions_fieldset', array(
				'legend'=>Mage::helper('catalogrule')->__('Conditions (leave blank for all products)'))
		)->setRenderer($renderer);
		
		$fieldset->addField('conditions', 'text', array(
				'name' => 'conditions',
				'label' => Mage::helper('catalogrule')->__('Conditions'),
				'title' => Mage::helper('catalogrule')->__('Conditions'),
				'required' => true,
		))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));
		
		$form->setValues($model->getData());
		
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
}