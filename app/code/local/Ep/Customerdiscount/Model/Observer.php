<?php
/**
 * Ep_Customerdiscount
 * Catalog Price rules observer model
 */
class Ep_Customerdiscount_Model_Observer
{
	public function processFrontFinalPrice($observer)
	{
		if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			$customerData = Mage::getSingleton('customer/session')->getCustomer();
			$customerId = $customerData->getId();
		} else {
			return $this;
		}
		
		// Retrieve the product object
		$product    = $observer->getEvent()->getProduct();
		
		if($product && $customerId) {
			// Create a customerDiscount model and get the final price for that product
			$finalPrice = Mage::getModel('customerdiscount/customerdiscount')->getFinalPrice($product, $customerId);
			
			$product->setFinalPrice($finalPrice);
		}
		
		return $this;
		
	}
}