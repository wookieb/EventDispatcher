<?php

/**
 * @author wookieb
 * @package examples
 * @subpackage Comment
 */
class Comment_Data {
	private $_data = array();

	/**
	 * @param string $id
	 * @return self
	 */
	public function setId($id) {
		$this->_data['id'] = (string)$id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->_getValue('id');
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	private function _getValue($key) {
		if (isset($this->_data[$key])) {
			return $this->_data[$key];
		}
	}

	/**
	 * @param string $title
	 * @return self
	 */
	public function setTitle($title) {
		$this->_data['title'] = (string)$title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->_getValue('key');
	}

	/**
	 * @param string $body
	 * @return self
	 */
	public function setBody($body) {
		$this->_data['body'] = (string)$body;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->_getValue('body');
	}

	/**
	 * @param DateTime $date
	 * @return self
	 */
	public function setDateCreated(DateTime $date) {
		$this->_data['date_created'] = $date;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getDateCreated() {
		return $this->_getValue('date_created');
	}
}
