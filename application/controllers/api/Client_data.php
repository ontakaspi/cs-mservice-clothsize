<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Client_data extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model([
			'Client_data_model',
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
			$result = $this->Client_data_model->get_by_id($id);
		}else{
			$result = $this->Client_data_model->get_all();
		}


		if ($result){
			$message = [
				'code' => 200,
				'data' => $result,
				'message' => "Success retrieve client data"
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}else{
			if ($id){
				$message = [
					'code' => 404,
					'message' => 'Client data not found'
				];
			}else{
				$message = [
					'code' => 404,
					'message' => 'Client data has no record'
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
				'message' => 'Id is required, please send an id for client data.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		if ($this->Client_data_model->delete($id) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'Client data deleted.'
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
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[client.email]', [
			'required' => 'Email required'
		]);
		$this->form_validation->set_rules('name', 'Name', 'required', [
			'required' => 'Name is required'
		]);
		$this->form_validation->set_rules('gender', 'Gender', 'required', [
			'required' => 'Gender is required'
		]);
		$this->form_validation->set_message('is_unique', 'The %s is already taken');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$message = [
			'name' => $this->post('name'),
			'email' => $this->post('email'),
			'gender' => $this->post('gender'),
			'created_at' => date('Y-m-d'),
		];

		if ($this->Client_data_model->create($message) > 0) {
			$this->response([
				'code' => 200,
				'data' => $data,
				'message' => 'Client data has been created.'
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
				'message' => 'Id is required, please send an id for chart size.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}

		$data = $this->put();
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email', [
			'required' => 'Email required'
		]);
		$this->form_validation->set_rules('name', 'Name', 'required', [
			'required' => 'Name is required'
		]);
		$this->form_validation->set_rules('gender', 'Gender', 'required', [
			'required' => 'Gender is required'
		]);
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'code' => 400,
				'message' => validation_errors()
			], REST_Controller::HTTP_BAD_REQUEST);
		}


		$check_email = $this->Client_data_model->get_by_email($this->put('email'));


			if ($check_email){
				if ($this->put('email') !== $check_email->email){
					$this->response([
						'code' => 400,
						'message' => 'Email already taken'
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			}

		$message = [
			'name' => $this->put('name'),
			'email' => $this->put('email'),
			'gender' => $this->post('gender'),
			'updated_at' => date('Y-m-d'),
		];

		if ($this->Client_data_model->put($id,$message) > 0) {
			//Berhasil
			$this->response([
				'code' => 200,
				'id' => $id,
				'message' => 'Client data updated.'
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
