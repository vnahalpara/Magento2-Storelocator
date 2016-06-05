<?php
namespace Brainvire\Storelocator\Block\Adminhtml\Storelocator\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_storelocator_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Storelocator Information'));
    }
}