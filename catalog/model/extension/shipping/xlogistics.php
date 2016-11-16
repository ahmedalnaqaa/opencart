<?php
class ModelExtensionShippingXlogistics extends Model {
    function getQuote($address) {
        $this->load->language('extension/shipping/xlogistics');

        $method_data = array();
        $quote_data = array();
        $currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('xlogistics_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('xlogistics_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        if ($status) {
            $quote_data['xlogistics'] = array(
                'code'         => 'xlogistics'.'.xlogistics',
                'title'        => $this->language->get('text_title'),
                'cost'         => $this->config->get('xlogistics_cost'),
                'tax_class_id' => $this->config->get('xlogistics_tax_class_id'),
                'text'         => $this->currency->format($this->tax->calculate($this->config->get('xlogistics_cost'), $this->config->get('xlogistics_tax_class_id'), $this->config->get('config_tax')), $currency_code)
            );

            $method_data = array(
                'code'       => 'xlogistics',
                'title'      => $this->language->get('text_title'),
                'quote'      => $quote_data,
                'sort_order' => $this->config->get('xlogistics_sort_order'),
                'error'      => ''
            );
        }

        return $method_data;
    }
}
?>