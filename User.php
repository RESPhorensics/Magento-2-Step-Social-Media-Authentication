<?php

class SocialLogon_Model_Twitter_Info_User extends SocialLogon_Model_Twitter_Info
{

    /**
     *
     * @var type Mage_Core_Model_Customer
     */
    protected $customer = null;


    /**
     * Load customer user info
     *
     * @param null|int $id Customer Id
     * @return SocialLogon_Model_Twitter_Userinfo
     */
    public function load($id = null)
    {
        if(is_null($id) && Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->customer = Mage::getSingleton('customer/session')->getCustomer();
        } else if(is_int($id)) {
            $this->customer = Mage::getModel('customer/customer')->load($id);
            
            // TODO: Implement
        }

        if(!$this->customer->getId()) {
            return $this;
        }

        if(!($socialconnectLid = $this->customer->getInchooSocialconnectTid()) ||
                !($socialconnectTtoken = $this->customer->getInchooSocialconnectTtoken())) {
            return $this;
        }

        $this->setAccessToken($socialconnectTtoken);
        $this->_load();

        return $this;
    }

    protected function _onException($e) {
        parent::_onException($e);

        $helper = Mage::helper('SocialLogon/twitter');
        /* @var $helper SocialLogon_Helper_Twitter */

        $helper->disconnect($this->customer);
    }

}