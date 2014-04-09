<?php  // $Id: list_activites_users.php,v 1.0 2014/03/25 00:00:00 jfruitet Exp $
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 2005 Martin Dougiamas  http://dougiamas.com             //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////


// referentiel : list_activites_users.php
// récupère et affiche une liste d'activités en utilisant des appels Ajax

require_once('../../config.php');
include('print_lib_activite.php');	// AFFICHAGES
include('lib_task.php');
include('print_lib_task.php');	// AFFICHAGES TACHES

$instanceid   = optional_param('instanceid', 0, PARAM_INT);   // referentiel instance id
$userid       = optional_param('userid', 0, PARAM_INT);   // userid if selected
$sql          = optional_param('sql','', PARAM_TEXT);
$lparams      = optional_param('lparams','', PARAM_TEXT);
$pageNo       = optional_param('pageNo', 0, PARAM_INT);
$perPage      = optional_param('perPage', 1, PARAM_INT);
$selacc       = optional_param('selacc', 0, PARAM_INT);
$modeaff      = optional_param('modeaff', 0, PARAM_INT);
$order    	  = optional_param('order', 0, PARAM_INT);

	if ($modeaff==1){
		$mode='listactivityall';
	}
	else if ($modeaff==2){
        $mode='listactivity';
	}
	else{
        $mode='updateactivity';
	}

// DEBUG
// echo "DEBUG :: list_activites_users.php :: 42 :: <br> $instanceid , ".htmlspecialchars($sql).",$lparams, $pageNo, $perPage\n";
// echo "<br>MODEAFF : $modeaff\n";
// echo "<br>SELECT_ACC : $selacc\n";
//exit;

    $url = new moodle_url('/mod/referentiel/list_activites_users.php');
	if ($instanceid) {     // referenteil_referentiel_id
        if (! $referentiel = $DB->get_record("referentiel", array("id" => "$instanceid"))) {
            print_error('Referentiel instance is incorrect');
        }
        if (! $referentiel_referentiel = $DB->get_record("referentiel_referentiel", array("id" => "$referentiel->ref_referentiel"))) {
            print_error('Referentiel id is incorrect');
        }

		if (! $course = $DB->get_record("course", array("id" => "$referentiel->course"))) {
	            print_error('Course is misconfigured');
    	}

		if (! $cm = get_coursemodule_from_instance('referentiel', $referentiel->id, $course->id)) {
    	        print_error('Course Module ID is incorrect');
		}
        $url->param('instanceid', $instanceid);
    }
	else{
		print_error(get_string('erreurscript','referentiel','Erreur01 : list_activites_users.php'), 'referentiel');
	}

    $contextcourse = get_context_instance(CONTEXT_COURSE, $course->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
	$PAGE->set_context($context);

    // Requête
    if (!empty($sql)){
	    //echo "<br />DEBUG :: 46 :: Length : ".strlen($sql)." :  ".htmlspecialchars($sql)."\n";
    	//$sql = stripslashes(urldecode($sql));
        $sql = stripslashes($sql);
	    //echo "<br />DEBUG :: 48 :: Length : ".strlen($sql)." :  ".htmlspecialchars($sql)."\n";
		$sql = str_replace('&gt;','>',$sql);    // hack
        $sql = str_replace('&lt;','<',$sql);    // hack

    	//echo "<br />DEBUG :: 95 :: Length : ".strlen($sql)." : ".htmlspecialchars($sql)."\n";
    }

    if (!empty($lparams)){
		$params=explode('|',$lparams);
	}


	// DEBUG
    //echo "<br>DEBUG :: list_activites_users.php :: 697 :: Params<br />\n";
	//print_object($params);
	$deb= ($pageNo-1) * $perPage;
	$fin=  $deb + $perPage;
	$limit = ' LIMIT '.$deb.', '.$fin;
    $sql.=$limit;
    // echo "<br />DEBUG :: lib_activites_users.php :: 102 :: Length : ".strlen($sql)." <br /> ".htmlspecialchars($sql)."\n";

    if ($recs=$DB->get_records_sql($sql, $params)){
		// DEBUG
		//echo "<br />DEBUG :: list_activites_users.php :: 113 : RECORD<br />\n";
		//print_object( $recs);

		// affichage
		// preparer les variables globales pour Overlib
        // DEBUG
		//echo "<br>DEBUG :: 696:: CROISSANT : $order<br>\n";
		if (!empty($order)) {
        	$recs=referentiel_order_users($recs, $order);
 			//echo "<br />DEBUG :: list_activites_users.php :: 122 : RECORD TRIES<br />\n";
			//print_object( $recs);
			//exit;
		}
		referentiel_initialise_descriptions_items_referentiel($referentiel_referentiel->id);

        $userid_old=0;  // pour la jauge
        if ($modeaff==0){
			// formulaire global
			//echo "\n\n".'<form name="form" id="form" action="activite.php?id='.$cm->id.'&course='.$course->id.'&mode='.$mode.'&filtre_auteur='.$data_filtre->filtre_auteur.'&filtre_validation='.$data_filtre->filtre_validation.'&filtre_referent='.$data_filtre->filtre_referent.'&filtre_date_modif='.$data_filtre->filtre_date_modif.'&filtre_date_modif_student='.$data_filtre->filtre_date_modif_student.'&select_acc='.$select_acc.'&sesskey='.sesskey().'" method="post">'."\n";
            echo "\n\n".'<form name="form" id="form" action="activite.php?id='.$cm->id.'&course='.$course->id.'&mode='.$mode.'&sesskey='.sesskey().'" method="post">'."\n";
            echo '<table class="activite" width="100%">'."\n";
			echo '<tr valign="top">
<td class="ardoise" colspan="8">
 <img class="selectallarrow" src="./pix/arrow_ltr_bas.png" width="38" height="22" alt="Pour la sélection :" />
 <i>'.get_string('cocher_enregistrer', 'referentiel').'</i>
<input type="submit" value="'.get_string("savechanges").'" />
<input type="reset" value="'.get_string("corriger", "referentiel").'" />
<input type="submit" name="cancel" value="'.get_string("quit", "referentiel").'" />
</td></tr>'."\n";

			foreach($recs as $record_a){
				//print_object($record_a);
            	//echo "<br />\n";
				//echo '<tr valign="top"><td class="ardoise" colspan="9">'."\n";
    			//echo '<input type="text" name="nom" value="" />'."\n";
				//echo '</td></tr>'."\n";
			    // Jauge d'activite
				if ($userid_old!=$record_a->userid){
                    $userid_old=$record_a->userid;
					echo '<tr><td class="centree" colspan="8">'."\n";
                    echo get_string('competences_declarees','referentiel', '<span class="bold">'.referentiel_get_user_info($record_a->userid).'</span>')."\n".referentiel_print_jauge_activite($record_a->userid, $referentiel_referentiel->id)."\n";
					echo '</td></tr>'."\n";
				}
    			echo referentiel_edit_activite_detail($context, $cm->id, $course->id, $mode, $record_a, true);
        	}
    		echo '<tr valign="top">
<td class="ardoise" colspan="8">
 <img class="selectallarrow" src="./pix/arrow_ltr.png"
    width="38" height="22" alt="Pour la sélection :" />
<i>'.get_string('cocher_enregistrer', 'referentiel').'</i>
<input type="hidden" name="action" value="modifier_activite_global" />
<input type="hidden" name="pageNo" value="'.$pageNo.'" />
<!-- accompagnement -->
<input type="hidden" name="select_acc" value="'.$selacc.'" />
';
			if (!empty($userid)){
				echo '<input type="hidden" name="userid" value="'.$userid.'" />'."\n";
			}
			echo '
<!-- These hidden variables are always the same -->
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="modulename"    value="referentiel" />
<input type="hidden" name="mode"          value="'.$mode.'" />
<input type="submit" value="'.get_string("savechanges").'" />
<input type="reset" value="'.get_string("corriger", "referentiel").'" />
<input type="submit" name="cancel" value="'.get_string("quit", "referentiel").'" />
</td></tr>
</table>
</form>'."\n";
		}
        else{
			// affichage
			foreach($recs as $record_a){
                // Jauge d'activite
				if ($userid_old!=$record_a->userid){
                    $userid_old=$record_a->userid;
					echo '<div align="center">'.get_string('competences_declarees','referentiel', '<span class="bold">'.referentiel_get_user_info($record_a->userid).'</span>')."\n".referentiel_print_jauge_activite($record_a->userid, $referentiel_referentiel->id).'</div>'."\n";
				}
                referentiel_print_activite_detail($record_a, $context, ($modeaff==1));
                if ($record_a->ref_course==$course->id){
                	referentiel_menu_activite($cm, $context, $record_a->id, $record_a->userid, $referentiel->id, $record_a->approved, $selacc, ($modeaff==1), $mode);
	                if (!$record_a->approved){
    	           		echo '<div align="center">'.referentiel_ajout_document($record_a, $mode, $selacc)."</div>\n";
					}
                }
				else{
                    echo '<div align="center">'.get_string('activite_exterieure','referentiel')."</div>\n";
				}
				echo '<br />'."\n";
			}
        }
    }
    
?>
