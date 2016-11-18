<?php
class ModelExtensionTotalCODFee extends Model {
    public function getTotal($totals) {
        if ($this->cart->getSubTotal() && (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'cod_fee')) {
            $this->load->language('extension/total/cod_fee');

            $totals['totals'][] = array(
                'code'       => 'cod_fee',
                'title'      => $this->language->get('text_cod_fee'),
                'value'      => $this->config->get('cod_fee_amount'),
                'sort_order' => $this->config->get('cod_fee_sort_order')
            );

            $totals['total'] += $this->config->get('cod_fee_amount');
        }
    }
}