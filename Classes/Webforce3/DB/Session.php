<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class Session extends DbObject {
	/**
	 * @param int $id
	 * @return DbObject
	 */

	/**
	 *
	 * @var string 
	 */
	protected $startDate;

	/**
	 *
	 * @var string
	 */
	protected $endDate;

	/**
	 *
	 * @var int 
	 */
	protected $number;

	/**
	 *
	 * @var Location 
	 */
	protected $location;

	/**
	 *
	 * @var Training 
	 */
	protected $training;

	function __construct($id = 0, $startDate = '', $endDate = '', $number = '', $location = null, $training = null, $inserted = 0) {
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->number = $number;
		if (empty($location)) {
			$this->location = new Location();
		} else {
			$this->location = $location;
		}
		if (empty($training)) {
			$this->training = new Training();
		} else {
			$this->training = $training;
		}

		parent::__construct($id, $inserted);
	}

	function getStartDate() {
		return $this->startDate;
	}

	function getEndDate() {
		return $this->endDate;
	}

	function getNumber() {
		return $this->number;
	}

	function getLocation() {
		return $this->location;
	}

	function getTraining() {
		return $this->training;
	}

	function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	function setNumber($number) {
		$this->number = $number;
	}

	function setLocation(Location $location) {
		$this->location = $location;
	}

	function setTraining(Training $training) {
		$this->training = $training;
	}

	public static function get($id) {
		$sql = '
			SELECT *
			FROM session
			INNER JOIN location ON location_loc_id = loc_id
			INNER JOIN training ON training_tra_id = tra_id
			WHERE ses_id = :id
		';

		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		} else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$sessionObject = new Session(
						$row['ses_id'], $row['ses_start_date'], $row['ses_end_date'], $row['ses_number'], new Location($row['location_loc_id']), new Training($row['training_tra_id']), time()
				);
				return $sessionObject;
			}
		}
		return false;
	}

	/**
	 * @return DbObject[]
	 */
	public static function getAll() {
		// TODO: Implement getAll() method.
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		} else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '[' . $row['ses_start_date'] . ' > ' . $row['ses_end_date'] . '] ' . $row['tra_name'] . ' - ' . $row['loc_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @param int $sessionId
	 * @return DbObject[]
	 */
	public static function getFromSession($sessionId) {
		// TODO: Implement getFromTraining() method.
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
		$sql = 'UPDATE session SET ses_start_date = :sesstartdate, ses_end_date = :sesenddate, ses_number = :number, location_loc_id = :locid, training_tra_id = :traid WHERE ses_id = :sesid';

		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':sesid', $this->id, \PDO::PARAM_INT);
		$stmt->bindValue(':locid', $this->location->getId(), \PDO::PARAM_INT);
		$stmt->bindValue(':traid', $this->training->getId(), \PDO::PARAM_INT);
		$stmt->bindValue(':number', $this->getNumber(), \PDO::PARAM_INT);
		$stmt->bindValue(':sesstartdate', $this->getStartDate(), \PDO::PARAM_STR);
		$stmt->bindValue(':sesenddate', $this->getEndDate(), \PDO::PARAM_STR);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		} else {

			echo 'Happy';
		}
		return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		// TODO: Implement deleteById() method.
	}

}
