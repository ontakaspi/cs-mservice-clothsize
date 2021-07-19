<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Client_cloth_size extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model([
			'Client_cloth_size_model',
			'Client_data_model',
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
			$result = $this->Client_cloth_size_model->get_by_id_client($id);
		}else{
			$result = $this->Client_cloth_size_model->get_all();

		}

		if ($result){
			$message = [
				'code' => 200,
				'data' => $result,
				'message' => "Success retrieve Client cloth size"
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}else{
			if ($id){
				$message = [
					'code' => 404,
					'message' => 'Client cloth size not found'
				];
			}else{
				$message = [
					'code' => 404,
					'message' => 'Client cloth size no record'
				];
			}
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}


	}


	public function detail_get($id = null)
	{
		if (!$id) {
			$this->response([
				'code' => 400,
				'message' => 'Id is required, please send an id for client cloth size data.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$result = $this->Client_cloth_size_model->get_by_id($id);

		if ($result){
			$message = [
				'code' => 200,
				'data' => $result,
				'message' => "Success retrieve client cloth size cloth size"
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}else{

			$message = [
				'code' => 404,
				'message' => 'Client cloth size not found'
			];

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
				'message' => 'Id is required, please send an id for client cloth size.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		if ($this->Client_cloth_size_model->delete($id) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'Client cloth size data deleted.'
			], REST_Controller::HTTP_OK);
		} else {
			//id not found
			$this->response([
				'code' => 404,
				'message' => 'Client cloth size data null or id not found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}

	}

	public function create_post()
	{

		$data = $this->post();
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('size_chart_id', 'Size Chart id', 'required', [
			'required' => 'Size Chart id is required'
		]);
		$this->form_validation->set_rules('client_id', 'Client id', 'required', [
			'required' => 'Client id is required'
		]);
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$check_size_chart = $this->Size_chart_model->get_by_id($this->post('size_chart_id'));
		$check_client = $this->Client_data_model->get_by_id($this->post('client_id'));
		if (!$check_client || !$check_size_chart){
			$this->response([
				'code' => 400,
				'message' => ($check_client==null)?'size_chart_id & client id not found, data not created':'size_chart_id not found, data not created',
			], REST_Controller::HTTP_BAD_REQUEST);
		}
		$message = [
			'size_chart_id' => $this->post('size_chart_id'),
			'client_id' => $this->post('client_id'),
		];

		if ($this->Client_cloth_size_model->create($message) > 0) {
			$this->response([
				'code' => 200,
				'data' => $data,
				'message' => 'Client cloth size has been created.'
			], REST_Controller::HTTP_OK);
		} else {
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
				'message' => 'Id is required, please send an id for client cloth size.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$data = $this->put();
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('size_chart_id', 'Size Chart id', 'required', [
			'required' => 'Size Chart id is required'
		]);
		$this->form_validation->set_rules('client_id', 'Client id', 'required', [
			'required' => 'Client id is required'
		]);

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$check_size_chart = $this->Size_chart_model->get_by_id($this->post('size_chart_id'));
		$check_client = $this->Client_data_model->get_by_id($this->post('client_id'));
		if (!$check_client || !$check_size_chart){
			$this->response([
				'code' => 400,
				'message' => ($check_client==null)?'size_chart_id & client id not found, data not created':'size_chart_id not found, data not created',
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$check_exist = $this->Client_cloth_size_model->get_by_data_exist($this->put('client_id'),$this->put('client_id'));


			if ($check_exist){
					$this->response([
						'code' => 400,
						'message' => 'Client already store that cloth size'
					], REST_Controller::HTTP_BAD_REQUEST);
			}

		$data = [
			'size_chart_id' => $this->put('size_chart_id'),
		];

		if ($this->Client_cloth_size_model->put($id,$data) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'client cloth size updated.'
			], REST_Controller::HTTP_OK);
		} else {
			//id not found
			$this->response([
				'code' => 404,
				'message' => 'No data change or id not found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}

	}

}
