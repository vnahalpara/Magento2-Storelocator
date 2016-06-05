<?php
/**
 * Copyright © 2015 Brainvire . All rights reserved.
 */
namespace Brainvire\Storelocator\Block\Storelocator;
use Brainvire\Storelocator\Block\BaseBlock;
use Magento\Framework\View\Element\Template;

class View extends BaseBlock
{
 
   /**
    * @param Template\Context $context
    * @param NewsFactory $newsFactory
    * @param array $data
    */
   
 
   /**
     * Set news collection
     */
    protected  function _construct()
    {
        parent::_construct();
        $id = $this->getRequest()->getParam('id');
        $collection = $this->_collectionFactory->addFieldToFilter('id',$id)->getFirstItem();
        $this->setCollection($collection);
    }
 
	
}
