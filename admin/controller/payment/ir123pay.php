<?php

class ControllerPaymentIr123pay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language( 'payment/ir123pay' );
		$this->load->model( 'setting/setting' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && ( $this->validate() ) ) {

			$this->model_setting_setting->editSetting( 'ir123pay', $this->request->post );

			$this->session->data['success'] = $this->language->get( 'text_success' );

			$this->redirect( $this->url->link( 'extension/payment', 'token=' . $this->session->data['token'], 'SSL' ) );
		}

		$this->data['heading_title'] = $this->language->get( 'heading_title' );

		$this->data['text_enabled']  = $this->language->get( 'text_enabled' );
		$this->data['text_disabled'] = $this->language->get( 'text_disabled' );
		$this->data['text_yes']      = $this->language->get( 'text_yes' );
		$this->data['text_no']       = $this->language->get( 'text_no' );

		$this->data['entry_merchant_id']  = $this->language->get( 'entry_merchant_id' );
		$this->data['entry_create']       = $this->language->get( 'entry_create' );
		$this->data['entry_verify']       = $this->language->get( 'entry_verify' );
		$this->data['entry_gateway']      = $this->language->get( 'entry_gateway' );
		$this->data['entry_order_status'] = $this->language->get( 'entry_order_status' );
		$this->data['entry_status']       = $this->language->get( 'entry_status' );
		$this->data['entry_sort_order']   = $this->language->get( 'entry_sort_order' );

		$this->data['help_encryption'] = $this->language->get( 'help_encryption' );

		$this->data['button_save']   = $this->language->get( 'button_save' );
		$this->data['button_cancel'] = $this->language->get( 'button_cancel' );

		$this->data['tab_general'] = $this->language->get( 'tab_general' );

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(

			'text'      => $this->language->get( 'text_home' ),
			'href'      => $this->url->link( 'common/home', 'token=' . $this->session->data['token'], 'SSL' ),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(

			'text'      => $this->language->get( 'text_payment' ),
			'href'      => $this->url->link( 'extension/payment', 'token=' . $this->session->data['token'], 'SSL' ),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(

			'text'      => $this->language->get( 'heading_title' ),
			'href'      => $this->url->link( 'payment/ir123pay', 'token=' . $this->session->data['token'], 'SSL' ),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link( 'payment/ir123pay', 'token=' . $this->session->data['token'], 'SSL' );
		$this->data['cancel'] = $this->url->link( 'extension/payment', 'token=' . $this->session->data['token'], 'SSL' );

		if ( isset( $this->error['warning'] ) ) {

			$this->data['error_warning'] = $this->error['warning'];

		} else {

			$this->data['error_warning'] = false;
		}

		if ( isset( $this->error['merchant_id'] ) ) {

			$this->data['error_merchant_id'] = $this->error['merchant_id'];

		} else {

			$this->data['error_merchant_id'] = false;
		}

		if ( isset( $this->error['create'] ) ) {

			$this->data['error_create'] = $this->error['create'];

		} else {

			$this->data['error_create'] = false;
		}

		if ( isset( $this->error['verify'] ) ) {

			$this->data['error_verify'] = $this->error['verify'];

		} else {

			$this->data['error_verify'] = false;
		}

		if ( isset( $this->error['gateway'] ) ) {

			$this->data['error_gateway'] = $this->error['gateway'];

		} else {

			$this->data['error_gateway'] = false;
		}

		if ( isset( $this->request->post['ir123pay_merchant_id'] ) ) {

			$this->data['ir123pay_merchant_id'] = $this->request->post['ir123pay_merchant_id'];

		} else {

			$this->data['ir123pay_merchant_id'] = $this->config->get( 'ir123pay_merchant_id' );
		}

		if ( isset( $this->request->post['ir123pay_create'] ) ) {

			$this->data['ir123pay_create'] = $this->request->post['ir123pay_create'];

		} else {

			$this->data['ir123pay_create'] = $this->config->get( 'ir123pay_create' );

			if ( isset( $this->data['ir123pay_create'] ) ) {

				$this->data['ir123pay_create'] = $this->data['ir123pay_create'];

			} else {

				$this->data['ir123pay_create'] = 'https://123pay.ir/api/v1/create/payment';
			}
		}

		if ( isset( $this->request->post['ir123pay_verify'] ) ) {

			$this->data['ir123pay_verify'] = $this->request->post['ir123pay_verify'];

		} else {

			$this->data['ir123pay_verify'] = $this->config->get( 'ir123pay_verify' );

			if ( isset( $this->data['ir123pay_verify'] ) ) {

				$this->data['ir123pay_verify'] = $this->data['ir123pay_verify'];

			} else {

				$this->data['ir123pay_verify'] = 'https://123pay.ir/api/v1/verify/payment';
			}
		}

		if ( isset( $this->request->post['ir123pay_gateway'] ) ) {

			$this->data['ir123pay_gateway'] = $this->request->post['ir123pay_gateway'];

		} else {

			$this->data['ir123pay_gateway'] = $this->config->get( 'ir123pay_gateway' );

			if ( isset( $this->data['ir123pay_gateway'] ) ) {

				$this->data['ir123pay_gateway'] = $this->data['ir123pay_gateway'];

			} else {

				$this->data['ir123pay_gateway'] = 'https://123pay.ir/checkout/invoice/';
			}
		}

		if ( isset( $this->request->post['ir123pay_order_status_id'] ) ) {

			$this->data['ir123pay_order_status_id'] = $this->request->post['ir123pay_order_status_id'];

		} else {

			$this->data['ir123pay_order_status_id'] = $this->config->get( 'ir123pay_order_status_id' );
		}

		$this->load->model( 'localisation/order_status' );

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if ( isset( $this->request->post['ir123pay_status'] ) ) {

			$this->data['ir123pay_status'] = $this->request->post['ir123pay_status'];

		} else {

			$this->data['ir123pay_status'] = $this->config->get( 'ir123pay_status' );
		}

		if ( isset( $this->request->post['ir123pay_sort_order'] ) ) {

			$this->data['ir123pay_sort_order'] = $this->request->post['ir123pay_sort_order'];

		} else {

			$this->data['ir123pay_sort_order'] = $this->config->get( 'ir123pay_sort_order' );
		}

		$this->template = 'payment/ir123pay.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput( $this->render() );
	}

	private function validate() {
		if ( ! $this->user->hasPermission( 'modify', 'payment/ir123pay' ) ) {

			$this->error['warning'] = $this->language->get( 'error_permission' );
		}

		if ( ! $this->request->post['ir123pay_merchant_id'] ) {

			$this->error['warning']     = $this->language->get( 'error_validate' );
			$this->error['merchant_id'] = $this->language->get( 'error_merchant_id' );
		}

		if ( ! $this->request->post['ir123pay_create'] ) {

			$this->error['warning'] = $this->language->get( 'error_validate' );
			$this->error['create']  = $this->language->get( 'error_create' );
		}

		if ( ! $this->request->post['ir123pay_verify'] ) {

			$this->error['warning'] = $this->language->get( 'error_validate' );
			$this->error['verify']  = $this->language->get( 'error_verify' );
		}

		if ( ! $this->request->post['ir123pay_gateway'] ) {

			$this->error['warning'] = $this->language->get( 'error_validate' );
			$this->error['gateway'] = $this->language->get( 'error_gateway' );
		}

		if ( ! $this->error ) {

			return true;

		} else {

			return false;
		}
	}
}