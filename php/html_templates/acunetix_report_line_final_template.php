<?php

$html = "
    <div class='nodedata'>
     <div class='panel-group'>
      <div class='panel panel-default'>
       <div class='panel-heading'>
        <h4 class='panel-title'>
         <span>
          <span style='margin-left: 30px;'>" . $plugin_name ."&#160;&#160;&#160;<font color=\"red\">".$mandatory."</font>
          </span>
        </span>
        <span class='pull-right'>
          <span class=\"" . $badge_collors[$value["left"]["riskLevel"]] . '">' . $risk_factor . "
          </span>
          <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160;Extend  &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span> </a>
        </span>

      </h4>
    </div>


    <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
      <ul class='list-group'>
       <li class='list-group-item'>
        <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . '" /><strong>Description: </strong> ' . $description . " </li>
        <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . '" /><strong>
         Information:&#160;  </strong>' . $fixInformation . "<br />  </li>

         <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . '" /><strong>CVE:&#160;</strong>' . $cve_strings . '
         </li>
         <li class=\'list-group-item \'><strong>Commentaries:  </strong>'.$comment.'</li>
       </ul>
     </div>
   </div>
 </div>
</div>';

?>