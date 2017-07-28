<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class City extends DbObject {
	/**
	 * @param int $id
	 * @return DbObject
	 */
	protected $name;
	/**
	 *
	 * @var Country 
	 */
	protected $country;
	
	function __construct($id=0, $name='', $country=null , $inserted=0) {
		
		if(empty($country)) {
			$this->country = new Country();
		}
		else{
			$this->country = $country;
		}
		
		$this->name = $name;
		
		parent::__construct($id, $inserted);
	}
	
	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}
	
	function getCountry() {
		return $this->country;
	}

	function setCountry($country) {
		$this->country = $country;
	}

	
	
	
	
	public static function get($id) {
		$sql = '
			SELECT *
			FROM city
			WHERE cit_id = :id
		';
		
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
		
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$cityObject = new City(
					$row['cit_id'],
					$row['cit_name'],
					new Country($row['country_cou_id'])	
				);
				return $cityObject;
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
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cit_id']] = $row['cit_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
		$sql = 'UPDATE city SET country_cou_id = :couid, cit_name = :citname WHERE cit_id = :citid';
		
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':citid', $this->id, \PDO::PARAM_INT);
		$stmt->bindValue(':couid', $this->country->getId(), \PDO::PARAM_INT);
		$stmt->bindValue(':citname', $this->name, \PDO::PARAM_STR);
		
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			
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