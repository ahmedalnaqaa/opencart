<?php
class ModelExtensionPaymentCODFee extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/cod_fee');
        $currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cod_fee_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('cod_fee_total') > 0 && $this->config->get('cod_fee_total') > $total) {
            $status = false;
        } elseif (!$this->cart->hasShipping()) {
            $status = false;
        } elseif (!$this->config->get('cod_fee_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'cod_fee',
                'title'      => $this->language->get('text_title')." - ".$this->config->get('cod_fee_amount')." ". $currency_code,
                'terms'      => '',
                'sort_order' => $this->config->get('cod_fee_sort_order')
            );
        }

        return $method_data;
    }
}
