<?php
/**
 *
 * Copyright © 2015 Brainvirecommerce. All rights reserved.
 */
namespace Brainvire\Storelocator\Controller\Storelocator;

class Search extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Brainvire\Storelocator\Model\ResourceModel\Storelocator\Collection $collectionFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_collectionFactory = $collectionFactory;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
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

        print_r($collection->getData());
        
    }

    public function getStoreName()
{
    return $this->scopeConfig->getValue(
        'general/store_information/name',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    );
}
}
