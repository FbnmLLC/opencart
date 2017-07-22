<?php

class ControllerPaymentIr123pay extends Controller {
	protected function index() {
		$this->load->language( 'payment/ir123pay' );
		$this->load->model( 'checkout/order' );

		$order_info = $this->model_checkout_order->getOrder( $this->session->data['order_id'] );

		if ( $this->currency->getCode() != 'RLS' ) {

			$this->currency->set( 'RLS' );
		}

		$this->data['error_warning'] = false;

		$this->data['button_confirm'] = $this->language->get( 'button_confirm' );

		if ( extension_loaded( 'curl' ) ) {

			$parameters = array(
				'merchant_id'  => $this->config->get( 'ir123pay_merchant_id' ),
				'amount'       => $this->currency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false ),
				'callback_url' => urlencode( $this->url->link( 'payment/ir123pay/callback', 'order_id=' . $order_info['order_id'], '', 'SSL' ) ),
			);

			$response = $this->common( $this->config->get( 'ir123pay_create' ), $parameters );
			$result   = json_decode( $response );

			if ( $result->status ) {

				$this->data['action'] = $this->config->get( 'ir123pay_gateway' ) . $result->RefNum;

			} else {

				$message = isset( $result->message ) ? $result->message : $this->language->get( 'error_undefined' );

				$this->data['error_warning'] = $this->language->get( 'error_request' ) . '<br/><br/>' . $this->language->get( 'error_message' ) . $message;
			}

		} else {

			$this->data['error_warning'] = $this->language->get( 'error_curl' );
		}

		if ( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . '/template/payment/ir123pay.tpl' ) ) {

			$this->template = $this->config->get( 'config_template' ) . '/template/payment/ir123pay.tpl';

		} else {

			$this->template = 'default/template/payment/ir123pay.tpl';
		}

		$this->response->setOutput( $this->render() );
	}

	public function callback() {
		$this->load->language( 'payment/ir123pay' );
		$this->load->model( 'checkout/order' );

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$order_id = isset( $this->session->data['order_id'] ) ? $this->session->data['order_id'] : false;
		$order_id = isset( $order_id ) ? $order_id : $this->request->get['order_id'];

		$order_info = $this->model_checkout_order->getOrder( $order_id );

		if ( $this->currency->getCode() != 'RLS' ) {

			$this->currency->set( 'RLS' );
		}

		$this->data['heading_title'] = $this->language->get( 'heading_title' );

		$this->data['button_continue'] = $this->language->get( 'button_continue' );
		$this->data['continue']        = $this->url->link( 'common/home', '', 'SSL' );

		$this->data['error_warning'] = false;

		$this->data['continue'] = $this->url->link( 'checkout/cart', '', 'SSL' );

		if ( $this->request->get['State'] && $this->request->get['RefNum'] ) {

			$State  = $this->request->get['State'];
			$RefNum = $this->request->get['RefNum'];

			if ( $State == 'OK' ) {

				if ( is_numeric( $order_id ) ) {

					$parameters = array(
						'merchant_id' => $this->config->get( 'ir123pay_merchant_id' ),
						'RefNum'      => $RefNum
					);

					$response = $this->common( $this->config->get( 'ir123pay_verify' ), $parameters );
					$result   = json_decode( $response );

					if ( $result->status ) {

						$amount = @$this->currency->format( $order_info['total'], $order_info['currency'], $order_info['value'], false );

						if ( $amount == $result->amount ) {

							$this->model_checkout_order->confirm( $order_info['order_id'], $this->config->get( 'ir123pay_order_status_id' ), $RefNum );

						} else {

							$this->data['error_warning'] = $this->language->get( 'error_amount' );
						}

					} else {

						$code    = isset( $result->errorCode ) ? $result->errorCode : 'Undefined';
						$message = isset( $result->errorMessage ) ? $result->errorMessage : $this->language->get( 'error_undefined' );

						$this->data['error_warning'] = $this->language->get( 'error_request' ) . '<br/><br/>' . $this->language->get( 'error_code' ) . $code . '<br/>' . $this->language->get( 'error_message' ) . $message;
					}

				} else {

					$this->data['error_warning'] = $this->language->get( 'error_invoice' );
				}

			} else {

				$this->data['error_warning'] = $this->language->get( 'error_payment' );
			}

		} else {

			$this->data['error_warning'] = $this->language->get( 'error_data' );
		}

		if ( $this->data['error_warning'] ) {

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get( 'text_home' ),
				'href'      => $this->url->link( 'common/home', '', 'SSL' ),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get( 'text_basket' ),
				'href'      => $this->url->link( 'checkout/cart', '', 'SSL' ),
				'separator' => ' » '
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get( 'text_checkout' ),
				'href'      => $this->url->link( 'checkout/checkout', '', 'SSL' ),
				'separator' => ' » '
			);

			if ( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . '/template/payment/ir123pay_callback.tpl' ) ) {

				$this->template = $this->config->get( 'config_template' ) . '/template/payment/ir123pay_callback.tpl';

			} else {

				$this->template = 'default/template/payment/ir123pay_callback.tpl';
			}

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput( $this->render() );

		} else {

			$this->redirect( $this->url->link( 'checkout/success', '', 'SSL' ) );
		}
	}

	function common( $url, $parameters ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}
}

?>
