<?php

class ModelPaymentIr123pay extends Model {
	public function getMethod() {
		$this->load->language( 'payment/ir123pay' );

		if ( $this->config->get( 'ir123pay_status' ) ) {

			$status = true;

		} else {

			$status = false;
		}

		$method_data = array();

		if ( $status ) {

			$method_data = array(
				'code'       => 'ir123pay',
				'title'      => $this->language->get( 'text_title' ),
				'sort_order' => $this->config->get( 'ir123pay_sort_order' )
			);
		}

		return $method_data;
	}
}