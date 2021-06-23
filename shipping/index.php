<?php
    require_once( './config.php' );
    require_once( './header.php' );
?>
<article>
    <h2>RESTFul Web Services Example</h2>
    <p>Canada Post offers a web service for companies to access and get shipping rates directly from their production and development web servers.</p>
    <p>When using this RESTFul Web Service, the developer needs to pass values in an XML file and the service returns back an XML for the developer to parse.</p>
    <p>This example form below will allow you to test the Canada Post web service.</p>
    <form id="restform" class="sub-container" action="<?php echo Config::$base_url; ?>/shipping/getrates.php" method="post">
        <label for="startpostal"><div class="form-label">Starting Postal Code: </div></label>
        <div class="form-input"><input type="text" id="startpostal" name="startpostal" data-required="true"></div>
        
        <label for="endpostal"><div class="form-label">Destination Postal Code: </div></label>
        <div class="form-input"><input type="text" id="endpostal" name="endpostal" data-required="true"></div>
        
        <label for="weight"><div class="form-label">Weight: (grams)</div></label>
        <div class="form-input"><input type="text" id="weight" name="weight" data-required="true"></div>
        
        <label for="package"><div class="form-label">Package Size (cm x cm x cm): </div></label>
        <div class="form-input">
            <select name="package" id="package" data-required="false">
                <option value="SmallSquareBox" selected="selected">Small Square Box (12.0 x 12.0 x 6.0)</option>
                <option value="MediumSquareBox">Medium Square Box (12.0 x 12.0 x 12.0)</option>
                <option value="LargeSquareBox">Large Square Box (16.0 x 16.0 x 16.0)</option>
            </select>
        </div>
        <div>
            <input type="hidden" name="action" value="doShipping"/>
            <input id="submitform" name="submit" type="submit" value="Submit Form" />
        </div>
    </form>
</article>
<?php
require_once( './footer.php' );
