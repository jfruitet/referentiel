<?php  // $Id:  lib_activite.php,v 1.0 2012/10/05 00:00:00 jfruitet Exp $
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


// Affiche une entete activite
// *****************************************************************
// *
// output string                                                    *
// *****************************************************************
function referentiel_print_entete_activite_complete(){
// Affiche une entete activite complete
$s='';
	$s.='<table class="activite" width="100%"><tr>'."\n";
	$s.='<tr>'."\n";
	$s.='<th>'.get_string('id','referentiel').'</th>';
	$s.='<th>'.get_string('auteur','referentiel').'</th>';
	$s.='<th>'.get_string('course').'</th>';	
	$s.='<th>'.get_string('type_activite','referentiel').'</th>';
	$s.='<th colspan="2">'.get_string('description','referentiel').'</th>';
	$s.='<th rowspan="3">'.get_string('menu','referentiel').'</th>'."\n";		
	$s.='</tr>'."\n";
	$s.='<tr>';	
	$s.='<th colspan="2">'.get_string('liste_codes_competence','referentiel').'</th>';
	$s.='<th>'.get_string('referent','referentiel').'</th>';
	$s.='<th>'.get_string('validation','referentiel').'</th>';
	$s.='<th>'.get_string('date_modif_student','referentiel').'</th>';	
	$s.='<th>'.get_string('date_modif','referentiel').'</th>';
	$s.='</tr>'."\n";
	$s.='<tr>';
	$s.='<th colspan="3">'.get_string('commentaire','referentiel').'</th>';
	$s.='<td colspan="3" class="yellow" align="center">'.get_string('document','referentiel').'</td>';
	$s.='</tr>'."\n";
	return $s;
}

/************************************************************************
 * takes a list of records, a search string,                            *
 * input @param array $records   of users                               *
 *       @param string $search                                          *
 * output null                                                          *
 ************************************************************************/

// Affiche les activites de ce referentiel
function referentiel_menu_activite($context, $activite_id, $referentiel_instance_id, $approved, $select_acc=0, $mode='updateactivity'){
	global $CFG;
	global $OUTPUT;
	$s="";
	$s.='<tr><td align="center" colspan="7">'."\n";
	$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=listactivityall&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="'.$OUTPUT->pix_url('search','referentiel').'" alt="'.get_string('plus', 'referentiel').'" title="'.get_string('plus', 'referentiel').'" /></a>'."\n";
	
	$has_capability=has_capability('mod/referentiel:approve', $context);
	$is_owner=referentiel_activite_isowner($activite_id);
	
	if ($has_capability	or $is_owner){
		if ($has_capability || ($is_owner && !$approved)) {
	        $s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('t/edit').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
		}
    if ($has_capability || ($is_owner && !$approved)) {
		    $s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('t/delete').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
    	}
	}
	// valider
    if ($has_capability){
		if (!$approved){
			$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=approveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('nonvalide','referentiel').'" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>'."\n";
		}
		else{
    		$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=desapproveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('valide','referentiel').'" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>'."\n";
		}
	}
	// commentaires
    if (has_capability('mod/referentiel:comment', $context)){
    	$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=commentactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('feedback','referentiel').'" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>'."\n";
	}
	$s.='</td></tr>'."\n";
	return $s;
}



function referentiel_select_users_activite($record_users, $userid=0, $mode='listactivity', $select_acc=0){
global $cm;
global $course;
$maxcol=MAXBOITESSELECTION;
$s="";
	if ($record_users){
		
		$s.='<div align="center">
		
<form name="form" method="post" action="activite.php?id='.$cm->id.'&amp;action=selectuser">'."\n"; 
		$s.='<table class="selection">'."\n";
		$s.='<tr>';
		$s.='<td>';
		if (($userid=='') || ($userid==0)){
			$s.='<input type="radio" name="userid" id="userid" value="" checked="checked" />'.get_string('tous', 'referentiel').'</td>'."\n";;
		}
		else{
			$s.='<input type="radio" name="userid" id="userid" value="" />'.get_string('tous', 'referentiel').'</td>'."\n";;
		}
		$s.='</tr>';
		$s.='<tr>';
		
		$col=0;
		$lig=0;
		foreach ($record_users as $record_u) {   // liste d'id users
			$user_info=referentiel_get_user_info($record_u->userid);
			if ($record_u->userid==$userid){
				$s.='<td><input type="radio" name="userid" id="userid" value="'.$record_u->userid.'" checked="checked" />'.$user_info.'</td>'."\n";;
			}
			else{
				$s.='<td><input type="radio" name="userid" id="userid" value="'.$record_u->userid.'" />'.$user_info.'</td>'."\n";;
			}
			if ($col<$maxcol){
				$col++;
			}
			else{
				$s.='</tr><tr>'."\n";
				$col=0;
				$lig++;
			}
		}
		if ($lig>0){
			while ($col<$maxcol){
				$s.='<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>'."\n";
				$col++;
			}
		}
			
		$s.='<td>&nbsp; &nbsp; &nbsp; <input type="submit" value="'.get_string('select', 'referentiel').'" /></td>';
		$s.='
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />
</tr></table>
</form>
</div>'."\n";
	}
	return $s;
}


// Affiche une entete activite
// *****************************************************************
// *
// output string                                                    *
// *****************************************************************

function referentiel_print_entete_activite(){
// Affiche une entete activite
$s='';
	$s.='<table class="activite" width="100%">'."\n";
	$s.='<tr>';
	$s.='<th>'.get_string('id','referentiel').'</th>';
	$s.='<th>'.get_string('auteur','referentiel').'</th>';
	$s.='<th>'.get_string('course').'</th>';	
	$s.='<th>'.get_string('type_activite','referentiel').'</th>';
	$s.='<th>'.get_string('liste_codes_competence','referentiel').'</th>';
	$s.='<th>'.get_string('referent','referentiel').'</th>';
	$s.='<th>'.get_string('validation','referentiel').'</th>';
	$s.='<th>'.get_string('date_modif_student','referentiel').'</th>';	
	$s.='<th>'.get_string('date_modif','referentiel').'</th>';
	$s.='<th>&nbsp;</th>';	
	$s.='</tr>'."\n";
	return $s;
}

// Affiche une activite et les documents associés
// *****************************************************************
// input @param a $record_a   of activite                          *
// output null                                                     *
// *****************************************************************

function referentiel_print_activite($record_a){
$s="";
	if ($record_a){
		$activite_id=$record_a->id;
		$type_activite = stripslashes($record_a->type_activite);
		$description_activite = stripslashes($record_a->description_activite);
		$competences_activite = $record_a->competences_activite;
		$commentaire_activite = stripslashes($record_a->commentaire_activite);
		$ref_instance = $record_a->ref_instance;
		$ref_referentiel = $record_a->ref_referentiel;
		$ref_course = $record_a->ref_course;
		$userid = $record_a->userid;
		$teacherid = $record_a->teacherid;
		$date_creation = $record_a->date_creation;
		$date_modif_student = $record_a->date_modif_student;
		$date_modif = $record_a->date_modif;
		
        $prioritaire=referentiel_activite_prioritaire($record_a);
		$approved = $record_a->approved;
		$ref_task = $record_a->ref_task;

        $user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);

		// dates
		if ($date_creation!=0){
			$date_creation_info=userdate($date_creation);
		}
		else{
			$date_creation_info='';
		}

		if ($date_modif!=0){
			$date_modif_info=userdate($date_modif);
		}
		else{
			$date_modif_info='';
		}

		if ($date_modif_student==0){
			$date_modif_student=$date_creation;
		}
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}


		$s.='<tr>';
        if (!empty($prioritaire)){
            $s.='<td class="prioritaire">';
        }
        else if (isset($approved) && ($approved)){
			$s.= '<td class="valide">';
		}
		else{
			$s.= '<td class="invalide">';
		}

		$s.= $activite_id;
		$s.='</td><td>';
		$s.=$user_info;
		// MODIF JF 2012/05/06
        $s.= referentiel_liste_groupes_user($ref_course, $userid);
		$s.='</td><td>';
		$s.=$type_activite;
		// Modif JF 06/10/2010
		if ($ref_task){
            // consignes associées à une tâche
            $titre_task=referentiel_get_theme_task($ref_task);
            $info_task=referentiel_get_content_task($ref_task);
            if ($info_task!=''){
                // lien vers la tâche
                $s.='<br />'.referentiel_affiche_overlib_texte($titre_task, $info_task);
            }
            // documents associés à une tâche
            echo referentiel_print_liste_documents_task($ref_task);
        }
/*
		p($type_activite);
		$s.=nl2br($description_activite);
*/

		if (isset($approved) && ($approved)){
			$s.='</td><td class="valide">';
		}
		else{
			$s.='</td><td class="invalide">';
		}
		$s.=referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel);
/*
		$s.=nl2br($commentaire_activite);
*/
		$s.='</td><td>';
		$s.=$teacher_info;
		$s.='</td><td>';
		if (isset($approved) && ($approved)){
			$s.=get_string('approved','referentiel');
		}
		else{
			$s.=get_string('not_approved','referentiel');	
		}
		$s.='</td><td>';
		$s.='<span class="small">'.$date_modif_info.'</span>';
		
/***************************** DOCUMENTS *******************************
		// charger les documents associes à l'activite courante
		if (isset($activite_id) && ($activite_id>0)){
			$ref_activite=$activite_id; // plus pratique
			// AFFICHER LA LISTE DES DOCUMENTS
			$compteur_document=0;
			$records_document = referentiel_get_documents($ref_activite);
	    	if ($records_document){
    			// afficher
				// DEBUG
				// echo "<br/>DEBUG ::<br />\n";
				// print_r($records_document);
				foreach ($records_document as $record_d){
					$compteur_document++;
        			$document_id=$record_d->id;
					$type_document = $record_d->type_document;
					$description_document = $record_d->description_document;
					$url_document = $record_d->url_document;
					$ref_activite = $record_d->ref_activite;
					if (isset($record_d->cible_document) && ($record_d->cible_document==1)){
						$cible_document='_blank'; // fenêtre cible
					}
					else{
						$cible_document='';
					}
					if (isset($record_d->etiquette_document)){
						$etiquette_document=$record_d->etiquette_document; // fenêtre cible
					}
					else{
						$etiquette_document='';
					}

					print_string('document','referentiel');
					p($document_id);
					print_string('type','referentiel');
					p($type_document); 
					print_string('description','referentiel');
					echo (nl2br($description_document)); 
					print_string('url','referentiel'); 
					if (eregi("http",$url_document)){
						echo '<a href="'.$url_document.'" target="_blank">'.$url_document.'</a>';
					}
					else{
						echo $url_document;
					}
				}
			}
		}
******************************************************************************/
		$s.='</td></tr>'."\n";
	}
	return $s;
}




/************* SUPPRIME 
// Affiche les activites de ce referentiel
// ----------------------------------------------------------
function referentiel_liste_toutes_activites($id_referentiel){
	if (isset($id_referentiel) && ($id_referentiel>0)){
		// DEBUG
		// echo "<br/>DEBUG :: $id_referentiel<br />\n";
		
		$records = referentiel_get_activites($id_referentiel);
		if (!$records){
			print_error(get_string('noactivite','referentiel'), "activite.php?d=$id_referentiel&amp;mode=add");
		}
	    else {
    		// afficher
			// DEBUG
			// echo "<br/>DEBUG ::<br />\n";
			// print_r($records);
			foreach ($records as $record){
				referentiel_print_activite($record);
			}
		}
	}
}
***************************/

/**
 * Print Library of functions for activities of module referentiel
 * 
 * @author jfruitet
 * @version $Id: print_lib_activite.php,v 1.0 2008/04/29 00:00:00 jfruitet Exp $
 * @version $Id: print_lib_activite.php,v 2.0 2009/11/30 00:00:00 jfruitet Exp $
 * @package referentiel
 **/


/**************************************************************************
 * takes a list of records, the current referentiel, an optionnal user id *
 * and mode to display                                                    *
 * input @param string  $mode                                             *
 *       @param object $referentiel_instance                              *
 *       @param int $userid_filtre                                        *
 *       @param array of objects $gusers of users get from current group  *
 *       @param string $sql_filtre_where, $sql_filtre_order               *
 * output null                                                            *
 **************************************************************************/
function referentiel_print_liste_activites($mode, $referentiel_instance, $userid_filtre=0, $gusers=NULL, $sql_filtre_where, $sql_filtre_order, $select_acc) {
global $DB;
global $CFG;
global $USER;
static $isadmin=false;
static $istutor=false;
static $isteacher=false;
static $iseditor=false;
static $referentiel_id = NULL;
global $appli;

	// contexte
    $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
    $course = $DB->get_record('course', array('id' => $cm->course));
	if (empty($cm) or empty($course)){
        print_error('REFERENTIEL_ERROR 5 :: print_lib_activite.php :: You cannot call this script in that way');
	}

    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}


	$records = array();
	$referentiel_id = $referentiel_instance->ref_referentiel;
    $roles=referentiel_roles_in_instance($referentiel_instance->id);
    $iseditor=$roles->is_editor;
    $isadmin=$roles->is_admin;
    $isteacher=$roles->is_teacher;
    $istutor=$roles->is_tutor;
    $isstudent=$roles->is_student;


	if (isset($referentiel_id) && ($referentiel_id>0)){
		$referentiel_referentiel=referentiel_get_referentiel_referentiel($referentiel_id);
		if (!$referentiel_referentiel){
			if ($iseditor){
				print_error(get_string('creer_referentiel','referentiel'), "edit.php?d=$referentiel_instance->id&amp;mode=editreferentiel&amp;sesskey=".sesskey());
			}
			else {
				print_error(get_string('creer_referentiel','referentiel'), "../../course/view.php?id=$course->id&amp;sesskey=".sesskey());
			}
		}
	 	// preparer les variables globales pour Overlib
		// referentiel_initialise_data_referentiel($referentiel_referentiel->id);
		// boite pour selectionner les utilisateurs ?
		if ($isteacher || $iseditor || $istutor){
			if (!empty($select_acc)){
			  // eleves accompagnes
        $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
      }
			else{
  			// tous les users possibles (pour la boite de selection)
	   		// Get your userids the normal way
		  	$record_id_users  = referentiel_get_students_course($course->id,0,0);  //seulement les stagiaires
			}
      if ($gusers && $record_id_users){ // liste des utilisateurs du groupe courant
				// echo "<br />DEBUG :: print_lib_activite.php :: 740 :: GUSERS<br />\n";
				// print_object($gusers);
				// echo "<br />\n";
				// exit;
				$record_users  = array_intersect($gusers, array_keys($record_id_users));
				// $record_users  = array_intersect_assoc($record_id_users, array_keys($gusers));
				// echo "<br />DEBUG :: print_lib_activite.php :: 745 :: RECORD_USERS<br />\n";
				// print_r($record_users  );
				// echo "<br />\n";
				// recopier
				$record_id_users=array();
				foreach ($record_users  as $record_id){
                    $a_obj=new stdClass();
                    $a_obj->userid=$record_id;
                    $record_id_users[]=$a_obj;
				}
			}
			// DEBUG
			// echo "<br />DEBUG :: print_lib_activite.php :: 734 :: Record_id_users<br />\n";
			// print_object($record_id_users  );
			// echo "<br />\n";
			// exit;

			echo referentiel_select_users_activite($record_id_users, $userid_filtre, $mode);
		}
		// filtres
		if ((!$isteacher) && (!$iseditor) && (!$istutor)){
			$userid_filtre=$USER->id;
		}

		// recuperer les utilisateurs filtres
		if (!empty($select_acc)){
			  // eleves accompagnes
        $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
    }
		else{
		  // $userid_filtre est l'id de l'utilisateur dont on affiche les activites
		  // si $userid_filtre ==0 on retourne tous les utilisateurs du cours et du groupe
		  $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
		}

    if ($record_id_users && $gusers){ // liste des utilisateurs du groupe courant
			$record_users  = array_intersect($gusers, array_keys($record_id_users));
			// recopier
			$record_id_users=array();
			foreach ($record_users  as $record_id){
                $a_obj=new stdClass();
                $a_obj->userid=$record_id;
                $record_id_users[]=$a_obj;
			}
		}

		if ($record_id_users){
			// Afficher
			if (isset($mode) && ($mode=='listactivityall')){
				echo referentiel_print_entete_activite_complete();
			}
			else if (isset($mode) && ($mode=='listactivitysingle')){
				echo referentiel_print_entete_activite_complete();
			}
			else{
				echo referentiel_print_entete_activite();
			}
		    foreach ($record_id_users  as $record_id) {   // afficher la liste d'users
				// recupere les enregistrements
				// MODIF JF 23/10/2009
				if (isset($userid_filtre) && ($userid_filtre==$USER->id)){
					$actif=true;
					$records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id->userid, $sql_filtre_where, $sql_filtre_order);
				}
				else if (isset($mode) && ($mode=='listactivityall')){
					$actif=false;
					$records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id->userid, $sql_filtre_where, $sql_filtre_order);
				}
				else{
					$actif=false;
					// 	$records=referentiel_get_all_activites_user_course($referentiel_instance->ref_referentiel, $record_id->userid, $course->id);
					$records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id->userid, $sql_filtre_where, $sql_filtre_order);
				}

				if ($records){
					// MODIF JF 2009/11/28
					// Liste des competences declarees
					echo '<td colspan="10" align="center">'.get_string('competences_declarees','referentiel', referentiel_get_user_info($record_id->userid))."\n".referentiel_print_jauge_activite($record_id->userid, $referentiel_id).'</td>'."\n";
				    foreach ($records as $record) {   // afficher l'activite
						// Afficher
						if (isset($mode) && ($mode=='listactivityall')){
							echo referentiel_print_activite_2($record, $context, $actif, $select_acc);
						}
						elseif (isset($mode) && ($mode=='listactivitysingle')){
							echo referentiel_print_activite_2($record, $context, $actif, $select_acc);
						}
						else{
							echo referentiel_print_activite_2($record, $context, $actif, $select_acc);
						}
					}
					// MODIF JF 2009/11/28
					// echo '<td colspan="10" align="center">'.get_string('competences_declarees','referentiel', referentiel_get_user_info($record_id->userid))."\n".referentiel_print_jauge_activite($record_id->userid, $referentiel_id).'</td>'."\n";
				}
				else{
					if (isset($mode) && ($mode=='listactivity')){
						echo '<tr><td class="zero" colspan="10" align="center">'.referentiel_print_aucune_activite_user($record_id->userid).'</td></tr>'."\n";
					}
					else if (isset($mode) && ($mode=='listactivityall')){
						// echo '<tr><td class="zero" colspan="7" align="center">'.referentiel_print_aucune_activite_user($record_id->userid).'</td></tr>'."\n";
					}
				}
    		}
			// Afficher
			//if ($mode!='listactivitysingle'){
				echo referentiel_print_enqueue_activite();
			//}
		}
	}
	//echo '<br /><br />'."\n";
	return true;
}



/**************************************************************************
 * takes a the current referentiel, an user id                            *
 * input                                                                  *
 *       @param object $referentiel_instance                              *
 *       @param int $userid                                               *
 * output true                                                            *
 **************************************************************************/

function referentiel_print_liste_activites_user($referentiel_instance, $userid, $sql_filtre_where='', $sql_filtre_order='') {
global $CFG;
global $DB;
global $USER;
static $referentiel_id = NULL;
global $appli;


	// contexte
    $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
    $course = $DB->get_record("course", array("id" => "$cm->course"));

    if (empty($cm) or empty($course)){
        print_print_error('REFERENTIEL_print_error 5 :: print_lib_activite.php :: You cannot call this script in that way');
	}
	
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);

	$records = array();
	$referentiel_id = $referentiel_instance->ref_referentiel;

    $roles=referentiel_roles_in_instance($referentiel_instance->id);
    $iseditor=$roles->is_editor;
    $isadmin=$roles->is_admin;
    $isteacher=$roles->is_teacher;
    $istutor=$roles->is_tutor;
    $isstudent=$roles->is_student;
        
	if (isset($referentiel_id) && ($referentiel_id>0)){
		$referentiel_referentiel=referentiel_get_referentiel_referentiel($referentiel_id);
		if (!$referentiel_referentiel){
			if ($iseditor){
				print_print_error(get_string('creer_referentiel','referentiel'), "$CFG->wwwroot/mod/referentiel/edit.php?d=$referentiel_instance->id&amp;mode=editreferentiel&amp;sesskey=".sesskey());
			}
			else {
				print_print_error(get_string('creer_referentiel','referentiel'), "$CFG->wwwroot/course/view.php?id=$course->id&amp;sesskey=".sesskey());
			}
		}
	 	// preparer les variables globales pour Overlib
		// referentiel_initialise_data_referentiel($referentiel_referentiel->id);

		if (isset($userid) && ($userid==$USER->id)){ 
			$records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id->userid, $sql_filtre_where, $sql_filtre_order);
		}
		else{
			$records=referentiel_get_all_activites_user_course($referentiel_instance->ref_referentiel, $record_id->userid, $course->id);
		}
		if ($records){
			foreach ($records as $record) {   
				// Afficher 	
				referentiel_print_activite_detail($record);
				referentiel_menu_activite_detail($context, $record->id, $referentiel_instance->id, $record->approved, $select_acc);
			}
		}
		else{
			echo referentiel_print_aucune_activite_user($record_id->userid);
		}
		// Afficher le menu
		// referentiel_menu_activite_detail($context, $record->id, $referentiel_instance->id, $record->approved, $select_acc);
	}
	//echo '<br /><br />'."\n";
	return true;
}


?>