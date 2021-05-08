<?php
/**
 * Smartceo.ru
 * фильтр спама при регистрации новых пользователей
 */
namespace Smartceo\Customer\Plugin;

use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;
use Magento\Framework\Message\ManagerInterface;
//use Magento\Framework\Exception\InputException;
use \Magento\Framework\Exception\NotFoundException;

class CustomerCreatePostActionPlugin {

    public function __construct(
    UrlFactory $urlFactory, RedirectFactory $redirectFactory, ManagerInterface $messageManager
    ) {
        $this->urlModel = $urlFactory->create();
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Фильтрация получаемых данных при регистрации нового пользователя для отсечки спаовых сообщений
     * 
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @throws NotFoundException
     */
    public function beforeExecute(\Magento\Customer\Controller\Account\CreatePost $subject/* , \Closure $proceed */) {
        $firstname = $subject->getRequest()->getParam('firstname');
        $lastname = $subject->getRequest()->getParam('lastname');
        
        preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $firstname, $match);
        if (count($match) > 0) {
            throw new NotFoundException(__('Спамботам здесь не место.'));
        }
 
        preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $lastname, $match);
        if (count($match) > 0) {
            throw new NotFoundException(__('Спамботам здесь не место.'));
        }
        if (strlen(utf8_decode($firstname)) > 30 || strlen(utf8_decode($lastname)) > 30) {
            //throw new InputException(__('Спамботам здесь не место'));
            throw new NotFoundException(__('Спамботам здесь не место.'));
        }
    }

}
