<?php

$HTMLCombinedAntet = '
  <div class="alert alert-info" role="alert" style="background: #ECEEEF; border:0;">
    <h3 class="text-center"><strong>Infrastructure Vulnerabilities</strong></h3>
  </div>
  <br />
  <table class="table ">
    <thead >
      <tr>
        <th style="background: #DF0803;">Critical</th>
        <th style="background: #FF4D00;">High</th>
        <th style="background: #E99F54;">Medium</th>
        <th style="background: #008800;">Low</th>
        <th style="background: #337AB7;">Info</th>
        <th class="table-active">Total</th>


      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="Critical" style="background: #DF0803;  font-weight: bold; font-size: 160%;">' . $risk_counters['Critical'] . '</td>
        <td id="High" style="background: #FF4D00;  font-weight: bold; font-size: 160%;">' . $risk_counters['High'] . '</td>
        <td id="Medium" style="background: #E99F54; font-weight: bold; font-size: 160%;">' . $risk_counters['Medium'] . '</td>
        <td id="Low" style="background: #008800;  font-weight: bold; font-size: 160%;">' . $risk_counters['Low'] . '</td>
        <td id="Informational" style="background: #337AB7; font-weight: bold; font-size: 160%;">' . $risk_counters['Information'] . '</td>
        <td id="Total" class="table-active" style="font-size: 160%; font-weight: bold;" >' . $totalNumberOfIssues . '</td>


      </tr>

    </tbody>
  </table>
  ';

  ?>