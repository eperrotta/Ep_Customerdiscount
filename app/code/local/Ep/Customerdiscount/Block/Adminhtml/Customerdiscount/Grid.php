<?php
class Ep_Customerdiscount_Block_Adminhtml_Customerdiscount_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
	public function __construct() {
		parent::__construct();
		$this->setId('customerdiscountGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection() {
		$collection = Mage::getModel('customerdiscount/customerdiscount')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns() {
		$this->addColumn('id', array(
				'header' => Mage::helper('customerdiscount')->__('ID'),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id'
		));
		
		$this->addColumn('discount_type', array(
				'header' => Mage::helper('customerdiscount')->__('Discount Type'),
				'align' => 'left',
				'index' => 'discount_type',
				'type' => 'options',
				'options' => array(
						'fixed' => 'Fisso',
						'perc' => 'Percentuale'
				)
		));
		$this->addColumn('discount_value', array(
				'header' => Mage::helper('customerdiscount')->__('Discount Value'),
				'align' => 'left',
				'index' => 'discount_value'
		));
		
		$this->addColumn('action', array(
				'header' => Mage::helper('customerdiscount')->__('Action'),
				'width' => '100',
				'type' => 'action',
				'getter' => 'getId',
				'actions' => array(
						array(
								'caption' => Mage::helper('customerdiscount')->__('Edit'),
								'url' => array(
										'base' => '*/*/edit'
								),
								'field' => 'id'
						)
				),
				'filter' => false,
				'sortable' => false,
				'index' => 'stores',
				'is_system' => true
		));
		
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction() {
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('customerdiscount_id');
		
		$this->getMassactionBlock()->addItem('delete', array(
				'label' => Mage::helper('customerdiscount')->__('Delete'),
				'url' => $this->getUrl('*/*/massDelete'),
				'confirm' => Mage::helper('customerdiscount')->__('Are you sure?')
		));
		
		return $this;
	}
	
	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array(
				'id' => $row->getId()
		));
	}
}