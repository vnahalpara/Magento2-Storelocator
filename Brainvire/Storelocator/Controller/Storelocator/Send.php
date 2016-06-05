<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brainvire\Storelocator\Controller\Storelocator;

class Send extends \Magento\Contact\Controller\Index
{
    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$store = $objectManager->get('\Brainvire\Storelocator\Block\BaseBlock')->storeCollection($post['store_id']);
		$storeEmail = $store->getEmail();
        if (!$post) {
            $this->_redirect('storelocator/storelocator/view',array('id'=>$post['store_id']));
            return;
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $error = false;

            if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                $error = true;
            }
            if (\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            if(!$storeEmail)
            {
            	$storeEmail = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope);
            }
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($storeEmail)
                ->setReplyTo($post['email'])
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->_redirect('storelocator/storelocator/view',array('id'=>$post['store_id']));
            return;
        } catch (\Exception $e) {
        	echo $e->getMessage();
        	die;
            $this->inlineTranslation->resume();
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect('storelocator/storelocator/view',array('id'=>$post['store_id']));
            return;
        }
    }
}
