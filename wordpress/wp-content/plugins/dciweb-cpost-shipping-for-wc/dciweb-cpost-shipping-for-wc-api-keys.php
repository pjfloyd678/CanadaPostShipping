<?php
/**
 * Description of ShippingRates
 * @author Peter
 */
class DCIWeb_CPost_Shipping_For_WC_API_Keys {

    private $useProduction;
    private $sandbox;
    private $production;

    public function __construct() {
        $this->sandbox = array(
            'username' => '947574caacdb8e53',
            'password' => '6de805cca0fd404ca2b0e6',
            'server'   => 'ct.soa-gw.canadapost.ca'
        );
        $this->production = array(
            'username' => '3d4edcc60407704a',
            'password' => 'b80b3c2635037b359037b5',
            'server'   => 'soa-gw.canadapost.ca'
        );
        $this->useProduction = false; // default turn it off!
    }
    public function useProduction() {
        $this->useProduction = true;
    }
    public function useSandbox() {
        $this->useProduction = false;
    }
    public function get() {
        if ( $this->useProduction ) {
            return $this->production;
        } else {
            return $this->sandbox;
        }
    }
}
