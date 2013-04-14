<?php  // $Id: delete.php,v 1.0 2009/08/01/ 00:00:00 jfruitet Exp $
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
* @version $Id: delete.php,v 1.0 2009/08/01/ 00:00:00 jfruitet Exp $
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
	$action = optional_param('action','', PARAM_ALPHA);
    $mode = optional_param('mode','all', PARAM_ALPHANUMEXT);

	$select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement

    // nouveaute Moodle 1.9 et 2
    $url = new moodle_url('/mod/referentiel/delete.php');

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
		print_error(get_string('erreurscript','referentiel','Erreur01 : delete.php'));
	}

    if ($mode !== 'all') {
        $url->param('mode', $mode);
    }
    $returnlink_ref = new moodle_url('/mod/referentiel/view.php', array('id'=>$cm->id, 'non_redirection'=>'1'));
    $returnlink_course = new moodle_url('/course/view.php', array('id'=>$course->id));
    $returnlink_add = new moodle_url('/mod/referentiel/add.php', array('d'=>$referentiel->id, 'sesskey'=>sesskey()));

    require_login($course->id, false, $cm);
    if (!isloggedin() || isguestuser()) {
        redirect($returnlink_course);
    }

    $context = get_context_instance(CONTEXT_COURSE, $course->id);

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
                    redirect($returnlink_ref);
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
    	    	if (isset($SESSION->returnpage) && !empty($SESSION->returnpage)) {
	            	$return = $SESSION->returnpage;
    		        unset($SESSION->returnpage);
   	        		redirect($return);
       			} 
				else {
                    redirect($returnlink_ref);
   	    		}
       			exit;
			}
		}
		
		// variable d'action
		else if (!empty($form->delete)){
			if ($form->delete == get_string("delete")){
				// Suppression instances
				if ($form->action=="supprimerinstances"){
					// enregistre les modifications
					if (isset($form->t_ref_instance) && ($form->t_ref_instance) && is_array($form->t_ref_instance)){
						while (list($key, $val)=each($form->t_ref_instance)){
							if ($val){
								// suppression sans confirmation 
								/*
								// REPRIS DE course/mod.php
								*/
								// DEBUG
								// echo '<br />'. $key.' : '.$val."\n";
								$sql = "SELECT * FROM {course_modules} WHERE module = :module AND instance=:refid ";
								$courses_modules = $DB->get_records_sql($sql, array("module" => "$cm->module", "refid" => "$val"));
								if ($courses_modules){
  									foreach($courses_modules as $course_module){
										if (!empty($course_module)) {
            								if ($course_record = $DB->get_record("course", array("id" => "$course_module->course"))) {
    											require_login($course_module->course); // needed to setup proper $COURSE
	       		        						$context_course = get_context_instance(CONTEXT_COURSE, $course_module->course);
               									require_capability('moodle/course:manageactivities', $context_course);
											
			     								$that_instance = $DB->get_record("referentiel", array("id" => "$course_module->instance"));
				    							// echo '<br />INSTANCE :<br />'."\n";
					       						// print_r($that_instance );
						      					// exit;
											
							     				if 	($that_instance){
                                                    if (referentiel_delete_instance($that_instance->id)) {
                                                        if (delete_course_module($course_module->id)) {
                                                            if (delete_mod_from_section($course_module->id, "$course_module->section")) {
                                                                rebuild_course_cache($course_record->id);
		          				      						    $msg=get_string('instance_deleted', 'referentiel').' '.$that_instance->name;
				              		      					    add_to_log($course->id, "referentiel", "delete", "delete.php?d=".$referentiel->id, $msg, $cm->module);
										                    }
									                    }
								                    }
                                                }
							                }
						                }
					                }
                                }
                                else{ // cette 'instance' n'existe dans aucun module, c'est juste un fant�me, on peut la d�truire
								    if (!referentiel_delete_instance($instanceid)) {
                                        ;//print_error("Could not delete that referentiel instance", "$CFG->wwwroot/course/view.php?id=$course->id");
            			    	    }
                                }
                            }
                        }
                    }
					
					if (isset($form->referentiel_id) && ($form->referentiel_id>0)){
						$records_instance=referentiel_referentiel_list_of_instance($form->referentiel_id);
						if ($records_instance){
                            $msg='';
                            foreach($records_instance as $r_instance){
                                $record_instance = referentiel_get_referentiel($r_instance->id);
                                $record_course = $DB->get_record('course', array('id'=> $record_instance->course));
                                $msg.= "<br />".get_string('instance','referentiel')." $record_instance->name (#$record_instance->id) ".get_string('course')." $record_course->fullname ($record_course->shortname) ".get_string('not_deleted', 'referentiel')."\n";
                            }
							$msg.='<br />'.get_string("suppression_referentiel_impossible", "referentiel", $form->referentiel_id);
							redirect($returnlink_course, $msg);
						}
						else{
							// suppression du referentiel
							$return=referentiel_delete_referentiel_domaines($form->referentiel_id);
							if (isset($return) && !empty($return) && !is_string($return)){
                                // suppression des certificats
                                referentiel_delete_referentiel_certificats($form->referentiel_id);
                                $msg=get_string('deletereferentiel', 'referentiel').' '.$form->referentiel_id;
		    				    add_to_log($course->id, "referentiel", "delete", "delete.php?d=".$referentiel->id, $msg, $cm->module);
                                redirect($returnlink_course);
                            }
                            else{
   			                  	redirect($returnlink_course,"Could not delete #$form->referentiel_id occurrence...");
                            }
							exit;
						}
					}
				}
				// Suppression occurrence
				elseif ($form->action=="modifierreferentiel"){
					// enregistre les modifications
					if (isset($form->referentiel_id) && ($form->referentiel_id>0)){
						$records_instance=referentiel_referentiel_list_of_instance($form->referentiel_id);
						if ($records_instance){
                            $msg='';
                            foreach($records_instance as $r_instance){
                                $record_instance = referentiel_get_referentiel($r_instance->id);
                                $record_course = $DB->get_record('course', array('id'=> $record_instance->course));
                                $msg.= "<br />".get_string('instance','referentiel')." $record_instance->name (#$record_instance->id) ".get_string('course')." $record_course->fullname ($record_course->shortname) ".get_string('not_deleted', 'referentiel')."\n";
                            }
							$msg.=get_string("suppression_referentiel_impossible", "referentiel")." ".$form->referentiel_id;
							redirect($returnlink_course,$msg);
						}
						else{
							// suppression du referentiel_referentiel
							$return=referentiel_delete_referentiel_domaines($form->referentiel_id);
							if (isset($return) && !empty($return) && !is_string($return)){
                                referentiel_delete_referentiel_certificats($form->referentiel_id);
								// Mise a jour de la reference du referentiel dans l'instance de certification
								referentiel_de_associe_referentiel_instance($form->instance);
							}
							
							$msg=get_string('deletereferentiel', 'referentiel').' '.$form->referentiel_id;
		    				add_to_log($course->id, "referentiel", "delete", "delete.php?d=".$referentiel->id, $msg, $cm->module);
                            redirect($returnlink_course,$msg);
							exit;	
						}
					}
				}
			}
		}
	}
	
	// afficher les formulaires

    // unset($SESSION->modform); // Clear any old ones that may be hanging around.

    $modform = "delete.html";

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
        notice("ERREUR : No file found at : $modform)", "view.php?id=$course->id&d=$referentiel->id&amp;non_redirection=1");
    }
	
    /// Mark as viewed  ??????????? A COMMENTER
    $completion=new completion_info($course);
    $completion->set_module_viewed($cm);

// AFFICHAGE DE LA PAGE Moodle 2
	$strreferentiels = get_string('modulenameplural','referentiel');
	$strreferentiel = get_string('referentiel','referentiel');
    $strmessage = get_string('supprimer_referentiel','referentiel');
    $strpagename=get_string('supprimer_referentiel','referentiel');
    $strlastmodified = get_string('lastmodified');
    $pagetitle = strip_tags($course->shortname.': '.$strreferentiel.': '.format_string($referentiel->name,true));
    $icon = $OUTPUT->pix_url('icon','referentiel');


	/// Parametre des onglets
	if (!isset($mode)){
		$mode='deletereferentiel'; // un seul mode possible
	}
	$currenttab = 'deletereferentiel';
    if ($referentiel->id) {
       	$editentry = true;  //used in tabs
    }
    
    // affichage de la page
    $PAGE->set_url($url);
    $PAGE->requires->css('/mod/referentiel/referentiel.css');
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

    echo '<div align="center"><h2><img src="'.$icon.'" border="0" title="" alt="" /> '.$strmessage.' '.$OUTPUT->help_icon('suppreferentielh','referentiel').'</h2></div>'."\n";

	if ($mode=='listreferentiel'){
		referentiel_affiche_referentiel($referentiel->id); 
	}
	else {
        echo $OUTPUT->box_start('generalbox  boxaligncenter');

		// verifer si le mot de passe est fourni
		if (!$pass 
			&& 
			$referentiel
			&& 
			$referentiel_referentiel
			&& 
			isset($referentiel_referentiel->pass_referentiel)
			&&
			($referentiel_referentiel->pass_referentiel!='') 
			&& 
			isset($referentiel_referentiel->mail_auteur_referentiel)
			&&
			(referentiel_get_user_mail($USER->id)!=$referentiel_referentiel->mail_auteur_referentiel)){
			// demander le mot de passe
			$appli_appelante="delete.php";
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
