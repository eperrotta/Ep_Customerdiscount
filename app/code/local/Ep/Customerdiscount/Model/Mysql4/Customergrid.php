<?php

class Ep_Customerdiscount_Model_Mysql4_Customergrid extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('customerdiscount/customergrid', 'id');
    }
    
    public function addGridPosition($collection,$manager_id){
    	$table2 = $this->getMainTable();
    	$cond = $this->_getWriteAdapter()->quoteInto('e.entity_id = t2.customer_id','');
    	$collection->getSelect()->joinLeft(array('t2'=>$table2), $cond);
    	$collection->getSelect()->group('e.entity_id');
    	// 		echo $collection->getSelect();
    }
}