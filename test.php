<?php

include 'Api.php';
$api = new Diablo3API;
$api->set_Server('europe');
$api->download_Artisan('jeweler');
echo '<pre> Jeweler:<br />';
var_dump($api->get_Artisan());
echo 'Blacksmith:<br />';
$api->download_Artisan('blacksmith');
var_dump($api->get_Artisan());
echo 'Profile: <br />';
$api->set_Battletag('MrGh0st #1814');
$api->download_Profile();
var_dump($api->get_Profile());
echo 'Hero: <br />';
$api->download_Hero('15146713');
var_dump($api->get_Hero());
echo 'Item: <br />';
$api->download_Item('CmII0pLq2AoSBwgEFQLZapEdlL80ih2BtT6MHXWx7PAd48Qrbx2DDibvHQZ9lkEwCTjEAUAASBVQEmDGAmolCgwIABDg5JLLhYCAgAYSFQjMhriwBRIHCAQVzl22ejANOABAARj7m6npDVAEWAA');
var_dump($api->get_Item());
