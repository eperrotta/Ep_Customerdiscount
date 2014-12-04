<?php

/**
 * Catalog Rule Product Condition data model
 */
class Ep_Customerdiscount_Model_Override_CatalogRule_Rule_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product
{
	
	protected $_childrenCategories;
	
    /*
     * Fix for version 1.8.0.0, where you can’t use any globally-scoped attribute in 
     * a condition of a catalog rule without breaking it.
     */
    protected function _getAttributeValue($object)
    {
    	$storeId = $object->getStoreId();
    	$defaultStoreId = Mage_Core_Model_App::ADMIN_STORE_ID;
    	$productValues = isset($this->_entityAttributeValues[$object->getId()]) ? $this->_entityAttributeValues[$object->getId()] : array($defaultStoreId => $object->getData($this->getAttribute()));
    	$defaultValue = isset($productValues[$defaultStoreId]) ? $productValues[$defaultStoreId] : null;
    	$value = isset($productValues[$storeId]) ? $productValues[$storeId] : $defaultValue;
    
    	$value = $this->_prepareDatetimeValue($value, $object);
    	$value = $this->_prepareMultiselectValue($value, $object);
    	return $value;
    }
    
    
    public function validate(Varien_Object $object)
    {
    	$attrCode = $this->getAttribute();
    	
    	// Changed the category evaluation to consider all the subcategories
    	if ('category_ids' == $attrCode) {
    		
    		// Retrieve all the categories in the condition
    		$categoriesIds = $this->getData('value');
    		
    		// Parse the categories as an array
    		if ($this->isArrayOperatorType() && is_string($categoriesIds)) {
    			$categoriesIds = preg_split('#\s*[,;]\s*#', $categoriesIds, null, PREG_SPLIT_NO_EMPTY);
    		}
    		
    		// Reset the children categories array
    		$this->_childrenCategories = array();
    		
    		foreach($categoriesIds as $catId) {
    			$this->getChildCategories($catId);
    		}
    		
    		$categoryString = implode(', ', $this->_childrenCategories);
    		$this->setData('value', $categoryString);
    		
    		$categoriesIds = $object->getAvailableInCategories();
    		return $this->validateAttribute($categoriesIds);
    	}
    	if ('attribute_set_id' == $attrCode) {
    		return $this->validateAttribute($object->getData($attrCode));
    	}
    
    	$oldAttrValue = $object->hasData($attrCode) ? $object->getData($attrCode) : null;
    	$object->setData($attrCode, $this->_getAttributeValue($object));
    	$result = $this->_validateProduct($object);
    	$this->_restoreOldAttrValue($object, $oldAttrValue);
    
    	return (bool)$result;
    }
    
    protected function getChildCategories($categoryId) {
    	if(empty($this->_childrenCategories)) 
    		$this->_childrenCategories = array();
    	
    	$children = Mage::getModel('catalog/category')->getCategories($categoryId);
    	foreach ($children as $category) {
    		$this->_childrenCategories[] = $category->getId();
    		$this->getChildCategories($category->getId());
    	}
    	return $this->_childrenCategories;
    }

}
