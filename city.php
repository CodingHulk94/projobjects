<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\City;
use Classes\Webforce3\Helpers\SelectHelper;
use Classes\Webforce3\DB\Country;

// Get the config object
$conf = Config::getInstance();

$cityObject = new City();
$countryObject = new Country();
$citiesList = City::getAllForSelect();
$countriesList = Country::getAllForSelect();

//Get the list of cities

	if(!empty($_GET)){
		$id = isset($_GET['cit_id']) ? intval($_GET['cit_id']) : 0;
		if ($id > 0){
			$cityObject = City::get($id);
			
		}
	}
	
	//Get the list of cities
$selectCities = new SelectHelper($citiesList, $cityObject->getId(), array(
	'name' => 'cit_id',
	'id' => 'cit_id',
	'class' => 'form-control'
));
$selectCountries = new SelectHelper($countriesList, $cityObject->getCountry()->getId(), array(
	'name' => 'cou_id',
	'id' => 'cou_id',
	'class' => 'form-control'
));





// Formulaire soumis
if(!empty($_POST)) {
	$citId = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$citName = isset($_POST['cit_name']) ? strip_tags(trim($_POST['cit_name'])) : '';
	$countryId = isset($_POST['cou_id']) ? intval($_POST['cou_id']) : 0;
	var_dump($citId);
	var_dump($citName);
	var_dump($countryId);
	
	$cityObject = new City(
			$citId,
			$citName,
			new Country($countryId),
			time()
			);
	$cityObject->saveDB();
}

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'city.php';
require $conf->getViewsDir().'footer.php';