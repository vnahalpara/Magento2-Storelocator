<?php
namespace Brainvire\Storelocator\Controller\Adminhtml\Storelocator;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Backend\App\Action
{
    /**
    * @var \Magento\Framework\Image\AdapterFactory
    */
    protected $adapterFactory;
    /**
    * @var \Magento\MediaStorage\Model\File\UploaderFactory
    */
    protected $uploader;
    /**
    * @var \Magento\Framework\Filesystem
    */
    protected $filesystem;
    /**
    * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
    */
    protected $timezoneInterface;
    public function __construct(\Magento\Backend\App\Action\Context $context,\Magento\Framework\Image\AdapterFactory $adapterFactory,\Magento\MediaStorage\Model\File\UploaderFactory $uploader,\Magento\Framework\Filesystem $filesystem)
    {
    $this->adapterFactory = $adapterFactory;
    $this->uploader = $uploader;
    $this->filesystem = $filesystem;
    parent::__construct($context);
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_objectManager->create('Brainvire\Storelocator\Model\Storelocator');
		
   //           if(isset($_FILES['store_logo']['name']) && $_FILES['store_logo']['name'] != '') {
			// 	try {
			// 		    $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory', array('fileId' => 'store_logo'));
			// 			$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
			// 			$uploader->setAllowRenameFiles(true);
			// 			$uploader->setFilesDispersion(true);
			// 			$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
			// 				->getDirectoryRead(DirectoryList::MEDIA);
			// 			//$config = $this->_objectManager->get('Magento\Bannerslider\Model\Banner');
			// 			$result = $uploader->save($mediaDirectory->getAbsolutePath('Storelocator/store_logo'));
			// 			unset($result['tmp_name']);
			// 			unset($result['path']);
			// 			$data['store_logo'] = $result['file'];
			// 	} catch (Exception $e) {
			// 		$data['store_logo'] = $_FILES['store_logo']['name'];
			// 	}
			// }
			// else{
			// 	$data['store_logo'] = $data['store_logo']['value'];
			// } 

            //start block upload image
        if (isset($_FILES['store_logo']) && isset($_FILES['store_logo']['name']) && strlen($_FILES['store_logo']['name'])) 
        {
            /*
            * Save image upload
            */
            try 
            {
                $base_media_path = 'Storelocator/store_logo';
                $uploader = $this->uploader->create(['fileId' => 'store_logo']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $imageAdapter = $this->adapterFactory->create();
                $uploader->addValidateCallback('store_logo', $imageAdapter, 'validateUploadFile');
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $result = $uploader->save($mediaDirectory->getAbsolutePath($base_media_path));
                $data['store_logo'] = $base_media_path.$result['file'];
            } 
            catch (\Exception $e) 
            {
                if ($e->getCode() == 0) 
                {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        } 
        else 
        {
            if (isset($data['store_logo']) && isset($data['store_logo']['value'])) 
            {
                if (isset($data['store_logo']['delete'])) 
                {
                    $data['store_logo'] = null;
                    $data['delete_image'] = true;
                }
                elseif (isset($data['store_logo']['value'])) 
                {
                    $data['store_logo'] = $data['store_logo']['value'];
                }
                else 
                {
                    $data['store_logo'] = null;
                }
            }
        }
            //end block upload image

			$id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
			$data['store_view'] = implode(",",$data['store_view']);
            $model->setData($data);
			
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Frist Grid Has been Saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                echo $e->getMessage();
                die;
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
