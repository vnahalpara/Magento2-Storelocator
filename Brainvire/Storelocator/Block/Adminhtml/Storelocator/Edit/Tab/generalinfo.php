<?php
namespace Brainvire\Storelocator\Block\Adminhtml\Storelocator\Edit\Tab;
class generalinfo extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
		/* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('storelocator_storelocator');
		$isElementDisabled = false;
        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
        $objectManager = $bootstrap->getObjectManager();
        $countryHelper = $objectManager->get('Magento\Directory\Model\Config\Source\Country');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('General Info')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

		$fieldset->addField(
            'store_view',
            'multiselect',
            [
                'name' => 'store_view[]',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
		$fieldset->addField(
            'store_name',
            'text',
            array(
                'name' => 'store_name',
                'class' => 'letters-only',
                'label' => __('Store name'),
                'title' => __('Store name'),
                'required' => true,
            )
        );
		$fieldset->addField(
            'phone',
            'text',
            array(
                'name' => 'phone',
                'class' => 'validate-phoneStrict',
                'label' => __('Phone'),
                'title' => __('Phone'),
                'required' => true,
            )
        );
		$fieldset->addField(
            'fax',
            'text',
            array(
                'name' => 'fax',
                'class' => 'validate-fax',
                'label' => __('Fax'),
                'title' => __('Fax'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'website',
            'text',
            array(
                'name' => 'website',
                'class' => 'validate-url',
                'label' => __('Website'),
                'title' => __('Website'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'email',
            'text',
            array(
                'name' => 'email',
                'class' => 'validate-email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true,
            )
        );
		$fieldset->addField(
            'store_logo',
            'image',
            array(
                'class' => 'required-file',
                'name' => 'store_logo',
                'label' => __('Store Logo/Image'),
                'title' => __('Store Logo/Image'),
                'required' => true,
            )
        );
		$fieldset->addField(
            'opening_hours',
            'text',
            array(
                'name' => 'opening_hours',
                'label' => __('Opening Hours'),
                'title' => __('Opening Hours'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'street_address',
            'text',
            array(
                'name' => 'street_address',
                'label' => __('Street Address'),
                'title' => __('Street Address'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'city',
            'text',
            array(
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'state',
            'text',
            array(
                'name' => 'state',
                'label' => __('State'),
                'title' => __('State'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'country',
            'select',
            array(
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                'values' => $countryHelper->toOptionArray(),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'zipcode',
            'text',
            array(
                'name' => 'zipcode',
                'label' => __('Zipcode'),
                'title' => __('Zipcode'),
                /*'required' => true,*/
            )
        );
		/*{{CedAddFormField}}*/
        
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();   
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('General Info');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
