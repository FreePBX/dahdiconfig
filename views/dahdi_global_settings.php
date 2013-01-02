<form id="form-globalsettings" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=globalsettingssubmit">
<div id="global">
    <h2>Global Settings</h2>
    <hr />
    <table width="100%" style="text-align:left;">
        <tr>
            <td style="width:10px;">
                <label for="tone_region"><a href="#" class="info">Tone Region:<span>Please choose your country or your nearest neighboring country for default Tones (Ex: dialtone, busy tone, ring tone etc.)</span></a></label>
                
            </td>
            <td>
                <select id="tone_region" name="tone_region">
            		<option value="us" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'us'); ?>>United States/North America</option>
            		<option value="au" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'au'); ?>>Australia</option>
            		<option value="fr" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'fr'); ?>>France</option>
            		<option value="nl" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'nl'); ?>>Netherlands</option>
            		<option value="uk" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'uk'); ?>>United Kingdom</option>
            		<option value="fi" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'fi'); ?>>Finland</option>
            		<option value="es" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'es'); ?>>Spain</option>
            		<option value="jp" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'jp'); ?>>Japan</option>
            		<option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'no'); ?>>Norway</option>
            		<option value="at" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'at'); ?>>Austria</option>
            		<option value="nz" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'nz'); ?>>New Zealand</option>
            		<option value="it" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'it'); ?>>Italy</option>
            		<option value="us-old" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'us-old'); ?>>United States Circa 1950 / North America</option>
            		<option value="gr" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'gr'); ?>>Greece</option>
            		<option value="tw" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'tw'); ?>>Taiwan</option>
            		<option value="cl" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'cl'); ?>>Chile</option>
            		<option value="se" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'se'); ?>>Sweden</option>
            		<option value="be" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'be'); ?>>Belgium</option>
            		<option value="sg" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'sg'); ?>>Singapore</option>
            		<option value="il" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'il'); ?>>Israel</option>
            		<option value="br" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'br'); ?>>Brazil</option>
            		<option value="hu" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'hu'); ?>>Hungary</option>
            		<option value="lt" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'lt'); ?>>Lithuania</option>
            		<option value="pl" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'pl'); ?>>Poland</option>
            		<option value="za" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'za'); ?>>South Africa</option>
            		<option value="pt" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'pt'); ?>>Portugal</option>
            		<option value="ee" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ee'); ?>>Estonia</option>
            		<option value="mx" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'mx'); ?>>Mexico</option>
            		<option value="in" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'in'); ?>>India</option>
            		<option value="de" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'de'); ?>>Germany</option>
            		<option value="ch" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ch'); ?>>Switzerland</option>
            		<option value="dk" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'dk'); ?>>Denmark</option>
            		<option value="cz" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'cz'); ?>>Czech Republic</option>
            		<option value="cn" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'cn'); ?>>China</option>
            		<option value="ar" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ar'); ?>>Argentina</option>
            		<option value="my" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'my'); ?>>Malaysia</option>
            		<option value="th" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'th'); ?>>Thailand</option>
            		<option value="bg" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'bg'); ?>>Bulgaria</option>
            		<option value="ve" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ve'); ?>>Venezuela</option>
            		<option value="ph" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ph'); ?>>Philippines</option>
            		<option value="ru" <?php echo set_default($dahdi_cards->get_globalsettings('tone_region'),'ru'); ?>>Russian Federation</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="language"><a href="#" class="info">Select Language:<span>Specify the language.</span></a></label>
            </td>
            <td>
                <select id="language" name="language">
            	    <option value="en">English</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="busydetect"><a href="#" class="info">Enable Busy Detect:<span>On trunk interfaces (FXS) and E&amp;M interfaces (E&amp;M, Wink, Feature Group D etc) it can be useful to perform busy detection either in an effort to <br/>detect hangup or for detecting busies.  This enables listening for the beep-beep busy pattern.</span></a></label>
        	</td>
        	<td>
        	    <select id="busydetect" name="busydetect">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('busydetect'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('busydetect'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="busycount"><a href="#" class="info">Busy Detect Count:<span> If busydetect is enabled, it is also possible to specify how many busy tones to wait for before hanging up.  The default is 3, but it might be safer to set to 6 or even 8.  Mind that the higher the number, the more time that will be needed to hangup a channel, but lowers the probability that you will get random hangups.</span></a></label>
            </td>
            <td>
                <select id="busycount" name="busycount">
            	    <?php for($i=0; $i<=100; $i++) { ?>
            	    <option value="<?php echo $i; ?>" <?php echo set_default($dahdi_cards->get_globalsettings('busycount'),$i); ?>><?php echo $i; ?></option>
            	    <?php } ?>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
            	<label for="usecallerid"><a href="#" class="info">Use Caller ID:<span>Whether or not to use caller ID</span></a></label>
            </td>
            <td>
                <select id="usecallerid" name="usecallerid">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('usecallerid'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('usecallerid'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="callwaiting"><a href="#" class="info">Enable Call Waiting<span>Whether or not to enable call waiting on internal extensions. With this set to 'yes', busy extensions will hear the call-waiting tone, and can use hook-flash to switch between callers. The Dial() app will not return the "BUSY" result for extensions.</span></a></label>
            </td>
            <td>
                <select id="callwaiting" name="callwaiting">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('callwaiting'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('callwaiting'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="usecallingpres"><a href="#" class="info">Use Caller ID Presentation<span>Whether or not to use the caller ID presentation for the outgoing call that the calling switch is sending.</span></a></label>
            </td>
            <td>
                <select id="usecallingpres" name="usecallingpres">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('usecallingpres'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('usecallingpres'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="threewaycalling"><a href="#" class="info">Enable Three Way Calling<span>Support three-way calling</span></a></label>
            </td>
            <td>
                <select id="threewaycalling" name="threewaycalling">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('threewaycalling'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('threewaycalling'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="transfer"><a href="#" class="info">Enable Transfer<span><strong>For FXS ports (either direct analog or over T1/E1):</strong><br/>
                    Support flash-hook call transfer (requires three way calling) <br/>
                    Also enables call parking (overrides the 'canpark' parameter) <br/>
                    <br/>
                    <strong>For digital ports using ISDN PRI protocols:</strong><br/>
                    Support switch-side transfer (called 2BCT, RLT or other names)<br/>
                    This setting must be enabled on both ports involved, and the 'facilityenable' setting must also be enabled to allow sending the transfer to the ISDN switch, since it sent in a FACILITY message.</span></a></label>
            </td>
            <td>
               	<select id="transfer" name="transfer">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('transfer'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('transfer'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr> 
        <tr>
            <td style="width:10px;">
                <label for="cancallforward"><a href="#" class="info">Enable Call Forwarding<span>Support call forward variable</span></a></label>
            </td>
            <td>
                <select id="cancallforward" name="cancallforward">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('cancallforward'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('cancallforward'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="callreturn"><a href="#" class="info">Enable Call Return<span>Whether or not to support Call Return (*69, if your dialplan doesn't catch this first)</span></a></label>
            </td>
            <td>
               	<select id="callreturn" name="callreturn">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('callreturn'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('callreturn'),'no'); ?>>No</option>
            	</select> 
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="echocancel"><a href="#" class="info">Enable Echo Canceling<span>Enable echo cancellation <br/>
                    Note that if any of your DAHDI cards have hardware echo cancelers, then this setting only turns them on and off. There are no special settings required for hardware echo cancelers; when present and enabled in their kernel modules, they take precedence over the software echo canceler compiled into DAHDI automatically.</span></a></label>
            </td>
            <td>
                <select id="echocancel" name="echocancel">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('echocancel'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('echocancel'),'no'); ?>>No</option>
            	</select>
            </td>  
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="echocancelwhenbridged"><a href="#" class="info">Enable EC when bridged<span>Generally, it is not necessary (and in fact undesirable) to echo cancel when the circuit path is entirely TDM.  You may, however, change this behavior by enabling the echo canceler during pure TDM bridging below.</span></a></label>
            </td>
            <td>
                <select id="echocancelwhenbridged" name="echocancelwhenbridged">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('echocancelwhenbridged'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('echocancelwhenbridged'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="echotraining"><a href="#" class="info">Enable Echo Training<span>In some cases, the echo canceller doesn't train quickly enough and there is echo at the beginning of the call.  Enabling echo training will cause DAHDI to briefly mute the channel, send an impulse, and use the impulse response to pre-train the echo canceller so it can start out with a much closer idea of the actual echo.  Value may be "yes", "no", or a number of milliseconds to delay before training (default = 400)<br />
                <br/>
                <strong>WARNING:</strong>In some cases this option can make echo worse!  If you are trying to debug an echo problem, it is worth checking to see if your echo is better with the option set to yes or no.  Use whatever setting gives the best results.<br/>
                <br/>
                Note that these parameters do not apply to hardware echo cancellers.</span></a></label>
            </td>
            <td>
                <input type="text" name="echotraining" id="echotraining" value="<?php echo $dahdi_cards->get_globalsettings('echotraining'); ?>">
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="immediate"><a href="#" class="info">Answer Immediately<span>Specify whether the channel should be answered immediately or if the simple switch should provide dialtone, read digits, etc.<br/>
                    Note: If yes the dialplan execution will always start at extension 's' priority 1 regardless of the dialed number!</span></a></label>
            </td>
            <td>
                <select id="immediate" name="immediate">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('immediate'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('immediate'),'no'); ?>>No</option>
            	</select>
            </td>
        </tr>
        <tr>
            <td style="width:10px;">
                <label for="faxdetect"><a href="#" class="info">Fax Detection<span>For fax detection</span></a></label>
            </td>
            <td>
              	<select id="faxdetect" name="faxdetect">
            	    <option value="yes" <?php echo set_default($dahdi_cards->get_globalsettings('faxdetect'),'yes'); ?>>Yes</option>
            	    <option value="no" <?php echo set_default($dahdi_cards->get_globalsettings('faxdetect'),'no'); ?>>No</option>
            	    <option value="incoming" <?php echo set_default($dahdi_cards->get_globalsettings('faxdetect'),'incoming'); ?>>Incoming</option>
            	    <option value="outgoing" <?php echo set_default($dahdi_cards->get_globalsettings('faxdetect'),'outgoing'); ?>>Outgoing</option>
            	</select>
            </td>
        </tr> 
        <tr>
            <td style="width:10px;"> 
                <label for="rxgain"><a href="#" class="info">Receive Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
            </td>
            <td>
                <input type="text" name="rxgain" id="rxgain" value="<?php echo $dahdi_cards->get_globalsettings('rxgain'); ?>">
            </td>
        </tr>
        <tr>
            <td style="width:10px;"> 
                <label for="txgain"><a href="#" class="info">Transmit Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
            </td>
            <td>
                <input type="text" name="txgain" id="txgain" value="<?php echo $dahdi_cards->get_globalsettings('txgain'); ?>">
            </td>
        </tr>
    </table>
    <?php
    $gs = $dahdi_cards->get_all_globalsettings();
    foreach($gs as $key => $value) {
        if(!in_array($key,$dahdi_cards->original_global) && !strpos($key, 'checkbox')) {
            $dh_key = $key;
            $dh_val = $value;
            unset($gs[$key]);
            break;
        }
    }
    ?>
    <table width="100%" style="text-align:left;">
        <tr id="dh_global_additional_0">
            <td style="width:10px;vertical-align:top;">
                <label>Other Global Dahdi Settings: </label>
            </td>
            <td style="vertical-align:middle;">
                <a href="#" onclick="dh_global_delete_field(0)"><img height="10px" src="assets/dahdiconfig/images/delete.png"></a>
                <input type="hidden" name="dh_global_add[]" value="0" />
                <input type="hidden" id="dh_global_origsetting_key_0" name="dh_global_origsetting_key_0" value="<?php echo $dh_key?>" />
                <input id="dh_global_setting_key_0" name="dh_global_setting_key_0" value="<?php echo $dh_key?>" /> =
                <input id="dh_global_setting_value_0" name="dh_global_setting_value_0" value="<?php echo $dh_val?>" /> <br />
            </td>
        </tr>
        <?php
        $a = 1;
        foreach($gs as $key => $value) {
            if(!in_array($key,$dahdi_cards->original_global)) {
                ?>
                <tr id="dh_global_additional_<?php echo $a?>">
                    <td style="width:10px;vertical-align:top;">
                    </td>
                    <td style="vertical-align:middle;">
                        <a href="#" onclick="dh_global_delete_field(<?php echo $a?>)"><img height="10px" src="assets/dahdiconfig/images/delete.png"></a>
                        <input type="hidden" name="dh_global_add[]" value="<?php echo $a?>" />
                        <input type="hidden" id="dh_global_origsetting_key_<?php echo $a?>"name="dh_global_origsetting_key_<?php echo $a?>" value="<?php echo $key?>" />
                        <input id="dh_global_setting_key_<?php echo $a?>" name="dh_global_setting_key_<?php echo $a?>" value="<?php echo $key?>" /> =
                        <input id="dh_global_setting_value_<?php echo $a?>" name="dh_global_setting_value_<?php echo $a?>" value="<?php echo $value?>" /> <br />
                    </td>
                </tr>
                <?php
                $a++;
            }
        }
        ?>
        <tr id="dh_global_add">
            <td> 
            </td>
            <td>
                <input type="button" value="Add Field" onclick="dh_global_add_field(<?php echo $a?>)">
            </td>
        </tr>
    </table>
</div>
</form>