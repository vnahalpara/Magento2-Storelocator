<?php
namespace Brainvire\Storelocator\Block\Adminhtml\Storelocator;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
	protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
		\Brainvire\Storelocator\Model\ResourceModel\Storelocator\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
		
		$this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
		
        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
       
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
		try{
			
			
			$collection =$this->_collectionFactory->load();

		  

			$this->setCollection($collection);

			parent::_prepareCollection();
		  
			return $this;
		}
		catch(Exception $e)
		{
			echo $e->getMessage();die;
		}
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog_product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();
        $countryHelper = $objectManager->get('Magento\Directory\Model\Config\Source\Country');
        $country = $countryHelper->toOptionArray();
        foreach ($country as $value) {
            if($value["value"])
            {
                $options[$value["value"]] = $value["label"];
            }
        }

        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
		$this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'class' => 'status',
                'type' => 'options',
                'options' => array(
                    1 => 'Enable',
                    0 => 'Disable',
                    ),
            ]
        );
		$this->addColumn(
            'store_name',
            [
                'header' => __('Store Name'),
                'index' => 'store_name',
                'class' => 'store_name'
            ]
        );
		$this->addColumn(
            'phone',
            [
                'header' => __('Phone'),
                'index' => 'phone',
                'class' => 'phone'
            ]
        );
		$this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'class' => 'email'
            ]
        );
		$this->addColumn(
            'city',
            [
                'header' => __('city'),
                'index' => 'city',
                'class' => 'city'
            ]
        );
		$this->addColumn(
            'state',
            [
                'header' => __('state'),
                'index' => 'state',
                'class' => 'state'
            ]
        );
        // $status = $countryHelper->toOptionArray();
        // foreach ($status as $value) {
        //     $options[$value["status"]] = $value["label"];
        // }
		$this->addColumn(
            'country',
            [
                'header' => __('country'),
                'index' => 'country',
                'class' => 'country',
                'type' => 'options',
                'options' => $options,
            ]
        );
		$this->addColumn(
            'zipcode',
            [
                'header' => __('zipcode'),
                'index' => 'zipcode',
                'class' => 'zipcode'
            ]
        );
		/*{{CedAddGridColumn}}*/

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

     /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('storelocator/*/massDelete'),
                'confirm' => __('Are you sure?')
            )
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('storelocator/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'storelocator/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
