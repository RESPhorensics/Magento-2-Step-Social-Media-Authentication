<?php

class SocialLogon_Model_Twitter_Info extends Varien_Object
{
    protected $params = array(
        'skip_status' => true
    );

    /**
     * Twitter client model
     *
     * @var SocialLogon_Model_Twitter_Oauth_Client
     */
    protected $client = null;

    public function _construct() {
        parent::_construct();
        
        $this->client = Mage::getSingleton('SocialLogon/twitter_oauth_client');
        if(!($this->client->isEnabled())) {
            return $this;
        }
    }

    /**
     * Get Twitter client model
     *
     * @return SocialLogon_Model_Twitter_Oauth_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setClient(SocialLogon_Model_Twitter_Oauth_Client $client)
    {
        $this->client = $client;
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    /**
     * Get Twitter client's access token
     *
     * @return stdClass
     */
    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }

    public function load($id = null)
    {
        $this->_load();
        
        // Twitter doesn't allow email access trough API
        $this->setEmail(sprintf('%s@twitter-user.com', strtolower($this->getScreenName())));

        return $this;
    }

    protected function _load()
    {
        try{
            $this->client->getAccessToken();
            
            $response = $this->client->api(
                '/account/verify_credentials.json',
                'GET',
                $this->params
            );

            foreach ($response as $key => $value) {
                $this->{$key} = $value;
            }

        } catch(SocialLogon_Twitter_Oauth_Exception $e) {
            $this->_onException($e);
        } catch(Exception $e) {
            $this->_onException($e);
        }
    }

    protected function _onException($e)
    {
        if($e instanceof SocialLogon_Twitter_Oauth_Exception) {
            Mage::getSingleton('core/session')->addNotice($e->getMessage());
        } else {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
    }

}