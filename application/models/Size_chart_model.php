<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Size_chart_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	public function get_all()
	{

		return $this->db
			->select('*')
			->order_by('size_chart.id', 'DESC' )
			->from('size_chart')
			->get()->result();
	}
	public function get_by_id($id)
	{

		return $this->db
			->select('*')
			->from('size_chart')
			->where('id',$id)
			->get()->row();
	}

    public function get_list_chart_size_lama()
    {

		return $this->datatables_lib
			->select('*')
			->order_by('size_chart.id', 'DESC' )
			->from('size_chart')
			->generate();
    }

	public function delete($id)
	{
		$this->db->delete('size_chart', ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function put($id,$data)
	{
		$this->db->update('size_chart', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function create($data)
	{
		$this->db->insert('size_chart', $data);
		return $this->db->affected_rows();
	}


	public function get_by_gender_distinct($params)
	{

		return $this->db->distinct()
			->select('cloth_type')
			->where('gender_type',$params)
			->get('size_chart')->result();
	}

	public function get_by_cloth_type_distinct($params1,$params2)
	{

		return $this->db
			->select('id,size_type')
			->where('cloth_type',$params1)
			->where('gender_type',$params2)
			->get('size_chart')->result();

	}


}

