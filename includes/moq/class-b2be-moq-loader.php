<?php
/**
 * WC RFQ.
 *
 * @package b2b-ecommerce-for-woocomerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'B2BE_MOQ_Loader' ) ) {
	/**
	 * Class B2BE_MOQ.
	 */
	class B2BE_MOQ_Loader {
		/**
		 * Cart Variable.
		 */
		public function __construct() {

			$this->includes();

		}

		/**
		 *  Function Includes.
		 */
		public function includes() {

			include_once 'class-b2be-moq-settings.php';

		}
	}

}
new B2BE_MOQ_Loader();
