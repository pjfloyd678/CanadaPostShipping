<?php
/**
 * Description of ShippingRates
 * @author Peter
 */
class ShippingRates {
    private $shipping;
    private $headers;
    private $destinationInfo;
    private $username;
    private $password;
    private $accountNumber;
    private $xmlData;
    private $originatingPostalCode;
    private $packagesShipping;
    private $packagesProducts;
    
    public function __construct() {
        $this->shipping = [
            'choice' => '',
            'code' => '',
            'id' => '',
            'price' => 0,
            'delivery-date' => '',
            'days' => ''
        ];
        $this->setHeaders();
        $this->setUsername();
        $this->setPassword();
        $this->setAccountNumber();
        $this->setDestinationInfo("CA");
        $this->setPackagesShipping();
        $this->xmlData = '';
//        $this->setOriginatingPostalCode();
    }
    
    /*
     * SHIPPING INFO
     */
    public function setShipping($shipping) {
        $this->shipping = $shipping;
    }

    public function setShippingDetails($inArray) {
        $this->shipping = $inArray;
    }
    
    public function getShippingDetails() {
        $arr = array();
        if (is_array($this->shipping)) {
            $arr = $this->shipping;
        }
        return $arr;
    }

    public function setShippingCode($inCode) {
        if (is_array($this->shipping)) {
            $this->shipping['code'] = $inCode;
        }
    }

    public function setshippingName($inID) {
        if (is_array($this->shipping)) {
            $this->shipping['id'] = $inID;
        }
    }
    
    public function setShippingPrice($inPrice) {
        if (is_array($this->shipping)) {
            $this->shipping['price'] = floatval($inPrice);
        }
    }
    
    public function setShippingDeliveryDate($inDate) {
        if (is_array($this->shipping)) {
            $this->shipping['delivery-date'] = $inDate;
        }
    }
    
    public function setShippingDays($inDays) {
        if (is_array($this->shipping)) {
            $this->shipping['days'] = $inDays;
        }
    }

    public function getPackagesShipping() {
        return $this->packagesShipping;
    }
    
    public function setPackagesShipping() {
        $arrPackages = [];
        $arrPackages[0] = [
            'id' => "None",
            'length' => 0,
            'width' => 0,
            'height' => 0,
            'cubic' => 0,
            'weight' => 0
        ];
        $arrPackages[1] = [
            'id' => "SmallSquareBox",
            'length' => 12.0,
            'width' => 12.0,
            'height' => 6.0,
            'cubic' => 12.0 * 12.0 * 6.0,
            'weight' => 90
        ];
        $arrPackages[2] = [
            'id' => "MediumSquareBox",
            'length' => 12.0,
            'width' => 12.0,
            'height' => 12.0,
            'cubic' => 12.0 * 12.0 * 12.0,
            'weight' => 120
        ];
        $arrPackages[3] = [
            'id' => "LargeSquareBox",
            'length' => 16.0,
            'width' => 16.0,
            'height' => 16.0,
            'cubic' => 16.0 * 16.0 * 16.0,
            'weight' => 120
        ];
        $this->packagesShipping = $arrPackages;
    }
    
    public function getShipping() {
        return $this->shipping;
    }

    public function getShippingCode() {
        $value = "";
        if (is_array($this->shipping)) {
            $value = $this->shipping['code'];
        }
        return $value;
    }

    public function getShippingID() {
        $value = "";
        if (is_array($this->shipping)) {
            $value = $this->shipping['id'];
        }
        return $value;
    }

    public function getShippingPrice() {
        $value = "";
        if (is_array($this->shipping)) {
            $value = $this->shipping['price'];
        }
        return $value;
    }

    public function getShippingDeliveryDate() {
        $value = "";
        if (is_array($this->shipping)) {
            $value = $this->shipping['delivery-date'];
        }
        return $value;
    }

    public function getShippingDays() {
        $value = "";
        if (is_array($this->shipping)) {
            $value = $this->shipping['days'];
        }
        return $value;
    }
    
    public function setOriginatingPostalCode($inOriginatingPostalCode) {
        $this->originatingPostalCode = $inOriginatingPostalCode;
    }
    private function getOriginatingPostalCode() {
        return $this->originatingPostalCode;
    }
    
    public function checkValues() {
        $check = TRUE;
        if ((!is_array($this->getShippingDetails())) || 
                (!is_array($this->getDestinationInfo())) ||
                (!is_array($this->getHeaders()))) {
            $check = FALSE;
        } else {
            $shippingHeaders = $this->getHeaders();
            foreach ($shippingHeaders as $value) {
                if ($value === "") {
                    $check = FALSE;
                }
            }
            $checkDestinationInfo = $this->getDestinationInfo();
            if ($checkDestinationInfo['countryCode'] === "") {
                $check = FALSE;
            }
            if ($checkDestinationInfo['codeType'] === "") {
                $check = FALSE;
            }
            $serviceCodes = $checkDestinationInfo['serviceCodes'];
            foreach ($serviceCodes as $code) {
                if ($code === "") {
                    $check = FALSE;
                }
            }
            if ($this->getUsername() === "") {
                $check = FALSE;
            }
            if ($this->getPassword() === "") {
                $check = FALSE;
            }
            if ($this->getAccountNumber() === "") {
                $check = FALSE;
            }
        }
        return $check;
    }
    
    private function setHeaders() {
        $arr = ['Accept:application/vnd.cpc.ship.rate-v4+xml',
            'Content-Type:application/vnd.cpc.ship.rate-v4+xml',
            'Accept-Language:en-CA'
            ];
        $this->headers = $arr;
    }
    private function getHeaders() {
        $arr = [];
        if (is_array($this->headers)) {
            $arr = $this->headers;
        }
        return $arr;
    }
    private function setUsername() {
        //DEVELOPMENT
        $this->username = '947574caacdb8e53';
    }
    private function getUsername() {
        //DEVELOPMENT
        return $this->username;
    }
    private function setPassword() {
        //DEVELOPMENT
        $this->password = '6de805cca0fd404ca2b0e6';
    }
    private function getPassword() {
        //DEVELOPMENT
        return $this->password;
    }
    private function setAccountNumber() {
        $this->accountNumber = '0008383357';
    }
    private function getAccountNumber() {
        return $this->accountNumber;
    }
    public function setDestinationInfo($inLocation) {
        switch ($inLocation) {
            case "US":
                $this->destinationInfo = [
                    'countryCode' => 'united-states',
                    'codeType' => 'zip-code',
                    'serviceCodes' => [
                        '<service-code>USA.TP</service-code>',
                        '<service-code>USA.EP</service-code>',
                        '<service-code>USA.XP</service-code>'
                    ]
                ];
                break;
            default:
                $this->destinationInfo = [
                    'countryCode' => 'domestic',
                    'codeType' => 'postal-code',
                    'serviceCodes' => [
                        '<service-code>DOM.RP</service-code>', 
                        '<service-code>DOM.XP</service-code>', 
                        '<service-code>DOM.PC</service-code>'
                    ]
                ];
                break;
        }
    }
    public function getDestinationInfo() {
        $arr = [];
        if (is_array($this->destinationInfo)) {
            $arr = $this->destinationInfo;
        }
        return $arr;
    }
    public function setXMLData($packageWeight, $packageBoxIndex, $postalZip) {
        if (!$this->checkValues()) {
            die ('Please set all data before call to function');
        }
        $shippingIndex = $this->getShippingBoxIndex($packageBoxIndex);
        $shippingPackages = $this->getPackagesShipping();
        $shippingPack = $shippingPackages[$shippingIndex];
        $packageLength = $shippingPack['length'];
        $packageWidth = $shippingPack['width'];
        $packageHeight = $shippingPack['height'];
        $getDate = new DateTime('today');
        $getDate->modify("+4 days");
        $tomorrow = $getDate->format('Y-m-d');
        $serviceCodes = $this->getDestinationInfo()['serviceCodes'];
        if (!is_array($serviceCodes)) {
            die('Invalid Service Coded'); 
        }
        $xml_data = '
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
<customer-number>' . $this->getAccountNumber() . '</customer-number>
<quote-type>commercial</quote-type>
<expected-mailing-date>' . $tomorrow . '</expected-mailing-date>
<parcel-characteristics>
    <weight>' . $packageWeight . '</weight>
    <dimensions>
        <length>' . $packageLength . '</length>
        <width>' . $packageWidth . '</width>
        <height>' . $packageHeight . '</height>
    </dimensions>
    <unpackaged>false</unpackaged>
</parcel-characteristics>
<services>';
        foreach ($serviceCodes as $serviceCode) {
            $xml_data .= $serviceCode . "\n";
        }
        $xml_data .= '
</services>
<origin-postal-code>' . $this->getOriginatingPostalCode() . '</origin-postal-code>
<destination>
    <' . $this->destinationInfo['countryCode'] . '>
        <' . $this->destinationInfo['codeType'] . '>' . $postalZip . '</' . $this->destinationInfo['codeType'] .'>
    </' . $this->destinationInfo['countryCode'] . '>
</destination>
</mailing-scenario>
';
        $this->xmlData = $xml_data;
    }
    private function getXMLData() {
        if ($this->xmlData === "") {
            die('Please set the XML data.');
        }
        return $this->xmlData;
    }
    
    public function doProcess() {
        $debug = FALSE;
        $services = [];
        if ($this->checkValues()) {
            $service_url = "https://ct.soa-gw.canadapost.ca/rs/ship/price";
            $curl = curl_init($service_url); // Create REST Request
            // Set Options
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
            $certFile = dirname(__FILE__) . '/cacert.pem';
            curl_setopt($curl, CURLOPT_CAINFO, $certFile); // Mozilla cacerts
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->getUsername() . ':' . $this->getPassword());
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($curl, CURLOPT_POST, 1); 
            // Apply the XML to our curl call 
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getXMLData()); 

            /* @var $curl_response type */
            $curl_response = curl_exec($curl); // Execute REST Request
            if(curl_errno($curl)){
                echo 'Curl error: ' . curl_error($curl) . "\n";
                die ('Curl error: ' . curl_error($curl));
            }
            $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($httpCode === 200) {
                $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$curl_response) . '</root>');
                if (!$xml) {
                    echo 'Failed loading XML' . "\n";
                    echo $curl_response . "\n";
                    foreach(libxml_get_errors() as $error) {
                        echo "\t" . $error->message;
                    }
                } else {
                    //preOut($xml);
                    if($xml && $xml->{'price-quotes'}) {
                        $priceQuotes = $xml->{'price-quotes'}->children('http://www.canadapost.ca/ws/ship/rate-v3');
                        if($priceQuotes->{'price-quote'}) {
                            $iterCtr = 0;
                            foreach($priceQuotes as $priceQuote) {
                                $services[$iterCtr] = [
                                    'code' => (string)$priceQuote->{'service-code'},
                                    'id' => (string)$priceQuote->{'service-name'},
                                    'price' => (float)$priceQuote->{'price-details'}->{'due'},
                                    'days' => (int)$priceQuote->{'service-standard'}->{'expected-transit-time'},
                                    'delivery-date' => (string)$priceQuote->{'service-standard'}->{'expected-delivery-date'}
                                ];
                                $iterCtr++;
                            }
                        }
                    }
                    if ($debug) {
                        var_dump($services);
                    }
                }
            } else {
                $services = [
                    'ERROR', 
                    $httpCode, 
                    'There was an error returned by the Canada Post Server.',
                    $curl_response
                ];
            }
        }
        return $services;
    }
    
    public function getShippingBoxIndex($inPackageID) {
        $index = 0;
        $shippingBoxes = $this->getPackagesShipping();
        for ($ctr = 0; $ctr < count($shippingBoxes); $ctr++) {
            $box = $shippingBoxes[$ctr];
            if ($box['id'] === $inPackageID) {
                $index = $ctr;
                break;
            }
        }
        return $index;
    }
}
