<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class MY_Model extends CI_Model
{
	public function add($post, $table)
	{
		if ($this->db->insert($table, $post)) {
			$id = $this->db->insert_id();
			return ($id) ? $id : true;
		}else
			return false;
	}

	public function update($where, $post, $table)
	{
		return $this->db->where($where)->update($table, $post);
	}

	public function delete($table, $where)
	{
		return $this->db->delete($table, $where);
	}

	public function get($table, $select, $where)
	{
		$result = $this->db->select($select)
							->from($table)
							->where($where)
							->get()
							->row_array();

		return ($result !== false) ? $result : false;
	}

	public function getAll($table, $select, $where, $order_by = '', $limit = '')
	{
		$this->db->select($select)
					->from($table)
					->where($where);

		if ($order_by != '') 
			$this->db->order_by($order_by);
		
		if ($limit != '') 
			$this->db->limit($limit);
		
		$result = $this->db->get()->result_array();

		return ($result !== false) ? $result : false;
	}
}