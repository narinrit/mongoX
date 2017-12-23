<?
#	MongoDB PHP Connector
#	Copyright (c) 2016, Narinrit Jitkati.
#
#	Version 1.1.0
#

Class MongoX {
	protected $Manager;
	protected $db;

	public function useDB($db) {
		$this->db = $db;
	}

	public function connect($authString = 'localhost') {
		try {
			$this->Manager = new MongoDB\Driver\Manager($authString);
		} catch (Exception $e) {
			error_log('Can\'t connect to MongoDB ' . $e);
		}
	}

	public function command($commands) {

		try {
			$command = new MongoDB\Driver\Command($commands);
		} catch (Exception $e) {
			error_log('MongoDB command error: ' . $e);
			return NULL;
		}

		try {
			$cursor = $this->Manager->executeCommand($this->db, $command);
			$cursor->setTypeMap(['root' => 'array', 'document' => 'array']);
			return $cursor->toArray();
		} catch (Exception $e) {
			error_log('MongoDB executeCommand error: ' . $e);
			return NULL;
		}
	}

	public function aggregate($collection, $pipeline) {
		$result = self::command(['aggregate' => $collection, 'pipeline' => $pipeline]);
		return isset($result[0]['result'][0]) ? $result[0]['result'][0] : NULL;
	}

	public function count($collection, $query = []) {
		$result = self::command(['count' => $collection, 'query' => $query]);
		return $result[0]['n'];
	}

	public function find($collection, $query = [], $options = []) {

		try {
			$query = new MongoDB\Driver\Query($query, $options);
		} catch (Exception $e) {
			error_log('MongoDB query error: ' . $e);
			return NULL;
		}

		try {
			$cursor = $this->Manager->executeQuery($this->db . '.' . $collection, $query);
			$cursor->setTypeMap(['root' => 'array', 'document' => 'array']);
			return $cursor->toArray();
		} catch (Exception $e) {
			error_log('MongoDB executeQuery error: ' . $e);
			return NULL;
		}
	}

	public function findOne($collection, $query = [], $options = []) {
		$options['limit'] = 1;
		$result = self::find($collection, $query, $options);
		return isset($result[0]) ? $result[0] : NULL;
	}

	public function save($collection, $document, $options = []) {
		return self::insert($collection, $document, $options);
	}

	public function insert($collection, $document) {

		try {
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->insert($document);
		} catch (Exception $e) {
			error_log('MongoDB BulkWrite insert error: ' . $e);
			return NULL;
		}

		try {
			$result = $this->Manager->executeBulkWrite($this->db . '.' . $collection, $bulk);
			return $result->getInsertedCount();
		} catch (Exception $e) {
			error_log('MongoDB insert error: ' . $e);
			return NULL;
		}
	}

	public function update($collection, $filter, $update, $options = []) {

		try {
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->update($filter, $update, $options);
		} catch (Exception $e) {
			error_log('MongoDB BulkWrite update error: ' . $e);
			return NULL;
		}

		try {
			$result = $this->Manager->executeBulkWrite($this->db . '.' . $collection, $bulk);
			return $result->getModifiedCount();
		} catch (Exception $e) {
			error_log('MongoDB update error: ' . $e);
			return NULL;
		}
	}

	public function remove($collection, $filter, $options = []) {
		return self::delete($collection, $filter, $options);
	}

	public function delete($collection, $filter, $options = []) {

		try {
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->delete($filter, $options);
		} catch (Exception $e) {
			error_log('MongoDB BulkWrite delete error: ' . $e);
			return NULL;
		}

		try {
			$result = $this->Manager->executeBulkWrite($this->db . '.' . $collection, $bulk);
			return $result->getDeletedCount();
		} catch (Exception $e) {
			error_log('MongoDB delete error: ' . $e);
			return NULL;
		}
	}
}