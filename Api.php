<?php

/**
 * @author Michał Świtalik <michal.switalik@gmail.com>
 *
 * @version 1.0
 */
class API
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
	    preg_match('/^(\D+)#(\d+)$/', $battletag, $tmp);
	    $this->battletag = $tmp[1] . '-' . $tmp[2];
	    return true;
	} else {
	    return false;
	}
    }

    /**
     * @desc Set server to connect
     * Possible server: america, europe, taiwan, korea, china
     * @param type $server
     * @return boolean True if server is set.
     */
    public function set_Server($server)
    {
	if (!array_key_exists($server, $this->locales)) {
	    return false;
	} else {
	    $this->local = $this->locales[$this->server];
	    return true;
	}
    }

    /**
     * @desc Download and decode (json) player profile, then save it to $profile
     * @return boolean 
     */
    public function download_Profile()
    {
	if (!$this->battletag && !$this->locale) {
	    return false;
	}
	$curl = curl_init($this->local . '.battle.net/api/d3/profile/' . $$this->battletag . '/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$this->profile = json_decode(curl_exec($curl));
	curl_close($curl);
	return true;
    }

    /**
     * @desc Download and decode (json) hero profile, then save it to $hero
     * @param $hero integer Blizzard ID hero
     * @return boolean 
     */
    public function download_Hero($hero)
    {
	if (!$this->battletag && !$this->locale) {
	    return false;
	}
	$curl = curl_init($this->locale . '.battle.net/api/d3/profile/' . $this->battletag . '/hero/' . $hero . '/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$this->hero = json_decode(curl_exec($curl));
	curl_close($curl);
	return true;
    }

    /**
     * @desc Download and decode (json) item, then save it to $item
     * @param $item string
     * @return boolean 
     */
    public function download_Item($item)
    {
	if (!$this->locale) {
	    return false;
	}
	$curl = curl_init($this->locale . '.battle.net/api/d3/data/item/' . $item . '/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$this->item = json_decode(curl_exec($curl));
	curl_close($curl);
	return true;
    }

    /**
     * @desc Download and decode (json) follower, then save it to $follower
     * @param $follower string
     * @return boolean 
     */
    public function download_Follower($follower)
    {
	if (!$this->locale && (in_array($follower, $this->followers))) {
	    return false;
	}
	$curl = curl_init($this->locale . '.battle.net/api/d3/data/follower/' . $follower . '/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$this->follower = json_decode(curl_exec($curl));
	curl_close($curl);
	return true;
    }

    /**
     * @desc Download and decode (json) artisan, then save it to $artisan
     * @param $artisan string
     * @return boolean 
     */
    public function download_Artisan($artisan)
    {
	if ((in_array($artisan, $this->artisans)) && !$this->locale) {
	    return false;
	}
	$curl = curl_init($this->locale . '.battle.net/api/d3/data/artisan/' . $artisan . '/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$this->artisan = json_decode(curl_exec($curl));
	curl_close($curl);
	return true;
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
