<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Client_data_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	public function get_all()
	{
		return $this->db
			->select('*')
			->order_by('client.id', 'DESC' )
			->from('client')
			->get()->result();
	}
	public function get_by_id($id)
	{

		return $this->db
			->select('*')
			->order_by('client.id', 'DESC' )
			->from('client')
			->where('id',$id)
			->get()->row();
	}

	public function get_by_email($email)
	{
		return $this->db
			->select('*')
			->order_by('client.id', 'DESC' )
			->from('client')
			->where('email',$email)
			->get()->row();
	}

	public function delete($id)
	{
		$this->db->delete('client', ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function put($id,$data)
	{
		$this->db->update('client', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function create($data)
	{
		$this->db->insert('client', $data);
		return $this->db->affected_rows();
	}

}

