<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Client_cloth_size_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	public function get_by_id($id)
	{

		return $this->db
			->select('client_cloth_size.id,client.id as client_id,client.gender,
			size_chart.size_type,
			size_chart.cloth_type,')
			->from('client_cloth_size')
			->join('size_chart','client_cloth_size.size_chart_id = size_chart.id')
			->join('client','client_cloth_size.client_id =client.id')
			->where('client_cloth_size.id',$id)
			->get()->row();
	}

	public function get_all()
	{


//		$get_client = $this->db->distinct()
//			->select('client_id')
//			->get('client_cloth_size')->result();
//		GROUP_CONCAT(CONCAT(size_chart.cloth_type, '-', size_chart.size_type) ORDER BY size_chart.cloth_type SEPARATOR ', ') AS ukuran,
		$results1=  $this->db
			->select('client.id,client.name,client.email')
			->from('client_cloth_size')
			->join('client','client_cloth_size.client_id =client.id')
			->join('size_chart','client_cloth_size.size_chart_id =size_chart.id')
			->group_by('client_cloth_size.client_id')
			->order_by('client_cloth_size.id', 'DESC' )
			->get()->result();



		foreach ($results1 as $ukuran){
			$data_concate ='';
			$results2 = $this->db
				->select('CONCAT(size_chart.cloth_type, \'(\', size_chart.size_type,\')\') AS ukuran')
				->from('client_cloth_size')
				->join('size_chart','client_cloth_size.size_chart_id =size_chart.id')
				->where('client_cloth_size.client_id', $ukuran->id )
				->get()->result();

			$last_key = count($results2)-1;
			foreach ($results2 as $key=>$ukuran_baju){

				if ($key == $last_key) {
					$data_concate .= $ukuran_baju->ukuran;
				} else {
					$data_concate .= $ukuran_baju->ukuran.', ';
				}

			}
			$ukuran->ukuran=$data_concate;
		}

		return $results1;

	}

	public function get_by_id_client($id)
	{
		return  $this->db
			->select('client_cloth_size.id,
			size_chart.size_type,
			size_chart.cloth_type,
			CONCAT(chest_width,\'cm\'),
			CONCAT(shirt_length,\'cm\'),
			CONCAT(waist_width,\'cm\'),
			CONCAT(sleeve_length,\'cm\'),
		')
			->from('client_cloth_size')
			->join('size_chart','client_cloth_size.size_chart_id = size_chart.id')
			->join('client','client_cloth_size.client_id =client.id')
			->order_by('client_cloth_size.id', 'DESC' )
			->where('client.id',$id)
			->get()->result();
	}

	public function delete($id)
	{
		$this->db->delete('client_cloth_size', ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function put($id,$data)
	{
		$this->db->update('client_cloth_size', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function create($data)
	{
		$this->db->insert('client_cloth_size', $data);
		return $this->db->affected_rows();
	}

	public function get_by_data_exist($params1,$params2)
	{

		return $this->db
			->select('id')
			->where('client_id',$params1)
			->where('size_chart_id',$params2)
			->get('client_cloth_size')->row();

	}

}

