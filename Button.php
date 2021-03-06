<?php

class SocialLogon_Block_Twitter_Button extends Mage_Core_Block_Template
{
    /**
     *
     * @var SocialLogon_Model_Twitter_Oauth_Client
     */
    protected $client = null;
    
    /**
     *
     * @var SocialLogon_Model_Twitter_Info_User
     */
    protected $userInfo = null;

    protected function _construct() {
        parent::_construct();

        $this->client = Mage::getSingleton('SocialLogon/twitter_oauth_client');
        if(!($this->client->isEnabled())) {
            return;
        }

        $this->userInfo = Mage::registry('SocialLogon_twitter_userinfo');

        Mage::getSingleton('customer/session')
            ->setSocialConnectRedirect(Mage::helper('core/url')->getCurrentUrl());

        $this->setTemplate('inchoo/socialconnect/twitter/button.phtml');
    }

    protected function _getButtonUrl()
    {
        if(is_null($this->userInfo) || !$this->userInfo->hasData()) {
            return $this->client->createAuthUrl();
        } else {
            return $this->getUrl('socialconnect/twitter/disconnect');
        }
    }

    protected function _getButtonText()
    {
        if(is_null($this->userInfo) || !$this->userInfo->hasData()) {
            if(!($text = Mage::registry('SocialLogon_button_text'))){
                $text = $this->__('Connect');
            }
        } else {
            $text = $this->__('Disconnect');
        }

        return $text;
    }

}
