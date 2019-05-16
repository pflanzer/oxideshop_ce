<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2014
 * @version   OXID eShop CE
 */

/**
 * Newsletter Subscriptions manager
 * Performs user managing function
 * information, deletion and other.
 *
 * @package model
 */
class oxNewsSubscribed extends oxBase
{
    /**
     * Subscription marker
     *
     * @var bool
     */
    protected $_blWasSubscribed = false;

    /**
     * Subscription marker. Marks that newsletter was subscribed but wasn't confirmed.
     *
     * @var bool
     */
    protected $_blWasPreSubscribed = false;

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxnewssubscribed';


    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {

        parent::__construct();

        $this->init( 'oxnewssubscribed' );
    }


    /**
     * Loads object (newssubscription) details from DB. Returns true on success.
     *
     * @param string $oxId oxnewssubscribed ID
     *
     * @return bool
     */
    public function load( $oxId )
    {
        $blRet = parent::load( $oxId );

        if ( $this->oxnewssubscribed__oxdboptin->value == 1 ) {
            $this->_blWasSubscribed = true;
        } elseif ( $this->oxnewssubscribed__oxdboptin->value == 2 ) {
            $this->_blWasPreSubscribed = true;
        }

        return $blRet;
    }

    /**
     * Loader which loads news subscription according to subscribers email address
     *
     * @param string $sEmailAddress subscribers email address
     *
     * @return bool
     */
    public function loadFromEmail( $sEmailAddress )
    {
        $oDb = oxDb::getDb();
        $sEmailAddressQuoted = $oDb->quote( $sEmailAddress );
            $sOxId = $oDb->getOne( "select oxid from oxnewssubscribed where oxemail = {$sEmailAddressQuoted} " );

        return $this->load( $sOxId );
    }

    /**
     * Loader which loads news subscription according to subscribers oxid
     *
     * @param string $sOxUserId subscribers oxid
     *
     * @return bool
     */
    public function loadFromUserId( $sOxUserId )
    {
        $oDb = oxDb::getDb();
        $sOxId = $oDb->getOne( "select oxid from oxnewssubscribed where oxuserid = {$oDb->quote( $sOxUserId )} and oxshopid = {$oDb->quote( $this->getConfig()->getShopId() )}" );
        return $this->load( $sOxId );
    }

    /**
     * Inserts nbews object data to DB. Returns true on success.
     *
     * @return mixed oxid on success or false on failure
     */
    protected function _insert()
    {
        // set subscription date
        $this->oxnewssubscribed__oxsubscribed = new oxField(date( 'Y-m-d H:i:s' ), oxField::T_RAW);
        return parent::_insert();
    }

    /**
     * We need to check if we unsubscribe here
     *
     * @return mixed oxid on success or false on failure
     */
    protected function _update()
    {
        if ( ( $this->_blWasSubscribed || $this->_blWasPreSubscribed ) && !$this->oxnewssubscribed__oxdboptin->value ) {
            // set unsubscription date
            $this->oxnewssubscribed__oxunsubscribed->setValue(date( 'Y-m-d H:i:s' ));
            // 0001974 Same object can be called many times without requiring to renew date.
            // If so happens, it would have _aSkipSaveFields set to skip date field. So need to check and
            // release if _aSkipSaveFields are set for field oxunsubscribed.
            $aSkipSaveFieldsKeys = array_keys( $this->_aSkipSaveFields, 'oxunsubscribed' );
            foreach ( $aSkipSaveFieldsKeys as $iSkipSaveFieldKey ) {
                unset ( $this->_aSkipSaveFields[ $iSkipSaveFieldKey ] );
            }
        } else {
            // don't update date
            $this->_aSkipSaveFields[] = 'oxunsubscribed';
        }

        return parent::_update();
    }

    /**
     * Newsletter subscription status getter
     *
     * @return int
     */
    public function getOptInStatus()
    {
        return $this->oxnewssubscribed__oxdboptin->value;
    }

    /**
     * Newsletter subscription status setter
     *
     * @param int $iStatus subscription status
     *
     * @return null
     */
    public function setOptInStatus( $iStatus )
    {
        $this->oxnewssubscribed__oxdboptin = new oxField($iStatus, oxField::T_RAW);
        $this->save();
		$this->_logSubscription($iStatus);
    }

    /**
     * Newsletter subscription email sending status getter
     *
     * @return int
     */
    public function getOptInEmailStatus()
    {
        return $this->oxnewssubscribed__oxemailfailed->value;
    }

    /**
     * Newsletter subscription email sending status setter
     *
     * @param int $iStatus subscription status
     *
     * @return null
     */
    public function setOptInEmailStatus( $iStatus )
    {
        $this->oxnewssubscribed__oxemailfailed = new oxField($iStatus, oxField::T_RAW);
        $this->save();
    }

    /**
     * Check if was ever unsubscribed by unsubscribed field.
     *
     * @return bool
     */
    public function wasUnsubscribed()
    {
        if ('0000-00-00 00:00:00' != $this->oxnewssubscribed__oxunsubscribed->value) {
            return true;
        }
        return false;
    }

    /**
     * This method is called from oxuser::update. Currently it updates user
     * information kept in db
     *
     * @param oxuser $oUser subscription user object
     *
     * @return bool
     */
    public function updateSubscription( $oUser )
    {
        // user email changed ?
        if ( $oUser->oxuser__oxusername->value && $this->oxnewssubscribed__oxemail->value != $oUser->oxuser__oxusername->value ) {
            $this->oxnewssubscribed__oxemail = new oxField( $oUser->oxuser__oxusername->value, oxField::T_RAW );
        }

        // updating some other fields
        $this->oxnewssubscribed__oxsal   = new oxField( $oUser->oxuser__oxsal->value, oxField::T_RAW );
        $this->oxnewssubscribed__oxfname = new oxField( $oUser->oxuser__oxfname->value, oxField::T_RAW );
        $this->oxnewssubscribed__oxlname = new oxField( $oUser->oxuser__oxlname->value, oxField::T_RAW );

        return (bool) $this->save();
    }
	
	/** 
	 * Returns allowed sources (only for frontend forms) for newsletter subscription logging 
	 *
	 * @return array
	 */
	public function getAllowedNewsSubscriptionSources() 
	{
		return array('footerform','footerlink');
	}
	
	/**
	 * Return source for newsletter subscription logging.
	 * Possible sources are: footerform, footerlink, newsletterform, registerform, myaccount, checkout, admin, unknown 
	 *
	 * @return string
	 */
	protected function _getNewsletterSubsriptionSource() 
	{
		if($this->isAdmin()) return 'admin';
		$sTopActiveClass = strtolower(oxRegistry::get('oxconfig')->getRequestParameter('cl'));
		switch ($sTopActiveClass) {
			case 'newsletter':
				$sRequestSource = oxRegistry::get('oxconfig')->getRequestParameter('nlsource');
				if(in_array($sRequestSource, $this->getAllowedNewsSubscriptionSources())) return $sRequestSource;
				return 'newsletterform';
			case 'register':
				return 'registerform';
			case 'account_newsletter':
				return 'myaccount';
			case 'user':
				return 'checkout';
		}
		return 'unknown';
	} 
	

	/**
	 * Logs subscription updates
	 *
	 * @param integer subscription status
	 *
	 * @return void
	 */
	protected function _logSubscription($iStatus) 
	{
		$aLogData = array('SOURCE' => $this->_getNewsletterSubsriptionSource(), 'LOGTIME' => date('Y-m-d H:i:s'));
		$aLogData = array_merge($aLogData,oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getRow("SELECT * FROM oxnewssubscribed WHERE OXID='".$this->getId()."'"));
		$sFormat = 'TXT';
		$sSeperator = '	';
		if(oxRegistry::get('oxconfig')->getShopConfVar('sNlSubsLogType') == 'CSV') {
			$sFormat = 'CSV';
			$sSeperator = ';';
			foreach($aLogData as $k => $v) {
				$aLogData[$k] = '"'.$v.'"';
			}
		}
		$filepath = oxRegistry::get('oxconfig')->getConfigParam('sShopDir').'/log/'.$this->_getSubscriptionLogFilename();
		// Write headline if file does not exist:
		if(!file_exists($filepath) || filesize($filepath) < 1) {
			file_put_contents($filepath,implode($sSeperator,array_keys($aLogData)));
		}
		// Write log data
		file_put_contents($filepath,PHP_EOL.implode($sSeperator,$aLogData),FILE_APPEND);
	}
	
	/**
	 * Returns format for subscription logging. Configurable in admin.
	 *
	 * @return string
	 */
	protected function _getSubscriptionLogFormat() 
	{
		if(oxRegistry::get('oxconfig')->getShopConfVar('sNlSubsLogType') == 'CSV') return 'csv';
		return 'txt';
	}
	
	/**
	 * Return filename to write subscription log data in
	 *
	 * @return string
	 */
	protected function _getSubscriptionLogFilename() {
		return 'nlsubscriptions_'.oxRegistry::get('oxconfig')->getShopId().'.'.$this->_getSubscriptionLogFormat();
	}
	
}
