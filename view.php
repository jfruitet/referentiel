<?php  // $Id: view.php,v 1.0 2008/02/28 00:00:00 mark-nielsen jfruitet Exp $
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
 * This page prints a  referentiel
 * 
 * @author JF
 * @version $Id: view.php,v 1.0 2008/02/28 00:00:00 jfruitet Exp $
 * @package referentiel
 **/

/// (Replace newmodule with the name of your module reference)
/// inspire du module data

  require_once("../../config.php");
  require_once("lib.php");
  require_once("print_lib_referentiel.php");	// AFFICHAGES 
  require_once("lib_accompagnement.php");

  $id = optional_param('id', 0, PARAM_INT);    // course module id
  $d  = optional_param('d', 0, PARAM_INT); // Referentiel ID

  // nouveaute Moodle 1.9 et 2
  $url = new moodle_url('/mod/referentiel/view.php');
  
  $mode  = optional_param('mode', 'all', PARAM_ALPHANUMEXT);    // Force the browse mode  ('single')
	
	// editer rubrique
  $edit = optional_param('edit', -1, PARAM_BOOL);

	/// These can be added to perform an action on a record actuivite
  $approve = optional_param('approve', 0, PARAM_INT);    //approval recordid
  $delete = optional_param('delete', 0, PARAM_INT);    //delete recordid
  
  // MODIF JF 22/01/2010
  $non_redirection = optional_param('non_redirection', 0, PARAM_INT);    // par defaut on redirige vers activite
	
  $select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement
  $filtre_validation = optional_param('filtre_validation', 0, PARAM_INT);
  $filtre_referent = optional_param('filtre_referent', 0, PARAM_INT);
  $filtre_date_modif = optional_param('filtre_date_modif', 0, PARAM_INT);
  $filtre_date_modif_student = optional_param('filtre_date_modif_student', 0, PARAM_INT);
  $filtre_auteur = optional_param('filtre_auteur', 0, PARAM_INT);
  $sql_filtre_where=optional_param('sql_filtre_where','', PARAM_ALPHA);
  $sql_filtre_order=optional_param('sql_filtre_order','', PARAM_ALPHA);

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
		print_error(get_string('erreurscript','referentiel','Erreur01 : view.php'));
    }
    $contextcourse = get_context_instance(CONTEXT_COURSE, $course->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    
    if ($mode !== 'all') {
        $url->param('mode', $mode);
    }
    $url->param('non_redirection', $non_redirection);
    
    require_login($course->id, true, $cm);


    // REDIRECTION vers activite
    if (!isset($non_redirection) || ($non_redirection==0)){
        // redirection vers les activites sans message "continuer"
        // Verifier qu'une occurrence de referentiel existe
        if ($referentiel->ref_referentiel){
            if ($referentiel_referentiel = $DB->get_record("referentiel_referentiel", array("id" => "$referentiel->ref_referentiel"))) {
                if (has_capability('mod/referentiel:managecertif', $context)) { // teacher
                    $firstIdGroup=-1;  // pas de groupe avec ce numéro
                    // chercher les groupes de l'utilisateur
    				$usergroups = groups_get_all_groups($course->id, $USER->id);  // groupes de l'utilisateur
	       			if (empty($usergroups)){
                        $usergroups = groups_get_all_groups($course->id, 0);  // groupes du cours
		      		}
                    if (count($usergroups)>0){
    		          	$cleuser=array_keys($usergroups);
        	       		$firstIdGroup=$usergroups[$cleuser[0]]->id;
		      		}
                    if (referentiel_has_pupils($referentiel->id, $course->id, $USER->id)>0){
                        redirect($CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;select_acc=1&amp;mode=listactivity&amp;group='.$firstIdGroup.'&amp;sesskey='.sesskey(),  '', 0);
                    }
                    else{
                        redirect($CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;select_acc=0&amp;mode=listactivity&amp;group='.$firstIdGroup.'&amp;sesskey='.sesskey(),  '', 0);
                    }
                }
                else{
                    redirect($CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;select_acc=1&amp;mode=listactivityall&amp;sesskey='.sesskey(),  '', 0);
                }
                exit;
            }
        }
    }

	/// RSS and CSS and JS meta
    $meta = '';

	/// Print the page header
/*
	$strreferentiels = get_string('modulenameplural','referentiel');
	$strreferentiel = get_string('referentiel','referentiel');
	$strmessage = get_string('referentiel','referentiel');
	$icon = '<img class="icon" src="'.$CFG->wwwroot.'/mod/referentiel/icon.gif" alt="'.get_string('modulename','referentiel').'"/>';
	$strpagename=get_string('modifier_referentiel','referentiel');
*/
    if (isset($mode)){
		if ($mode=='editreferentiel') {
			$currenttab = 'editreferentiel';
    	}
		else {
			$currenttab = 'listreferentiel';
    	}
	}

    $PAGE->set_url($url);
    $PAGE->requires->css('/mod/referentiel/referentiel.css');
    $PAGE->requires->js('/mod/referentiel/functions.js');
    $PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename-intance', 'referentiel')));

    $referentielinstance = new referentiel($cm->id, $referentiel, $cm, $course);

    /// Mark as viewed
    $completion=new completion_info($course);
    $completion->set_module_viewed($cm);

    $referentielinstance->view($mode, $currenttab, $select_acc, $data_filtre);   // Actually display the referentiel!

?>