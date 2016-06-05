<?php
/**
 * Copyright © 2015 Brainvire . All rights reserved.
 */
namespace Brainvire\Storelocator\Block\Storelocator;
use Brainvire\Storelocator\Block\BaseBlock;
use Magento\Framework\View\Element\Template;

class Index extends BaseBlock
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
        $data = $this->getRequest()->getPost();
        $collection = $this->_collectionFactory->addFieldToFilter('status',1);
        if($data['country'])
        {
            $collection->addFieldToFilter('country',$data['country']);
        }
        if($data['state'])
        {
            $collection->addFieldToFilter('state',$data['state']);
        }
        if($data['city'])
        {
            $collection->addFieldToFilter('city',$data['city']);
        }
        if($data['zipcode'])
        {
            $collection->addFieldToFilter('zipcode',$data['zipcode']);
        }
            //->setOrder('id', 'DESC');
        $this->setCollection($collection);
    }
 
   /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
           'Magento\Theme\Block\Html\Pager',
           'brainvire.storelocator.list.pager'
        );
        $pager->setLimit(2)
            ->setShowAmounts(false)
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
 
        return $this;
    }
 
   /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
	
}
