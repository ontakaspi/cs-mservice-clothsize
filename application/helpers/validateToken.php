<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('permission_access')) {
	function permission_access()
	{
		$ci = get_instance();
		$ci->load->model('Menu_model', 'menu', true);
		$role_id = $ci->session->userdata('roles_id');

		$segment1 = $ci->uri->segment('1');
		if ($segment1 == 'crud') {
			$url = $ci->uri->segment('1') . '/' . $ci->uri->segment('2');
			$aksescheck = $ci->menu->check_akses_user_crud($url, $role_id);
		} elseif ($segment1 == 'halaman') {
			$url = $ci->uri->segment('1') . '/' . $ci->uri->segment('2');
			$aksescheck = $ci->menu->check_akses_user_page($url, $role_id);
		}else {
			$url = $ci->uri->segment('1') . '/' . $ci->uri->segment('2');
			$aksescheck = $ci->menu->check_akses_user($url, $role_id);
		}

		if (!isset($aksescheck)) {
			if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$data['error'] = 1;
				$data['error_message'] = 'Kamu mencoba mengakses halaman yang tidak dijinkan.';
				echo json_encode($data);
				die;
			} else {
				$data['error_message'] = 'Kamu mencoba mengakses halaman yang tidak dijinkan.';
				redirect('error-show/index', 'refresh');
			}
		} else {
			return $aksescheck;
		}
	}
}
