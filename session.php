<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use Classes\Webforce3\Helpers\SelectHelper;
use Classes\Webforce3\DB\Session;
use Classes\Webforce3\DB\Country;
use Classes\Webforce3\DB\Location;
use Classes\Webforce3\DB\Training;

// Get the config object
$conf = Config::getInstance();

$sessionObject = new Session();
$trainingObject = new Training();
$locationObject = new Location();
$sessionList = Session::getAllForSelect();
$locationList = Location::getAllForSelect();
$trainingList = Training::getAllForSelect();


// Formulaire soumis
if (!empty($_GET)) {
	$sessionId = isset($_GET['ses_id']) ? intval($_GET['ses_id']) : 0;
	if ($sessionId > 0) {
		$sessionObject = Session::get($sessionId);
		echo $sessionObject->getLocation()->getId();
	}
}

$selectLocations = new SelectHelper($locationList, $sessionObject->getLocation()->getId(), array(
	'name' => 'loc_id',
	'id' => 'loc_id',
	'class' => 'form-control'
		));

$selectTrainings = new SelectHelper($trainingList, $sessionObject->getTraining()->getId(), array(
	'name' => 'tra_id',
	'id' => 'tra_id',
	'class' => 'form-control'
		));

$selectSessions = new SelectHelper($sessionList, $sessionObject->getId(), array(
	'name' => 'ses_id',
	'id' => 'ses_id',
	'class' => 'form-control'
		));


if (!empty($_POST)) {
	$sesId = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$sesStartDate = isset($_POST['ses_start_date']) ? strip_tags(trim($_POST['ses_start_date'])) : '';
	$sesEndDate = isset($_POST['ses_end_date']) ? strip_tags(trim($_POST['ses_end_date'])) : '';
	$sesNumber = isset($_POST['ses_number']) ? intval($_POST['ses_number']) : 0;
	$locId = isset($_POST['loc_id']) ? intval($_POST['loc_id']) : 0;
	$traId = isset($_POST['tra_id']) ? intval($_POST['tra_id']) : 0;

	$sessionObject = new Session(
			$sesId, $sesStartDate, $sesEndDate, $sesNumber, new Location($locId), new Training($traId), time()
	);
	echo $sessionObject->getLocation()->getId();
	echo $sessionObject->getTraining()->getId();
	$sessionObject->saveDB();
}

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir() . 'header.php';
require $conf->getViewsDir() . 'session.php';
require $conf->getViewsDir() . 'footer.php';
