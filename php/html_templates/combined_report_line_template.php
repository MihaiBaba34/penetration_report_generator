<?php

 $html = "
      <div class='nodedata'>
       <div class='panel-group'>
        <div class='panel panel-default'>
         <div class='panel-heading' style=\"background: #ECEEEF\">
          <h4 class='panel-title'>
           <span>
            <input id='supplied' class='checkrecord combinedLeftCheckbox' type='checkbox' value=\"" . $i . "\" name=\"" . $reportPositionInVector . "\" checked=\"checked\" />
            <span style='margin-left: 30px;'>" . $plugin_name . "
            </span>
          </span>
          <span class='pull-right'>
            <span class=\"" . $badge_collors[$i] . '">' . $risk_factor . "
            </span>
            <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160; Extend    &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span> &#160;</a>
            <input id='supplied' class='checkrecord combinedRightCheckbox' type='checkbox' value=\"" . $i . "\" name=\"" . $reportPositionInVector . "\" " . $mandatory_item . " />

          </span>
        </h4>
      </div>

      <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
        <ul class='list-group'>
         <li class='list-group-item'>
          <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . '" /><strong>Description: </strong> ' . $description . " </li>
          <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . '" /><strong>Fix
           Information:&#160;  </strong>' . $fixInformation . "<br />  </li>
           <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $exploit . '" /><strong>Exploit: &#160;</strong>' . $exploit . "
           </li>
           <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . '" /><strong>CVE: &#160;</strong>' . $cve_strings . '
           </li>

          <li class=\'list-group-item \'><strong>Commentaries:  </strong><input type=\'text\' class=\'combined_comments\'></input> </li>

         </ul>
       </div>
     </div>
   </div>
 </div>';

?>
