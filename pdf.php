<?php
include("auth_session.php");
require_once "db.php";
ob_start();
					$report_html = "";
 					$table = $con->prepare("SELECT * FROM students where id = ?");
                    $table->bind_param('i',$gx);
                    $gx =  $_SESSION['id'];
                    $table->execute();
                    $result = $table->get_result();

                    if ($result) {
                  # code...
                      while($row = $result->fetch_assoc()) { 
                      	$report_html .= "Names : ".$row['name']." ".$row['surname']."<br>";
                      	}
                      }
                      $table->close();

                  $report_html .= " <h3>End Of Year Examinations Report</h3> <br>";
                  $report_html .= "
                    <table class='table'>
                    <thead class='thead-dark'>
                      <tr>
                        <th scope='col'>Subject</th>
                        <th scope='col'>Symbol</th>
                        <th scope='col'>Possible Mark</th>
                        <th scope='col'>Pupil's Mark</th>
                      </tr>
                    </thead>
                    <tbody>
                  ";
                   ?>
                  <?php

                //Populaling The Table
                    $table = $con->prepare("SELECT * FROM subjects where stuID = ? AND type = ?");
                    $table->bind_param('is',$g,$trm);
                    $g =  $_SESSION['id'];
                    $trm = "Term 3"; 
                    $table->execute();
                    $result = $table->get_result();

                    $grade = "";
                    $remarks = $behavior = $activities = $punctuality = $neatness = $assignments = $health = "";



                    if ($result) {
                  # code...
                      while($row = $result->fetch_assoc()) { 

                        $entry = $con->prepare("SELECT * FROM evaluation WHERE resID = ?");
                        $entry->bind_param('i',$result_id);
                        $result_id = $row['resID'];
                        $entry->execute();
                        $eval_res = $entry->get_result();
                        if ($eval_res) {
                          # code...
                          while ($r = $eval_res->fetch_assoc()) {
                            if ($row['English'] > 79 && $row['English'] <= 100) {
                             $grade = "A";
                            }elseif ($row['English'] > 64 && $row['English'] <= 79) {
                             $grade = "B";
                            }elseif ($row['English'] > 49 && $row['English'] <= 64) {
                             $grade = "C";
                            }elseif ($row['English'] > 29 && $row['English'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['English'];
                            $report_html .= "
                                            <tr>
                                              <th scope='row'>English</th>
                                              <td>$grade</td>
                                              <td>100</td>
                                              <td>$resss</td>
                                            </tr>
                                            ";
                           
                            $grade = "";
                            if ($row['Setswana'] > 79 && $row['Setswana'] <= 100) {
                             $grade = "A";
                            }elseif ($row['Setswana'] > 64 && $row['Setswana'] <= 79) {
                             $grade = "B";
                            }elseif ($row['Setswana'] > 49 && $row['Setswana'] <= 64) {
                             $grade = "C";
                            }elseif ($row['Setswana'] > 29 && $row['Setswana'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['Setswana'];
                            $report_html .= "
                                            <tr>
                              <th scope='row'>Setswana</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                            $grade = "";
                            if ($row['Mathematics'] > 79 && $row['Mathematics'] <= 100) {
                             $grade = "A";
                            }elseif ($row['Mathematics'] > 64 && $row['Mathematics'] <= 79) {
                             $grade = "B";
                            }elseif ($row['Mathematics'] > 49 && $row['Mathematics'] <= 64) {
                             $grade = "C";
                            }elseif ($row['Mathematics'] > 29 && $row['Mathematics'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['Mathematics'];
                             $report_html .= "
                                            <tr>
                              <th scope='row'>Mathematics</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                             $grade = "";
                            if ($row['Science'] > 79 && $row['Science'] <= 100) {
                             $grade = "A";
                            }elseif ($row['Science'] > 64 && $row['Science'] <= 79) {
                             $grade = "B";
                            }elseif ($row['Science'] > 49 && $row['Science'] <= 64) {
                             $grade = "C";
                            }elseif ($row['Science'] > 29 && $row['Science'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['Science'];
                            $report_html .= "
                                            <tr>
                              <th scope='row'>Science</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                            $grade = "";
                            if ($row['CAPA'] > 79 && $row['CAPA'] <= 100) {
                             $grade = "A";
                            }elseif ($row['CAPA'] > 64 && $row['CAPA'] <= 79) {
                             $grade = "B";
                            }elseif ($row['CAPA'] > 49 && $row['CAPA'] <= 64) {
                             $grade = "C";
                            }elseif ($row['CAPA'] > 29 && $row['CAPA'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss  = $row['CAPA'];
                            $report_html .= "
                                            <tr>
                              <th scope='row'>CAPA</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                            $grade = "";
                            if ($row['Social_Studies'] > 79 && $row['Social_Studies'] <= 100) {
                             $grade = "A";
                            }elseif ($row['Social_Studies'] > 64 && $row['Social_Studies'] <= 79) {
                             $grade = "B";
                            }elseif ($row['Social_Studies'] > 49 && $row['Social_Studies'] <= 64) {
                             $grade = "C";
                            }elseif ($row['Social_Studies'] > 29 && $row['Social_Studies'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['Social_Studies'];
                            $report_html .= "
                                            <tr>
                              <th scope='row'>Social_Studies</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                            $grade = "";
                            if ($row['Agriculture'] > 79 && $row['Agriculture'] <= 100) {
                             $grade = "A";
                            }elseif ($row['Agriculture'] > 64 && $row['Agriculture'] <= 79) {
                             $grade = "B";
                            }elseif ($row['Agriculture'] > 49 && $row['Agriculture'] <= 64) {
                             $grade = "C";
                            }elseif ($row['Agriculture'] > 29 && $row['Agriculture'] <= 49) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $resss = $row['Agriculture'];
                            $report_html .= "
                                            <tr>
                              <th scope='row'>Agriculture</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$resss</td>
                            </tr>
                                            ";
                            
                            $allSubs = 0;
                            $allSubs = $row['English'] + $row['Setswana'] + $row['Mathematics'] + $row['Science'] + $row['CAPA'] + $row['Social_Studies'] + $row['Agriculture'];
                            $grade = "";
                            if ((($allSubs/700) * 100) > 79 && (($allSubs/700) * 100)) {
                             $grade = "A";
                            }elseif ((($allSubs/700) * 100) > 64 && (($allSubs/700) * 100)) {
                             $grade = "B";
                            }elseif ((($allSubs/700) * 100) > 49 && (($allSubs/700) * 100)) {
                             $grade = "C";
                            }elseif ((($allSubs/700) * 100) && (($allSubs/700) * 100)) {
                             $grade = "D";
                            }else{
                             $grade = "E";
                            }
                            $perc = (($allSubs/700) * 100);
                            $report_html .= "
                                            <tr>
                              <th scope='row'>Total</th>
                              <td>$grade</td>
                              <td>100</td>
                              <td>$perc %</td>
                            </tr>
                                            ";
                            
                          $remarks = $r['remarks'];
                          $behaviour = $r['behaviour'];
                          $activities = $r['activities'];
                          $punctuality = $r['punctuality'];
                          $neatness = $r['neatness'];
                          $assignments = $r['assignments'];
                          $health = $r['health'];
                          }
                        }
                        
                      }
                    }
                    $report_html .= "
                          </tbody>
                    </table>
                    <h5 class='text-dark text-center'>Teacher's Remarks</h5><br>
                    $remarks
                     ";
                  ?>
                  
                
                <?php
                $report_html .= "
                <h5 class='text-dark text-center'>End of Year Evaluation</h5>
                5 Unsatisfactory 4 Average 3 Fair 2 Good 1 Excellent
                <br>
                           Behavior :$behaviour <br>
                          School Activities :$activities <br>
                          Panctuality :$punctuality <br>
                          Neatness :$neatness <br>
                          Assingmnents and Homework :$assignments <br>
                          Health :$health
                     ";
                 
                          
                          ob_end_flush();
                          require_once __DIR__ . '/vendor/autoload.php';
                          $mpdf = new \Mpdf\Mpdf();
                          $mpdf->WriteHTML($report_html);
                          $mpdf->SetDisplayMode('fullpage');
                          $mpdf->list_indent_first_level = 0;
                          //save the file put which location you need folder/filname
                          $mpdf->Output("report.pdf", 'D');
                          
                          ?>