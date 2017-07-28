<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

/**
 * Description of Location
 *
 * @author Etudiant
 */


class Location extends DbObject{
	
	/**
	 *
	 * @var string 
	 */
	protected $name;
	/**
	 * 
	 * @var Country
	 */
	protected $country;
	
	function __construct($id=0, $name='', Country $country=null, $inserted=0) {
		$this->name = $name;
		$this->country = $country;
		parent::__construct($id, $inserted);
	}
	
	function getName() {
		return $this->name;
	}

	function getCountry() {
		return $this->country;
	}

	function setName($name) {
		$this->name = $name;
	}

	function setCountry(Country $country) {
		$this->country = $country;
	}

	
	
	
	public static function deleteById($id) {
		
	}

	public static function get($id) {
		$sql = '
			SELECT *
			FROM location
			WHERE loc_id = :id
		';
		
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
		
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$locationObject = new City(
					$row['loc_id'],
					$row['loc_name'],
					new Country($row['country_cou_id'])	
				);
				return $locationObject;
			}
		}
		return false;
	}

	public static function getAll() {
		
	}

	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT loc_id, loc_name
			FROM location
			WHERE loc_id > 0
			ORDER BY loc_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['loc_id']] = $row['loc_name'];
			}
		}

		return $returnList;
	}

	public function saveDB() {
		
	}

}
