<?php
/**
 * Copyright © 2015 Brainvire . All rights reserved.
 */
namespace Brainvire\Storelocator\Block;
use Magento\Framework\UrlFactory;
class BaseBlock extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Brainvire\Storelocator\Helper\Data
     */
	 protected $_devToolHelper;
	 
	 /**
     * @var \Magento\Framework\Url
     */
	 protected $_urlApp;
	 
	 /**
     * @var \Brainvire\Storelocator\Model\Config
     */
    protected $_config;

    /**
     * @param \Brainvire\Storelocator\Block\Context $context
	 * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct( \Brainvire\Storelocator\Block\Context $context,\Brainvire\Storelocator\Model\ResourceModel\Storelocator\Collection $collectionFactory,\Magento\Store\Model\StoreManagerInterface $storeManager
	)
    {
        $this->_devToolHelper = $context->getStorelocatorHelper();
        $this->_collectionFactory = $collectionFactory;
		$this->_config = $context->getConfig();
		$this->_storeManager = $storeManager;
        $this->_urlApp=$context->getUrlFactory()->create();
		parent::__construct($context);
	
    }
	
	/**
	 * Function for getting event details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getEventDetails();
    }

    // public function getCollection()
    // {
    // 	$collection =$this->_collectionFactory->load();
    // 	return $collection;
    // }

    public function getStoreManager()
    {
    	return $this->_storeManager;
    }
	
	/**
     * Function for getting current url
	 * @return string
     */
	public function getCurrentUrl(){
		return $this->_urlApp->getCurrentUrl();
	}
	
	/**
     * Function for getting controller url for given router path
	 * @param string $routePath
	 * @return string
     */
	public function getControllerUrl($routePath){
		
		return $this->_urlApp->getUrl($routePath);
	}
	
	/**
     * Function for getting current url
	 * @param string $path
	 * @return string
     */
	public function getConfigValue($path){
		return $this->_config->getCurrentStoreConfigValue($path);
	}
	
	/**
     * Function canShowStorelocator
	 * @return bool
     */
	public function canShowStorelocator(){
		$isEnabled=$this->getConfigValue('storelocator/module/is_enabled');
		if($isEnabled)
		{
			$allowedIps=$this->getConfigValue('storelocator/module/allowed_ip');
			 if(is_null($allowedIps)){
				return true;
			}
			else {
				$remoteIp=$_SERVER['REMOTE_ADDR'];
				if (strpos($allowedIps,$remoteIp) !== false) {
					return true;
				}
			}
		}
		return false;
	}

	public function storeCollection($id)
    {
        return $this->_collectionFactory->addFieldToFilter('id',$id)->getFirstItem();
    }
	
}
