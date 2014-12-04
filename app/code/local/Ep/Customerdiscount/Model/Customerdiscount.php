<?php

class Ep_Customerdiscount_Model_Customerdiscount extends Mage_Rule_Model_Abstract {
	
	protected $_appliedDiscounts;
	
	public function _construct() {
		parent::_construct();
		$this->_init('customerdiscount/customerdiscount');
	}
	public function getConditionsInstance()
	{
		return Mage::getModel('catalogrule/rule_condition_combine');
	}
	public function getActionsInstance()
	{
		return Mage::getModel('rule/action_collection');
	}
	
	public function getFinalPrice($product, $customerId) {
		
		$finalPrice = $product->getFinalPrice();
		
		$pId        = $product->getId();
		$storeId    = $product->getStoreId();
		
		$_product	= Mage::getModel('catalog/product')->load($pId);
		
		$priceHelper = Mage::helper('catalogrule');
		
		$customerRules = Mage::getModel('customerdiscount/customergrid')->getCollection()
		->addFieldToFilter('customer_id', $customerId)
		->getColumnValues('discount_id');
		
		$customerDiscount = Mage::getModel('customerdiscount/customerdiscount')->getCollection()
		->addFieldToFilter('id', $customerRules)
		->setOrder('exec_order', 'ASC')
		->getData();
		
		// Per ogni regola trovata
		foreach($customerDiscount as $discount) {
			// Get the product final price
			$productPrice = $finalPrice;
				
			// Retrieve the serialized rules
			$combine = unserialize($discount['conditions_serialized']);
				
			$aggregator = $combine['aggregator'];
				
			$isProductValidated = $aggregator == 'all' ? true : false;
				
			foreach($combine['conditions'] as $condition) {
				$conditionModel = Mage::getModel($condition['type']);
				$conditionModel->loadArray($condition);
		
				$isProductValidated = $conditionModel->validate($_product);
		
				if($aggregator == 'all') {
					if(!$isProductValidated) break;
				} else {
					if($isProductValidated) break;
				}
		
			}
				
			// If the conditions are satisfied apply the price calculation
			if($isProductValidated) {
				$this->_appliedDiscounts[] = array('discount_type' => $discount['discount_type'], 'discount_value' => $discount['discount_value']);
				$finalPrice = $priceHelper->calcPriceRule($discount['discount_type'], $discount['discount_value'], $productPrice);
			}
				
		}
		return $finalPrice;
	}
	
	
	public function getAppliedDiscount($product, $toString=false) {
		// Reset the $_appliedDiscounts variable
		$this->_appliedDiscounts = array();
		
		if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			$customerData = Mage::getSingleton('customer/session')->getCustomer();
			$customerId = $customerData->getId();
		}
		
		if($product && $customerId)
			$this->getFinalPrice($product, $customerId);

		if(toString) {
			$discountArray = array();
			foreach($this->_appliedDiscounts as $discount) {
				
				$sign = ($discount['discount_value'] > 0 ? '- ' : '+ ');
				
				switch ($discount['discount_type'])	{
					case 'by_fixed':
						$discountArray[] = $sign . Mage::helper('core')->currency(abs($discount['discount_value']), true, false);
						break;
					case 'by_percent':
						$discountArray[] = $sign . abs($discount['discount_value']) . '%';
						break;
					case 'to_fixed':
						$discountArray[] = '= '. Mage::helper('core')->currency(abs($discount['discount_value']), true, false);
						break;
					case 'to_percent':
						$discountArray[] = '= ' . abs($discount['discount_value']) . '%';
						break;
				}
			}
			
			$discountString = implode(', ', $discountArray);
			return $discountString;
		} else {
			return $this->_appliedDiscounts;
		}
	}
	
	
	/*
	 * 
	 * a:7:{
	 * 	s:4:"type";s:34:"catalogrule/rule_condition_combine";
	 * 	s:9:"attribute";N;
	 * 	s:8:"operator";N;
	 * 	s:5:"value";s:1:"1";
	 * 	s:18:"is_value_processed";N;
	 * 	s:10:"aggregator";s:3:"all";
	 * 	s:10:"conditions";a:1:{
	 * 		i:0;a:5:{
	 * 			s:4:"type";s:34:"catalogrule/rule_condition_product";
	 * 			s:9:"attribute";s:3:"sku";
	 * 			s:8:"operator";s:2:"==";
	 * 			s:5:"value";s:21:"251100600025000000000";
	 * 			s:18:"is_value_processed";b:0;
	 * 		}
	 * 	}
	 * }
	 */
}
