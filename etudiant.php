<?php  // $Id: scolarite.php,v 1.0 2008/05/03 00:00:00 jfruitet Exp $
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

    require_once("../../config.php");
    require_once('lib.php');
    require_once('lib_etab.php');
    require_once('print_lib_etudiant.php');	// AFFICHAGES 

	// PAS DE RSS
    // require_once("$CFG->libdir/rsslib.php");

    $id    = optional_param('id', 0, PARAM_INT);    // course module id    
	$d     = optional_param('d', 0, PARAM_INT);    // referentielbase id
	
    $userid   = optional_param('userid', 0, PARAM_INT);    //record etudiant id
	$etudiant_id   = optional_param('etudiant_id', 0, PARAM_INT);    //record etudiant id
	$etablissement_id   = optional_param('etablissement_id', 0, PARAM_INT);    //record etablissement id	

    $initiale     = optional_param('initiale','', PARAM_ALPHA); // selection par les initiales du nom
    $userids      = optional_param('userids','', PARAM_TEXT); // id user selectionnes par les initiales du nom

    // $import   = optional_param('import', 0, PARAM_INT);    // show import form

    $action  	= optional_param('action','', PARAM_ALPHANUMEXT); // pour distinguer differentes formes de traitements
    $mode       = optional_param('mode','', PARAM_ALPHANUMEXT);
    $add        = optional_param('add','', PARAM_ALPHA);
    $update     = optional_param('update', 0, PARAM_INT);
    $delete     = optional_param('delete', 0, PARAM_INT);
    $approve    = optional_param('approve', 0, PARAM_INT);	
    $comment    = optional_param('comment', 0, PARAM_INT);		
    $course     = optional_param('course', 0, PARAM_INT);
    $groupmode  = optional_param('groupmode', -1, PARAM_INT);
    $cancel     = optional_param('cancel', 0, PARAM_BOOL);
	$select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement

    $filtre_validation = optional_param('filtre_validation', 0, PARAM_INT);
    $filtre_referent = optional_param('filtre_referent', 0, PARAM_INT);
    $filtre_date_modif = optional_param('filtre_date_modif', 0, PARAM_INT);
    $filtre_date_modif_student = optional_param('filtre_date_modif_student', 0, PARAM_INT);
    $filtre_auteur = optional_param('filtre_auteur', 0, PARAM_INT);

    // MODIF JF 2012/09/20
	$data_filtre= new Object(); // paramettres de filtrage
	if (isset($filtre_validation)){
			$data_filtre->filtre_validation=$filtre_validation;
	}
	else {
		$data_filtre->filtre_validation=0;
	}
	if (isset($filtre_referent)){
		$data_filtre->filtre_referent=$filtre_referent;
	}
	else{
		$data_filtre->filtre_referent=0;
	}
	if (isset($filtre_date_modif_student)){
		$data_filtre->filtre_date_modif_student=$filtre_date_modif_student;
	}
	else{
		$data_filtre->filtre_date_modif_student=0;
	}
	if (isset($filtre_date_modif)){
		$data_filtre->filtre_date_modif=$filtre_date_modif;
	}
	else{
		$data_filtre->filtre_date_modif=0;
	}
	if (isset($filtre_auteur)){
		$data_filtre->filtre_auteur=$filtre_auteur;
	}
	else{
		$data_filtre->filtre_auteur=0;
	}

    // nouveaute Moodle 1.9 et 2
    $url = new moodle_url('/mod/referentiel/etudiant.php');

	if ($d) {     // referentiel_referentiel_id
        if (! $referentiel = $DB->get_record("referentiel", array("id" => "$d"))) {
            print_error('Referentiel instance is incorrect');
        }
        if (! $referentiel_referentiel = $DB->get_record("referentiel_referentiel", array("id" => "$referentiel->ref_referentiel"))) {
            print_error('R�ferentiel id is incorrect');
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
        if (! $referentiel_referentiel = $DB->get_record("referentiel_referentiel", array("id" => "$referentiel->ref_referentiel"))) {
            print_error('Referentiel is incorrect');
        }
        $url->param('id', $id);
    }
	else{
    // print_error('You cannot call this script in that way');
		print_error(get_string('erreurscript','referentiel','Erreur01 : etudiant.php'), 'referentiel');
	}

	// Moodle 2

    $url->param('mode', $mode);


    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}


	if (isset($userid) && ($userid>0)) { 
		// id etudiant
		$record = $DB->get_record("referentiel_etudiant", array("userid" => "$userid"));
		
		if (!$record) {
            $record=referentiel_add_etudiant_user($userid);
        }
	}
	if (isset($userid) && ($userid>0)) { 
		// id etudiant
        if (! $record){
            if (! $record=$DB->get_record("referentiel_etudiant", array("userid" => "$userid"))){
                print_error('Etudiant userid is incorrect');
            }
        }
	}
	
	if (isset($etudiant_id) && ($etudiant_id>0)) { 
		// id etudiant

        if (! $record){
            if (!$record = $DB->get_record("referentiel_etudiant", array("userid" => "$userid"))) {
                print_error('Etudiant id is incorrect');
            }
        }
	}

	if (isset($etablissement_id) && ($etablissement_id>0)) { 
		// id etablissement
        if (! $record_etab = $DB->get_record("referentiel_etablissement", array("id" => "$etablissement_id"))) {
            print_error('etablissement id is incorrect');
        }
	}

    require_login($course->id, false, $cm);   // pas d'autologin guest

    if (!isloggedin() or isguestuser()) {
        redirect($CFG->wwwroot.'/mod/referentiel/view.php?id='.$cm->id.'&amp;non_redirection=1');
    }

    
	/// If it's hidden then it's don't show anything.  :)
	/// Some capability checks.
    if (empty($cm->visible)
    && (
        !has_capability('moodle/course:viewhiddenactivities', $context)
            &&
        !has_capability('mod/referentiel:managecomments', $context)
        )

    ) {
        print_error(get_string("activityiscurrentlyhidden"),'error',"$CFG->wwwroot/course/view.php?id=$course->id");
    }

    if ($userid) {    // So do you have access?
        if (!(has_capability('mod/referentiel:write', $context) 
			or ($USER->id==$userid)) or !confirm_sesskey() ) {
            print_error(get_string('noaccess','referentiel'));
        }
    }
	
	// selecteur
	$userid_filtre=0;
	
	// RECUPERER LES FORMULAIRES
    if (isset($SESSION->modform)) {   // Variables are stored in the session
        $form = $SESSION->modform;
        unset($SESSION->modform);
    }
    else {
        $form = (object)$_POST;
    }

	/// selection etablissement
   	if (isset($mode) && ($mode=='selectetab')
		&& isset($userid) && ($userid>0)
		&& isset($etablissement_id) && ($etablissement_id>0)
		&& confirm_sesskey() ){
		referentiel_etudiant_set_etablissement($userid, $etablissement_id);
		$mode='listetudiant';
    }

	if ($cancel) {
        if (!empty($SESSION->returnpage)) {
            $return = $SESSION->returnpage;
            unset($SESSION->returnpage);
            redirect($return);
        }
        else {
            redirect('etudiant.php?d='.$referentiel->id);
        }
    }

	/// selection d'utilisateurs
    if (isset($action) && ($action=='selectuser')
		&& isset($form->userid) && ($form->userid>0)
		&& confirm_sesskey() ){
		$userid_filtre=$form->userid;
		// DEBUG
		// echo "<br />DEBUG :: etudiant.php :: Ligen 172 :: ACTION : $action  User: $userid_filtre\n";
		unset($form);
		unset($action);
		// exit;
    }

 	
	/// Delete any requested records
    if (isset($delete) && ($delete>0 )
			&& confirm_sesskey() 
			&& (has_capability('mod/referentiel:managecertif', $context) or referentiel_etudiant_isowner($delete))) {
        if ($confirm = optional_param('confirm',0,PARAM_INT)) {
            if (referentiel_delete_etudiant_user($delete)){
				// DEBUG
				// echo "<br /> DEBUG :: 212 ::  etudiant $delete REMIS A ZERO\n";
				// exit;
				add_to_log($course->id, 'referentiel', 'record delete', "etudiant.php?d=$referentiel->id", $delete, $cm->id);
                // notify(get_string('recorddeleted','referentiel'), 'notifysuccess');
            }
		} 
    }
	
	if (!empty($referentiel) && !empty($course) 
		&& isset($form) && isset($form->mode)
		)
	{
		// add, delete or update form submitted	
		$addfunction    = "referentiel_add_etudiant";
        $updatefunction = "referentiel_update_etudiant";
        $deletefunction = "referentiel_delete_etudiant";
		
		switch ($form->mode) {
    		case "updateetudiant":
			
				// DEBUG
				// echo "<br /> $form->mode\n";
				
				if (isset($form->name)) {
   		        	if (trim($form->name) == '') {
       		        	unset($form->name);
           		    }
               	}
				
				if (isset($form->delete) && ($form->delete==get_string('delete'))){
					// suppression 	
					// echo "<br />SUPPRESSION\n";
	    	        $return = $deletefunction($form->userid);
    	    	    if (!$return) {
							/*
            	        	if (file_exists($moderr)) {
                	        	$form = $form;
	                   		    include_once($moderr);
    	                   		die;
	    	               	}
							*/
    	         	      	print_error("Could not update etudiant $form->userid of the referentiel", "etudiant.php?d=$referentiel->id");
        	    	}
	                if (is_string($return)) {
    	           	    print_error($return, "etudiant.php?d=$referentiel->id");
	    	        }
	        	    if (isset($form->redirect)) {
    	                $SESSION->returnpage = $form->redirecturl;
        	       	}
                       else {
            	       	$SESSION->returnpage = "$CFG->wwwroot/mod/referentiel/etudiant.php?d=$referentiel->id";
	               	}
					
	    	        add_to_log($course->id, "referentiel", "delete",
            	          "mise a jour etudiant $form->userid",
                          "$form->etudiant_id", "");
					
				}
				else {
				// DEBUG
				// echo "<br /> UPDATE\n";
				
	    	    	$return = $updatefunction($form);
    	    	    if (!$return) {
					/*
            		    if (file_exists($moderr)) {
                			$form = $form;
                    		include_once($moderr);
                        	die;
	                    }
					*/
    	            	print_error("Could not update etudiant $form->userid of the referentiel", "etudiant.php?d=$referentiel->id");
					}
		            if (is_string($return)) {
    		        	print_error($return, "etudiant.php?d=$referentiel->id");
	    		    }
	        		if (isset($form->redirect)) {
    	        		$SESSION->returnpage = $form->redirecturl;
					} 
					else {
        	    		$SESSION->returnpage = "$CFG->wwwroot/mod/referentiel/etudiant.php?d=$referentiel->id";
	        	    }
					add_to_log($course->id, "referentiel", "update",
            	           "mise a jour etudiant $form->userid",
                           "$form->etudiant_id", "");
    	    	}

			break;
			
			case "addetudiant":
				if (!isset($form->name) || trim($form->name) == '') {
        			$form->name = get_string("modulename", "referentiel");
        		}
				$return = $addfunction($form);
				if (!$return) {
    	        	/*
					if (file_exists($moderr)) {
    	    	    	$form = $form;
        	    	    include_once($moderr);
            	    	die;
					}
	            	*/
					print_error("Could not add a new etudiant to the referentiel", "etudiant.php?d=$referentiel->id");
				}
	        	if (is_string($return)) {
    	        	print_error($return, "etudiant.php?d=$referentiel->id");
				}
				if (isset($form->redirect)) {
    	    		$SESSION->returnpage = $form->redirecturl;
				} 
				else {
					$SESSION->returnpage = "$CFG->wwwroot/mod/referentiel/etudiant.php?d=$referentiel->id";
				}
				add_to_log($course->id, referentiel, "add",
                           "creation etudiant $form->etudiant_id ",
                           "$form->instance", "");
            break;
			
	        case "deleteetudiant":
				if (! $deletefunction($form->userid)) {
	            	print_error("Could not delete etudiant of  the referentiel");
                }
	            unset($SESSION->returnpage);
	            add_to_log($course->id, referentiel, "add",
                           "suppression etudiant $form->userid ",
                           "$form->etudiant_id", "");
            break;
            
			default:
            	// print_error("No mode defined");
        }
       	
    	if (!empty($SESSION->returnpage)) {
            $return = $SESSION->returnpage;
	        unset($SESSION->returnpage);
    	    redirect($return);
        } 
		else {
	    	redirect("etudiant.php?d=$referentiel->id");
    	}
		
        exit;
	}

	// afficher les formulaires

    unset($SESSION->modform); // Clear any old ones that may be hanging around.

    $modform = "etudiant.html";

    /// Check to see if groups are being used here
	/// find out current groups mode
	$groupmode = groups_get_activity_groupmode($cm);
    $currentgroup = groups_get_activity_group($cm, true);

   	/// Get all users that are allowed to submit activite
	$gusers=NULL;
    if ($gusers = get_users_by_capability($context, 'mod/referentiel:write', 'u.id', 'u.lastname', '', '', $currentgroup, '', false)) {
    	$gusers = array_keys($gusers);
    }
	// if groupmembersonly used, remove users who are not in any group
    if ($gusers and !empty($CFG->enablegroupings) and $cm->groupmembersonly) {
    	if ($groupingusers = groups_get_grouping_members($cm->groupingid, 'u.id', 'u.id')) {
       		$gusers = array_intersect($gusers, array_keys($groupingusers));
       	}
    }

	/// Params for the tabs
	if (!isset($mode) || ($mode=="")){
		$mode='listetudiant';
	}
	if (isset($mode) && ($mode=="etudiant")){
		$mode='listetudiant';
	}

	// DEBUG
	// echo "<br /> MODE : $mode\n";

	if (isset($mode) && (($mode=="deleteetudiant") || ($mode=="updateetudiant"))){
		$currenttab ='editetudiant';
	}
	else{
		$currenttab = $mode;
	}

    if ($userid) {
       	$editentry = true;  //used in tabs
    }


    /// Mark as viewed  ??????????? A COMMENTER
    $completion=new completion_info($course);
    $completion->set_module_viewed($cm);

// AFFICHAGE DE LA PAGE Moodle 2
	/// Print the page header
	$stretudiant = get_string('etudiant','referentiel');
	$strpagename=get_string('etudiants','referentiel');

    $strreferentiel = get_string('modulename', 'referentiel');
    $strreferentiels = get_string('modulenameplural', 'referentiel');

    $strlastmodified = get_string('lastmodified');
    $pagetitle = strip_tags($course->shortname.': '.$strreferentiel.': '.format_string($referentiel->name,true));
    $icon = $OUTPUT->pix_url('icon','referentiel');

    $PAGE->set_url($url);
    $PAGE->requires->css('/mod/referentiel/activite.css');
    $PAGE->requires->css('/mod/referentiel/certificat.css');
    $PAGE->navbar->add($strpagename);
    $PAGE->set_title($pagetitle);
    $PAGE->set_heading($course->fullname);

    echo $OUTPUT->header();

	groups_print_activity_menu($cm,  $CFG->wwwroot . '/mod/referentiel/etudiant.php?d='.$referentiel->id.'&amp;mode='.$mode.'&amp;select_acc='.$select_acc);

    if (!empty($referentiel->name)){
        echo '<div align="center"><h1>'.$referentiel->name.'</h1></div>'."\n";
    }


    // ONGLETS
    include('tabs.php');
    //  Moodle 1.9
    //  print_heading_with_help($stretudiant, 'etudiant', 'referentiel', $icon);
    echo '<div align="center"><h2><img src="'.$icon.'" border="0" title="" alt="" /> '.$strpagename.' '.$OUTPUT->help_icon('etudianth','referentiel').'</h2></div>'."\n";

	// DEBUG
	// echo "<br /> MODE : $mode  ; CURRENTTABLE : $currenttab \n";
	// exit;

	
	if (($mode=='scolarite') || ($mode=='listetudiant')){
		referentiel_print_liste_etudiants($initiale, $userids, $mode, $referentiel, $userid_filtre, $gusers, $select_acc);
	}
	else {
        //print_simple_box_start('center', '', '', 5, 'generalbox', $referentiel->name);
   	    echo $OUTPUT->box_start('generalbox  boxaligncenter');
		// formulaires
		if ($mode=='updateetudiant'){
			// recuperer l'id du etudiant apr�s l'avoir genere automatiquement et mettre en place les competences
			
			if (!empty($userid)){
                if (!$record) { // id etudiant
                    if (!$record = $DB->get_record("referentiel_etudiant", array("userid" => "$userid"))){
                        print_error('etudiant ID is incorrect');
                    }
                }
            }
			else{
				print_error('etudiant ID is incorrect');
			}
			$modform = "etudiant_edit.html";
		}
		else if ($mode=='deleteetudiant'){
			// recuperer l'id du etudiant apr�s l'avoir genere automatiquement et mettre en place les competences
			
			if (!empty($userid)){ // id etudiant
                if (!$record){
                    if (!$record = $DB->get_record("referentiel_etudiant", array("userid" => "$userid"))){
	       				print_error('etudiant ID is incorrect');
    	        	}
			     }
            }
			else{
				print_error('etudiant ID is incorrect');
			}
			$modform = "etudiant_edit.html";
		}
		
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
    	    notice("ERREUR : No file found at : $modform)", "etudiant.php?d=$referentiel->id");
    	}
		include_once($modform);
        echo $OUTPUT->box_end();
    } 
    echo $OUTPUT->footer();
    die();

?>
