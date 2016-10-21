<?php

class SocialLogon_Block_Twitter_Account extends Mage_Core_Block_Template
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

        $this->setTemplate('inchoo/SocialLogon/twitter/account.phtml');

    }

    protected function _hasData()
    {
        return $this->userInfo->hasData();
    }


    protected function _getTwitterId()
    {
        return $this->userInfo->getId();
    }

    protected function _getStatus()
    {
        return '<a href="'.sprintf('https://twitter.com/%s', $this->userInfo->getScreenName()).'" target="_blank">'.
                    $this->escapeHtml($this->userInfo->getScreenName()).'</a>';
    }

    protected function _getPicture()
    {
        if($this->userInfo->getProfileImageUrl()) {
            return Mage::helper('SocialLogon/twitter')
                    ->getProperDimensionsPictureUrl($this->userInfo->getId(),
                            $this->userInfo->getProfileImageUrl());
        }

        return null;
    }

    protected function _getName()
    {
        return $this->userInfo->getName();
    }

}
