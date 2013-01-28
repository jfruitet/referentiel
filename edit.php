<?php  // $Id: edit.php,v 1.0 2008/04/29/ 00:00:00 jfruitet Exp $
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

/**
* Modification du referentiel
* association a l'instance
*
* @version $Id: edit.php,v 2.0 2009/08/04/ 00:00:00 jfruitet Exp $
* @author Martin Dougiamas, Howard Miller, and many others.
*         {@link http://moodle.org}
* @license http://www.gnu.org/copyleft/gpl.html GNU Public License
* @package referentiel
*/


    require_once('../../config.php');
    require_once('lib.php');
    // require_once('pagelib.php'); // ENTETES
	
	// PAS DE RSS
    // require_once("$CFG->libdir/rsslib.php");

    $id    = optional_param('id', 0, PARAM_INT);    // course module id	
    $d     = optional_param('d', 0, PARAM_INT);    // referentiel instance id
	$pass  = optional_param('pass', 0, PARAM_INT);    // mot de passe ok
    $checkpass = optional_param('checkpass','', PARAM_ALPHA); // mot de passe fourni
	
    $mode = optional_param('mode','all', PARAM_ALPHANUMEXT);
 	$select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement

    // nouveaute Moodle 1.9 et 2
    $url = new moodle_url('/mod/referentiel/edit.php');

	if ($d) {     // referentiel_referentiel_id
        if (! $referentiel = $DB->get_record("referentiel", array("id" => "$d"))) {
            print_error('Referentiel instance is incorrect');
        }

		if (! $course = $DB->get_record("course", array("id" => "$referentiel->course"))) {
	            print_error('Course is misconfigured');
    	}

		if (! $cm = get_coursemodule_from_instance('referentiel', $referentiel->id, $course->id)) {
    	        print_error('Course Module ID is incorrect');
		}
		$url->param('d', $d);
	}
	elseif ($id) {
        if (! $cm = get_coursemodule_from_id('referentiel', $id)) {
        	print_error('Course Module ID was incorrect');
        }
        if (! $course = $DB->get_record("course", array("id" => "$cm->course"))) {
            print_error('Course is misconfigured');
        }
        if (! $referentiel = $DB->get_record("referentiel", array("id" => "$cm->instance"))) {
            print_error('Referentiel instance is incorrect');
        }
        $url->param('id', $id);
    }
	else{
        // print_error('You cannot call this script in that way');
		print_error(get_string('erreurscript','referentiel','Erreur01 : edit.php'));
	}

    if ($mode !== 'all') {
        $url->param('mode', $mode);
    }

	$returnlink="$CFG->wwwroot/course/view.php?id=$course->id";
    require_login($course->id, false, $cm);

    if (!isloggedin() || isguestuser()) {   // nouveaute Moodle 2
        redirect($returnlink);
    }

    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}


    if (!empty($referentiel->id)) {    // So do you have access?
        if (!has_capability('mod/referentiel:writereferentiel', $context)
        or !confirm_sesskey() ) {
            print_error(get_string('noaccess','referentiel'));
        }
    }
	else{
		print_error('Referentiel instance is incorrect');
	}

	// lien vers le referentiel lui-meme
	if (!empty($referentiel->ref_referentiel)){
	    if (! $referentiel_referentiel = $DB->get_record('referentiel_referentiel', array("id" => "$referentiel->ref_referentiel"))) {
    		print_error('Referentiel referentiel id is incorrect '.$referentiel->ref_referentiel);
    	}
    }
	else{
		// rediriger vers la creation du referentiel
		$returnlink_add="$CFG->wwwroot/mod/referentiel/add.php?d=$referentiel->id";
        redirect($returnlink_add);
	}
	

	// RECUPERER LES FORMULAIRES
    if (isset($SESSION->modform)) {   // Variables are stored in the session
        $form = $SESSION->modform;
        unset($SESSION->modform);
    } 
	else {
        $form = (object)$_POST;
    }

    // Traitement des POST
	$msg="";

	if (!empty($course) && !empty($cm) && !empty($referentiel)  && !empty($referentiel_referentiel) && isset($form)) {
	
	// DEBUG
	// echo "<br />DEBUG : edit.php :: Ligne 122<br />\n";
	// print_r($form);

		// add, delete or update form submitted	
		
		// le mot de passe est-il actif ?
		// cette fonction est due au param�trage
		if ((!$pass) && ($checkpass=='checkpass')){
            if (!empty($form->pass_referentiel) && $referentiel_referentiel){
                if (!empty($form->force_pass)){  // forcer la sauvegarde sans verification
                    $pass=referentiel_set_pass($referentiel_referentiel->id, $form->pass_referentiel);
                }
                else{ // tester le mot de passe
                    $pass=referentiel_check_pass($referentiel_referentiel, $form->pass_referentiel);
                }
                if (!$pass){
                    // Abandonner
                    redirect("$CFG->wwwroot/mod/referentiel/view.php?id=$cm->id");
                    exit;
                }
            }
            else{    // mot de passe vide mais c'est un admin qui est connect�
                if (!empty($form->force_pass)){
                    $pass=1; // on passe... le mot de passe !
                }
            }
		}
		
		// variable d'action
		if (!empty($form->cancel)){
			if ($form->cancel == get_string("quit", "referentiel")){
				// Abandonner
    		    redirect("$CFG->wwwroot/mod/referentiel/view.php?id=$cm->id");
       			exit;
			}
		}
		
		// variable d'action
		else if (!empty($form->delete)){
			if ($form->delete == get_string("delete")){
				
				// Suppression
				if (($form->action=="modifierdomaine") || ($form->action=="modifiercompetence") || ($form->action=="modifieritem")){
					if ($form->action=="modifierdomaine"){
						// enregistre les modifications
						if (isset($form->domaine_id) && ($form->domaine_id>0)){
							$return=referentiel_supprime_domaine($form->domaine_id);
							$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("domaine", "referentiel")." ".$form->domaine_id;
						}
					}
					else if ($form->action=="modifiercompetence"){
						// enregistre les modifications
						if (isset($form->competence_id) && ($form->competence_id>0)){
							$return=referentiel_supprime_competence($form->competence_id);
							$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("competence", "referentiel")." ".$form->competence_id;
						}
					}
					else if ($form->action=="modifieritem"){
						// enregistre les modifications
						if (isset($form->item_id) && ($form->item_id>0)){
							$return=referentiel_supprime_item($form->item_id);
							$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("item", "referentiel")." ".$form->item_id;
						}
					}
					
					if (!isset($return) || (!$return)) {
    	    	    	print_error("Could not delete $msg", "view.php?d=$referentiel->id");
        	    	}
		            if (is_string($return)) {
    		        	print_error($return, "$CFG->wwwroot/mod/referentiel/view.php?d=$referentiel->id");
	    		    }
					
					if ($return) {
						// Mise � jour de la liste de competences dans le referentiel
						$liste_codes_competence=referentiel_new_liste_codes_competence($form->referentiel_id);
						// echo "<br />LISTE_CODES_COMPETENCE : $liste_codes_competence\n";
						referentiel_set_liste_codes_competence($form->referentiel_id, $liste_codes_competence);
					}
					
		    		add_to_log($course->id, "referentiel", "delete", "edit.php?d=".$referentiel->id, $msg, $cm->module);
				}
				
	        	if (isset($form->redirect)) {
    	        	$SESSION->returnpage = $form->redirecturl;
        	    } 
				else {
            		$SESSION->returnpage = "$CFG->wwwroot/mod/referentiel/view.php?id=$cm->id";
	            }
			}
		}
		
		// variable d'action Enregistrer
		else if (!empty($form->action)){
			if (isset($form->mode) && ($form->mode=="add")){
				// creer le referentiel
				if ($form->action=="modifierreferentiel"){
					// enregistre les modifications
					$return=referentiel_add_referentiel_domaines($form);
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id;
					$action="update";
				}
			}
			else if (isset($form->mode) && ($form->mode=="update")){
				if ($form->action=="modifierreferentiel"){
					// enregistre les modifications
		// DEBUG
		// echo "<br /> DEBUG :: edit.php :: 226 : MODE : $form->mode ACTION : $form->action<br />\n";
		// echo "<br />FORM<br />\n";		
		// print_r($form);
					// gestion du mot de passe
					
					if (isset($form->suppression_pass_referentiel) && ($form->suppression_pass_referentiel==1)){
						$form->old_pass_referentiel = '';
					}
					else if (isset($form->pass_referentiel) && ($form->pass_referentiel!='') 
						&& 
						(
							isset($form->old_pass_referentiel) && ($form->old_pass_referentiel!='') && ($form->old_pass_referentiel != md5($form->pass_referentiel))
							|| 
							isset($form->old_pass_referentiel) && ($form->old_pass_referentiel=='') 
						)
					){
						$form->old_pass_referentiel = md5($form->pass_referentiel);
					}
					
					$return=referentiel_update_referentiel_referentiel($form);
					
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id;
					$action="update";
					
				}
				else if ($form->action=="modifierdomaine"){
					// enregistre les modifications
					if (isset($form->domaine_id) && ($form->domaine_id>0)){
						$return=referentiel_update_domaine($form);
						$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("domaine", "referentiel")." ".$form->domaine_id;
						$action="update";
						
					}
				}
				else if ($form->action=="modifiercompetence"){
					// enregistre les modifications
					if (isset($form->competence_id) && ($form->competence_id>0)){
						$return=referentiel_update_competence($form);
						$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("competence", "referentiel")." ".$form->competence_id;
						$action="update";
					}
				}
				else if ($form->action=="modifieritem"){
					// enregistre le nouveau domaine
					$return=referentiel_update_item($form);
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("item", "referentiel")." ".$form->item_id;
					$action="update";
				}
				else if ($form->action=="newdomaine"){
					// enregistre le nouveau domaine
					$return=referentiel_add_domaine($form);
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("domaine", "referentiel")." ".$return;
					$action="add";
				}
				else if ($form->action=="newcompetence"){
					// enregistre le nouvel item
					$return=referentiel_add_competence($form);
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("competence", "referentiel")." ".$return;
					$action="add";
				}
				else if ($form->action=="newitem"){
					// enregistre les modifications
					$return=referentiel_add_item($form);
					$msg=get_string("referentiel", "referentiel")." ".$form->referentiel_id." ".get_string("item", "referentiel")." ".$return;
					$action="add";
				}
				
				if (!$return) {
					print_error("Could not update instance $form->referentiel_id of referentiel", "view.php?id=$cm->id");
        		}
	        	if (is_string($return)) {
					print_error($return, "$CFG->wwwroot/mod/referentiel/view.php?id=$cm->id");
	    		}
				
		        if (isset($form->redirect)) {
    		    	$SESSION->returnpage = $form->redirecturl;
        		}
                else {
            		$SESSION->returnpage = "$CFG->wwwroot/mod/referentiel/view.php?id=cm->id";
	        	}
			}
			
			if (isset($action)){
				add_to_log($course->id, "referentiel", $action, "edit.php?d=".$referentiel->id, $msg, $cm->module);
			}
			
			/*
    	    if (!empty($SESSION->returnpage)) {
            	$return = $SESSION->returnpage;
	            unset($SESSION->returnpage);
    	        redirect($return);
        	} 
			else {
	            redirect("$CFG->wwwroot/mod/referentiel/view.php?d=$referentiel->id");
    	    }
        	exit;
			*/
		}
	}
	
	// afficher les formulaires

    // unset($SESSION->modform); // Clear any old ones that may be hanging around.

  $modform = "edit.html";
  // $modform = "edit_global.html";     // A TERMINER
  if (file_exists($modform)) {
        if ($usehtmleditor = can_use_html_editor()) {
            $defaultformat = FORMAT_HTML;
            $editorfields = '';
        } 
	else {
            $defaultformat = FORMAT_MOODLE;
        }
	}
	else {
        notice("ERREUR : No file found at : $modform)", "view.php?id=$course->id&d=$referentiel->id");
  }

    /// Mark as viewed  ??????????? A COMMENTER
    $completion=new completion_info($course);
    $completion->set_module_viewed($cm);

// AFFICHAGE DE LA PAGE Moodle 2
	$strreferentiels = get_string('modulenameplural','referentiel');
	$strreferentiel = get_string('referentiel','referentiel');
	$strmessage = get_string('modifier_referentiel','referentiel');
	$strpagename=get_string('modifier_referentiel','referentiel');
    $strlastmodified = get_string('lastmodified');
    $pagetitle = strip_tags($course->shortname.': '.$strreferentiel.': '.format_string($referentiel->name,true));
    $icon = $OUTPUT->pix_url('icon','referentiel');


	// initialisation parametres affichage
	if (!isset($mode)){
		$mode='editreferentiel'; // un seul mode possible
	}
	$currenttab = 'editreferentiel';
    if ($referentiel->id) {
       	$editentry = true;  //used in tabs
    }


    // affichage de la page
    $PAGE->set_url($url);
    $PAGE->requires->css('/mod/referentiel/referentiel.css');
    //if ($CFG->version < 2011120100) $PAGE->requires->js('/lib/overlib/overlib.js');  else
    $PAGE->requires->js($OverlibJs);
    $PAGE->requires->js('/mod/referentiel/functions.js');

    $PAGE->set_title($pagetitle);
    $PAGE->set_heading($course->fullname);


    echo $OUTPUT->header();

    if (!empty($referentiel->name)){
        echo '<div align="center"><h1>'.$referentiel->name.'</h1></div>'."\n";
    }

    // ONGLETS
    include('tabs.php');

    echo '<div align="center"><h2><img src="'.$icon.'" border="0" title="" alt="" /> '.$strmessage.' '.$OUTPUT->help_icon('modifreferentielh','referentiel').'</h2></div>'."\n";

	if ($mode=='listreferentiel'){
		referentiel_affiche_referentiel($referentiel->id); 
	}
	else {
        echo $OUTPUT->box_start('generalbox  boxaligncenter');
        // formulaires
		
        // verifer si le mot de passe est fourni
        if (!$pass  // si cest un admin il outrepasse car pass==1
		&&
		(
            (
                isset($referentiel_referentiel->pass_referentiel)
		        &&
		        ($referentiel_referentiel->pass_referentiel!='')
            )
            || $isreferentielauteur
            || $isadmin
        )
        ){
		// demander le mot de passe
            $appli_appelante="edit.php";
			include_once("pass.html");
		}
		else{
			include_once($modform);
	    }
        echo $OUTPUT->box_end();
	}
	
    echo $OUTPUT->footer();
    die();
	
?>
