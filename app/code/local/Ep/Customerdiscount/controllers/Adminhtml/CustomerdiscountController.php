
<?php
class Ep_Customerdiscount_Adminhtml_CustomerdiscountController extends Mage_Adminhtml_Controller_Action {
	
	/* Init action has to be always present - It defines the active menu and creates breadcrumbs */
	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('customerdiscount/customerdiscount')->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}
	
	
	/* Default action - It has to show the list of inserted discounts */
	public function indexAction() {
		$this->_initAction()->renderLayout();
	}
	
	
	/* NEW action basically forward to EDIT action */
	public function newAction() {
		$this->_forward('edit');
	}
	
	
	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		
		$model = Mage::getModel('customerdiscount/customerdiscount')->load($id);
		
		$model->getConditions()->setJsFormObject('rule_conditions_fieldset');
		Mage::register('current_promo_catalog_rule', $model);
		
		if($model->getId() || $model->getId() == 0) {
			
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			
			if(!empty($data)) {
				$model->setData($data);
			}
			
			Mage::register('customerdiscount_data', $model);
			
			if($model->getEntityId()) {
				$fieldset->addField('entity_id', 'hidden', array(
						'name' => 'entity_id'
				));
			}
			
			$this->loadLayout();
			$this->_setActiveMenu('customerdiscount/items');
			
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
			
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			$this->_addContent($this->getLayout()->createBlock('customerdiscount/adminhtml_customerdiscount_edit'))->_addLeft($this->getLayout()->createBlock('customerdiscount/adminhtml_customerdiscount_edit_tabs'));
			
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customerdiscount')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	
	
	public function saveAction() {
		if($data = $this->getRequest()->getPost()) {
			
			$model = Mage::getModel('customerdiscount/customerdiscount');
			
			if ($id = $this->getRequest()->getParam('id')) {
				$model->load($id);
			}
			
			$data['conditions'] = $data['rule']['conditions'];
			unset($data['rule']);
			$model->loadPost($data);
			
			Mage::getSingleton('adminhtml/session')->setPageData($model->getData());
			$mdata = $model->getData();
			
			try {
				$model->save();

				$customerdiscount_id = $model->getId();
				
				// If is set a relation with any customer...
				if(isset($data ['relation'])) {
					
					$customers = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['relation']['customers']);
					
					$collection = Mage::getModel('customerdiscount/customergrid')->getCollection();
					$collection->addFieldToFilter('discount_id', $customerdiscount_id);
					foreach( $collection as $obj) {
						$obj->delete();
					}
					foreach( $customers as $key => $value) {
						$model2 = Mage::getModel('customerdiscount/customergrid');
						$model2->setDiscountId($customerdiscount_id);
						$model2->setCustomerId($key);
						$model2->save();
					}
				}
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('customerdiscount')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				if($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array(
							'id' => $model->getId()
					));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array(
						'id' => $this->getRequest()->getParam('id')
				));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('manager')->__('Unable to find item to save'));
		$this->_redirect('*/*/');
	}
	
	public function deleteAction() {
		if($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('customerdiscount/customerdiscount');
				
				$model->setId($this->getRequest()->getParam('id'))->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array(
						'id' => $this->getRequest()->getParam('id')
				));
			}
		}
		$this->_redirect('*/*/');
	}
	
	public function massDeleteAction() {
		$customerdiscountIds = $this->getRequest()->getParam('customerdiscount_id');
		if(! is_array($customerdiscountIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				foreach( $customerdiscountIds as $customerdiscountId) {
					$cd = Mage::getModel('customerdiscount/customerdiscount')->load($customerdiscountId);
					$cd->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($customerdiscountIds)));
			} catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public function customergridAction() {
		$this->loadLayout();
		$this->getLayout()->getBlock('customer.grid')->setCustomers($this->getRequest()->getPost('customers', null));
		$this->renderLayout();
	}
	
	public function customergridlistAction() {
		$this->loadLayout();
		$this->getLayout()->getBlock('customer.grid')->setCustomers($this->getRequest()->getPost('customers', null));
		$this->renderLayout();
	}
}