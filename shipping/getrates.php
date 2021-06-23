<?php
    require_once( './config.php' );
    require_once( './header.php' );
    require_once('./ShippingRates.php');

    $startPostal = filter_input(INPUT_POST, "startpostal");
    $endPostal = filter_input(INPUT_POST, "endpostal");
    $postalCode = $endPostal;
    $grams = filter_input(INPUT_POST, "weight");
    $weight = round(floatval(intval($grams)/1000), 3);
    $package = filter_input(INPUT_POST, "package");

    $arrShipQuotes = [];
    $shippingRates = new ShippingRates();
    $shippingRates->setDestinationInfo("CA");
    $shippingRates->setOriginatingPostalCode($startPostal);
    $shippingRates->setXMLData($weight, $package, $endPostal);
    if ($shippingRates->checkValues()) {
        $rates = $shippingRates->doProcess();
        if (is_array($rates)) {
            if ($rates[0] !== "ERROR") {
                for ($rCtr = 0; $rCtr < count($rates); $rCtr++) {
                    $arrShipQuotes[$rCtr] = [
                        'code' => $rates[$rCtr]['code'],
                        'id' => $rates[$rCtr]['id'],
                        'price' => floatval($rates[$rCtr]['price']),
                        'delivery-date' => $rates[$rCtr]['delivery-date'],
                        'days' => $rates[$rCtr]['days']
                    ];
                }                        
                $processRates = TRUE;
            } else {
                $showError = TRUE;
            }
        }
    } else {
        die ("CheckValues Failed");
    }
    
?>
<article id="shipping">
    <div>
<?php
    $handling = 0;
    if ($processRates) {
        $getDate = new DateTime('tomorrow + 1day');
        $tomorrow = $getDate->format('Y-m-d');
?>
        <h3>Formatted output of Data</h3>
        <div id="delivery-info">
            <div id="delivery-head">
                <span>Delivery Choices</span>
            </div>
            <div id="delivery-body">
                <table style="width: 98%; padding: 8px;">
                    <tbody>
                        <tr>
                            <td colspan="5">
                                Shipment weight: <?php echo $grams; ?>g
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                Shipment Date: <?php echo $tomorrow; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                Ship to Postal Code/Zip: <?php echo $postalCode; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="spacer">&nbsp;</td>
                        </tr>
                        <tr class="heading">
                            <td>Service Type</td>
                            <td>Price</td>
                            <td>Delivery Date</td>
                            <td>Days</td>
                        </tr>
                        <?php
        $size = count($rates);
        for ($sCtr=0; $sCtr < $size; $sCtr++) {
            $price = $handling + floatval($rates[$sCtr]['price']);
?>
                        <tr>
                            <td style="text-align: left;"><?php echo $rates[$sCtr]['id']; ?></td>
                            <td style="text-align: right;">$<?php echo number_format($price, 2); ?></td>
                            <td><?php echo $rates[$sCtr]['delivery-date']; ?></td>
                            <td><?php echo $rates[$sCtr]['days']; ?></td>
                        </tr>
                        <?php
        }
?>
                    </tbody>
                </table>
            </div>
        </div>
        <h3>Here is the parsed XML Data that was returned.</h3>
        <code><pre>
<?php
var_dump($arrShipQuotes);
    }
?>
            </pre></code>
        <p><a href="<?= $base_url; ?>/shipping/index.php">Try another shipment.</a></p>
    </div>
</article>
<?php
require_once( './footer.php' );
