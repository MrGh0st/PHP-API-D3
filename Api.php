<?php

/**
 * @author Michał Świtalik <michal.switalik@gmail.com>
 *
 * @version 1.0
 */
class Diablo3API
{

    private $hero;
    private $profile;
    private $battletag;
    private $locales = array(
	'america' => 'us',
	'europe' => 'eu',
	'taiwan' => 'tw',
	'korea' => 'kr',
	'china' => 'cn'
    );
    private $local;
    private $followers = array(
	'enchantress',
	'templar',
	'scoundrel'
    );
    private $item;
    private $follower;
    private $artisans = array(
	'blacksmith',
	'jeweler'
    );
    private $artisan;

    /**
     * @desc Check the battletag is valid
     * @param $battletag string Blizzard Battletag
     * @return boolean True if battletag is valid
     */
    public function validate_Battletag($battletag)
    {
	return preg_match('/^(.+) *#[0-9]+$/', $battletag);
    }

    /**
     * @desc Save user battletag to private $battletag of class
     * @param type $battletag Battletag of Blizzard
     * @return boolean If set battletag (and its real) it will be set
     */
    public function set_Battletag($battletag)
    {
	if ($this->validate_Battletag($battletag)) {
	    $battletag = str_replace(" ", "", $battletag);
	    preg_match('/^(.+)#(\d+)$/', $battletag, $tmp);
	    $this->battletag = $tmp[1] . '-' . $tmp[2];
	    echo $this->battletag;
	    return true;
	} else {
	    return false;
	}
    }

    /**
     * @desc Set server to connect
     * @param type $server Servers: america, europe, taiwan, korea, china
     * @return boolean True if server is set.
     */
    public function set_Server($server)
    {
	if (!array_key_exists($server, $this->locales)) {
	    return false;
	} else {
	    $this->local = $this->locales[$server];
	    return true;
	}
    }

    /**
     * @desc Download and decode (json) player profile, then save it to $profile
     * @return boolean 
     */
    public function download_Profile()
    {
	if (!$this->battletag || !$this->local) {
	    return false;
	}
	if(!($this->profile = $this->download('profile/' . $this->battletag . '/'))){
	    return false;
	}
	return true;
    }

    /**
     * @desc Download and decode (json) hero profile, then save it to $hero
     * @param $hero integer Blizzard ID hero
     * @return boolean 
     */
    public function download_Hero($hero)
    {
	if (!$this->battletag || !$this->local) {
	    return false;
	}
	if(!($this->hero = $this->download('profile/' . $this->battletag . '/hero/' . $hero))){
	    return false;
	}
	return true;
    }

    /**
     * @desc Download and decode (json) item, then save it to $item
     * @param $item string
     * @return boolean 
     */
    public function download_Item($item)
    {
	if (!$this->local) {
	    return false;
	}
	if(!($this->item = $this->download('data/item/' . $item))){
	    return false;
	}
	return true;
    }

    /**
     * @desc Download and decode (json) follower, then save it to $follower
     * @param $follower string Followers: enchantress,templar,scoundrel
     * @return boolean 
     */
    public function download_Follower($follower)
    {
	if ((!$this->locale) || (!in_array($follower, $this->followers))) {
	    return false;
	}
	if(!($this->follower = $this->download('data/follower/'.$follower))){
	    return false;
	}	
	return true;
    }

    /**
     * @desc Download and decode (json) artisan, then save it to $artisan,
     * @param $artisan string Artisans: blacksmith,jeweler
     * @return boolean 
     */
    public function download_Artisan($artisan)
    {
	if ((!in_array($artisan, $this->artisans)) || (!$this->local)) {
	    return false;
	}
	if(!($this->artisan = $this->download('data/artisan/'.$artisan))){
	    return false;
	}
	return true;
    }

    /**
     * @desc Using cURL to get content or false if failed.
     * @param string $url
     * @return array/boolean
     */
    private function download($url)
    {
	$tmp = $this->local .'.battle.net/api/d3/'.$url.'';
	$curl = curl_init($tmp);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$return = curl_exec($curl);
	if($return){
	    $return = json_decode($return);
	    curl_close($curl);
	}
	return $return;
    }
    public function get_Profile()
    {
	return $this->profile;
    }

    public function get_Hero()
    {
	return $this->hero;
    }

    public function get_Follower()
    {
	return $this->follower;
    }

    public function get_Item()
    {
	return $this->item;
    }

    public function get_Artisan()
    {
	return $this->artisan;
    }

}
