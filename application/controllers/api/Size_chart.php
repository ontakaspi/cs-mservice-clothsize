<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Size_chart extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model([
			'Size_chart_model',
		]);

		$this->auth = $this->authorization_token->validateToken();
		if (($this->auth['status']) ==false) {
			$this->response([
				'code' => 401,
				'message' => $this->auth['message'],
			], REST_Controller::HTTP_UNAUTHORIZED);
		}
	}


	public function index_get($id = null)
	{
		if ($id){
			$result = $this->Size_chart_model->get_by_id($id);
		}else{
			$result = $this->Size_chart_model->get_all();
		}

		if ($result){
			$message = [
				'code' => 200,
				'data' => $result,
				'message' => "Success retrieve chart size"
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}else{
			if ($id){
				$message = [
					'code' => 404,
					'message' => 'Chart size size not found'
				];
			}else{
				$message = [
					'code' => 404,
					'message' => 'Chart size no record'
				];
			}
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}


	}


	public function delete_delete($id = null)
	{
		if (($this->auth['data']->roles) !== 'admin') {
			$this->response([
				'code' => 401,
				'message' => 'user doesnt have this authorization',
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

		if (!$id) {
			$this->response([
				'code' => 400,
				'message' => 'Id is required, please send an id for chart size.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		if ($this->Size_chart_model->delete($id) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'Size chart data deleted.'
			], REST_Controller::HTTP_OK);
		} else {
			//id not found
			$this->response([
				'code' => 404,
				'message' => 'Data null or id not found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}

	}

	public function create_post()
	{

		$data = $this->post();
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('size_type', 'Size Type', 'required', [
			'required' => 'size type is required'
		]);
		$this->form_validation->set_rules('cloth_type', 'Cloth Type', 'required', [
			'required' => 'Cloth type is required'
		]);
		$this->form_validation->set_rules('gender_type', 'Gender type', 'required', [
			'required' => 'Gender type is required'
		]);
		$this->form_validation->set_rules('chest_width', 'Chest Width', 'required|max_length[3]', [
			'required' => 'Chest width is required'
		]);
		$this->form_validation->set_rules('shirt_length', 'Shirt Length', 'required|max_length[3]', [
			'required' => 'Shirt Length is required'
		]);
		$this->form_validation->set_rules('waist_width', 'Waist Width', 'required|max_length[3]', [
			'required' => 'Waist width is required'
		]);
		$this->form_validation->set_rules('sleeve_length', 'Sleeve Length', 'required|max_length[3]', [
			'required' => 'Sleeve Length is required'
		]);

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		if ($this->Size_chart_model->create($data) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'data' => $data,
				'message' => 'Size chart data has been created.'
			], REST_Controller::HTTP_OK);
		} else {
			//id not found
			$this->response([
				'code' => 400,
				'message' => 'No data created'
			], REST_Controller::HTTP_BAD_REQUEST);
		}

	}

	public function update_put($id =null)
	{
		if (!$id) {
			$this->response([
				'code' => 400,
				'message' => 'Id is required, please send an id for chart size.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
		$data = $this->put();
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('size_type', 'Size Type', 'required', [
			'required' => 'size type is required'
		]);
		$this->form_validation->set_rules('cloth_type', 'Cloth Type', 'required', [
			'required' => 'Cloth type is required'
		]);
		$this->form_validation->set_rules('gender_type', 'Gender type', 'required', [
			'required' => 'Gender type is required'
		]);
		$this->form_validation->set_rules('chest_width', 'Chest Width', 'required|max_length[3]', [
			'required' => 'Chest width is required'
		]);
		$this->form_validation->set_rules('shirt_length', 'Shirt Length', 'required|max_length[3]', [
			'required' => 'Shirt Length is required'
		]);
		$this->form_validation->set_rules('waist_width', 'Waist Width', 'required|max_length[3]', [
			'required' => 'Waist width is required'
		]);
		$this->form_validation->set_rules('sleeve_length', 'Sleeve Length', 'required|max_length[3]', [
			'required' => 'Sleeve Length is required'
		]);

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		if ($this->Size_chart_model->put($id,$data) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'Size chart data updated.'
			], REST_Controller::HTTP_OK);
		} else {
			//id not found
			$this->response([
				'code' => 404,
				'message' => 'No data change or id not found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}

	}

	public function get_get($by = null,$params1 =null,$params2=null)
	{

		switch ($by) {
			case 'gender':
				$result = $this->Size_chart_model->get_by_gender_distinct($params1);
				break;
			default:
				$result = $this->Size_chart_model->get_by_cloth_type_distinct($params1,$params2);
		}

		if ($result){
			$message = [
				'code' => 200,
				'data' => $result,
				'message' => "Success retrieve Client cloth size"
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}else{
			$message = [
					'code' => 404,
					'message' => 'Get chart size by'.$by.'not found'
			];

			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}


	}


}
