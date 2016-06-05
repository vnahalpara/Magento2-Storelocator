<?php
/**
 * Copyright © 2015 Brainvire. All rights reserved.
 */
namespace Brainvire\Storelocator\Model\ResourceModel;

/**
 * Storelocator resource
 */
class Storelocator extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('storelocator_storelocator', 'id');
    }

  
}
