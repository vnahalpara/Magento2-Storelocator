<?php
namespace Brainvire\Storelocator\Block\Adminhtml;
class Storelocator extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_storelocator';/*block grid.php directory*/
        $this->_blockGroup = 'Brainvire_Storelocator';
        $this->_headerText = __('Storelocator');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}