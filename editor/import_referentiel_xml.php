<?php  // $Id: import_instance.php,v 1.0 2008/04/29/ 00:00:00 jfruitet Exp $
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
* Importation d'un referentiel fabrique avec l'editeur
* D'apres competency/import.php
*
* @package referentiel
*
* Moodle 1.9
*/

    require_once('../../../config.php');
    require_once('../lib.php');
    require_once('../import_export_lib.php');	// IMPORT / EXPORT
    require_once('../class/import_form.php'); // formulaires de choix de fichiers
    require_once($CFG->libdir . '/uploadlib.php');
    require_once($CFG->dirroot.'/repository/lib.php');

    $id    = optional_param('id', 0, PARAM_INT);    // course module id
    $d     = optional_param('d', 0, PARAM_INT);    // referentiel instance id

    $action  			= optional_param('action','', PARAM_ALPHA); // pour distinguer differentes formes de vcreatin de referentiel
    $mode  				= optional_param('mode','', PARAM_ALPHANUMEXT);
    // $format 			= optional_param('format','', PARAM_FILE );
    // un seul format possible
    $format='sxml';
    
	$name_instance		= optional_param('name_instance','', PARAM_ALPHANUM);
	$description_instance= optional_param('description_instance','', PARAM_ALPHANUMEXT);
	$label_domaine    = optional_param('label_domaine','', PARAM_ALPHANUMEXT);
	$label_competence = optional_param('label_competence','', PARAM_ALPHANUMEXT);
	$label_item= optional_param('label_item','', PARAM_ALPHANUMEXT);

    $sesskey     		= optional_param('sesskey', '', PARAM_ALPHA);
	$instance 			= optional_param('instance', 0, PARAM_INT);
	$select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement

    // $file = optional_param('file','',PARAM_PATH);
    // nouveaute Moodle 1.9 et 2
    $url = new moodle_url('/mod/refrentiel/editor/import_referentiel_xml.php');
    
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
		print_error(get_string('erreurscript','referentiel','Erreur01 : editor/import_referentiel_xml.php'));
	}

	// get parameters

    $params = new stdClass;
    
    $params->file = optional_param('file','',PARAM_PATH);
    $params->choosefile = optional_param('choosefile','',PARAM_PATH);
    $params->stoponerror = optional_param('stoponerror', 0, PARAM_BOOL);
    $params->override = optional_param('override', 0, PARAM_BOOL);
    $params->newinstance = optional_param('newinstance', 0, PARAM_BOOL);

    // get display strings
    $txt = new stdClass();
    $txt->uploadproblemfile =  get_string('uploadproblemfile', 'referentiel');
    $txt->referentiel = get_string('referentiel','referentiel');
    $txt->fileformat = get_string('fileformat','referentiel');
	$txt->choosefile = get_string('choosefile','referentiel');
	$txt->formatincompatible= get_string('formatincompatible','referentiel');
    $txt->file = get_string('file');
    $txt->fileformat = get_string('fileformat','referentiel');
    $txt->fromfile = get_string('fromfile','referentiel');
	$txt->importerror_referentiel_id = get_string('importerror_referentiel_id','referentiel');
    $txt->importerror = get_string('importerror','referentiel');
    $txt->importfilearea = get_string('importfilearea','referentiel');
    $txt->importfilearea_xml_simple = get_string('importfilearea_xml_simple','referentiel');
    $txt->importfileupload = get_string('importfileupload','referentiel');
    $txt->importfileupload_xml_simple = get_string('importfileupload_xml_simple','referentiel');
    $txt->importfromthisfile = get_string('importfromthisfile','referentiel');
    $txt->modulename = get_string('modulename','referentiel');
    $txt->modulenameplural = get_string('modulenameplural','referentiel');
    $txt->onlyteachersimport = get_string('onlyteachersimport','referentiel');
    $txt->stoponerror = get_string('stoponerror', 'referentiel');
	$txt->upload = get_string('upload');
    $txt->uploadproblem = get_string('uploadproblem');
    $txt->uploadthisfile = get_string('uploadthisfile');
	$txt->importreferentiel	= get_string('importreferentiel','referentiel');
	$txt->newinstance	= get_string('newinstance','referentiel');
	$txt->choix_newinstance	= get_string('choix_newinstance','referentiel');
	$txt->choix_notnewinstance	= get_string('choix_notnewinstance','referentiel');
	$txt->override = get_string('override', 'referentiel');
	$txt->choix_override	= get_string('choix_override','referentiel');
	$txt->choix_notoverride	= get_string('choix_notoverride','referentiel');

    require_login($course->id, false, $cm);   // pas d'autologin guest

    if (!isloggedin() or isguestuser()) {
        redirect($CFG->wwwroot.'/index.php?id='.$course->id);
    }


    // check role capability
    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}

    require_capability('mod/referentiel:import', $context);

    // ensure the files area exists for this course
    // Moodle 1.9
    // make_upload_directory( "$course->id/$CFG->moddata/referentiel" );

	if (empty($mode)){
        $mode='add'; // un seul mode possible
    }
    $url->param('mode', $mode);
    $action='importreferentiel'; // une seule action possible
    $url->param('action', $action);

    $defaultformat = FORMAT_MOODLE;

    // AFFICHAGE DE LA PAGE Moodle 2
	$strreferentiels = get_string('modulenameplural','referentiel');
	$strreferentiel = get_string('referentiel','referentiel');
	$strpagename=get_string('importreferentiel','referentiel');
	$strpagename=get_string('modifier_referentiel','referentiel');
    $strlastmodified = get_string('lastmodified');
    $pagetitle = strip_tags($course->shortname.': '.$strreferentiel.': '.format_string($referentiel->name,true));
    $icon = $OUTPUT->pix_url('icon','referentiel');

    $PAGE->set_url($url);

    $PAGE->set_title($pagetitle);
    $PAGE->navbar->add($strpagename);
    $PAGE->set_heading($course->fullname);

    // formulaire de saisie d'un fichier
    // $fileformatnames = referentiel_get_import_export_formats( 'import', 'rformat' );
    $fileformatnames=array('sxml');
    $options = array('subdirs'=>0, 'maxbytes'=>get_max_upload_file_size($CFG->maxbytes, $course->maxbytes, 0), 'maxfiles'=>1, 'accepted_types'=>'sxml', 'return_types'=>FILE_INTERNAL);
    $mform = new referentiel_import_form(null, array('d'=>$referentiel->id, 'contextid'=>$context->id, 'filearea'=>'referentiel', 'fileformats' => $fileformatnames, 'override' => 0, 'stoponerror' => 1, 'newinstance' => 0, 'action' => 'importreferentiel', 'options'=>$options));

    if ($mform->is_cancelled()) {
        redirect(new moodle_url('/course/view.php', array('id'=>$course->id)));
    }
    else if ($mform->get_data()) {

//        $returnlink = new moodle_url('/mod/referentiel/view.php', array('id'=>$cm->id));
        $returnlink=new moodle_url('mod/referentiel/editor/import_referentiel_xml.php', array('courseid'=>$course->id, 'sesskey'=>$sesskey,'instance'=>$instance, 'mode'=>$mode, 'action'=>$action));

        if ($formdata = $mform->get_data()) {
            // DEBUG
            // echo "<br />DEBUG :: import_instance.php :: 193 :: FORMDATA\n";
            // print_object($formdata);

            // documents activites et consignes des tâches
            $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie');
            if (empty($formdata->filearea) || !in_array($formdata->filearea, $fileareas)) {
                return false;
            }


            $fs = get_file_storage();
            // suppression du fichier existant ?   NON
            // $fs->delete_area_files($formdata->contextid, 'mod_referentiel', $formdata->filearea, 0);

            if ($newfilename= $mform->get_new_filename('referentiel_file')) {
                $contents = $mform->get_file_content('referentiel_file');

                if (!empty($contents)){
                    /*
                    $fullpath = "/$formdata->contextid/mod_referentiel/$formdata->filearea/0/$newfilename";
                    $link = new moodle_url($CFG->wwwroot.'/pluginfile.php'.$fullpath);
                    // DEBUG
                    echo "<br />DEBUG :: 219 :: $link<br />\n";
                    */
/*

                    $format=$formdata->format;

                    echo "<br />DEBUG :: editor/import_referentiel_xml :: 225 :: $format<br />\n";
                    if (! is_readable("format/$format/format.php")) {
                        print_error( get_string('formatnotfound','referentiel', $format) );
                    }
*/
                    require_once("../format.php");  // Parent class
                    require_once("format.php");
                    $classname = "rformat_xml";
                    // echo "<br />DEBUG :: 232 :: $classname<br />\n";

                    $rformat = new $classname();
                    // load data into class
                    $rformat->setIReferentiel( $referentiel ); // instance
                    // $rformat->setRReferentiel( $referentiel_referentiel ); // not yet
                    $rformat->setCourse( $course );
                    $rformat->setContext( $context );
                    $rformat->setCoursemodule( $cm);
                    $rformat->setContents( $contents );
                    $rformat->setStoponerror( $formdata->stoponerror );
                    $rformat->setOverride( $formdata->override );
                    $rformat->setNewinstance( $formdata->newinstance );
                    $rformat->setAction( $formdata->action );

                    $rformat->setFilename( $newfilename );

echo $OUTPUT->header();

                    // Do anything before that we need to
                    if (! $rformat->importpreprocess()) {
                        print_error( get_string('importerror','referentiel') , $returnlink);
                    }

                    // Process the uploaded file

                    if (! $rformat->importprocess() ) {
                        print_error( get_string('importerror','referentiel') , $returnlink);
                    }

                    // In case anything needs to be done after
                    if (! $rformat->importpostprocess()) {
                        print_error( get_string('importerror','referentiel') , $returnlink);
                    }

                    // Verifier si  referentiel charge
                    if (! $rformat->new_referentiel_id) {
                        print_error( get_string('importerror_referentiel_id','referentiel') , $returnlink);
                    }

                    // mettre a jour l'instance
                    $DB->set_field ('referentiel','ref_referentiel',$rformat->new_referentiel_id, array("id" => "$referentiel->id"));

//   redireiger vers le script add.php

echo "<hr />";
//
echo '
<form name="form" method="post" action="../add.php?id='.$cm->id.'">

<input type="hidden" name="name_instance" value="'.$name_instance.'" />
<input type="hidden" name="description_instance" value="'.$description_instance.'" />
<input type="hidden" name="label_domaine" value="'.$label_domaine.'" />
<input type="hidden" name="label_competence" value="'.$label_competence.'" />
<input type="hidden" name="label_item" value="'.$label_item.'" />

<input type="hidden" name="action" value="importreferentiel" />

<input type="hidden" name="new_referentiel_id" value="'.$rformat->new_referentiel_id.'" />
<input type="hidden" name="action" value="'.$rformat->action.'" />

<input type="hidden" name="sesskey" value="'.sesskey().'" />
<input type="hidden" name="courseid" value="'.$course->id.'" />
<input type="hidden" name="instance" value="'.$referentiel->id.'" />
<input type="hidden" name="mode" value="'.$mode.'" />

<center>
<input type="submit" value="'.get_string("continue").'" />
</center>
</form>
<div>
';
    echo $OUTPUT->footer();
    die();
                }
                else{
                    print_error( get_string('cannotread','referentiel') );
                }
            }

            redirect($returnlink);
            exit;
        }
    }
    echo $OUTPUT->header();

    if (!empty($referentiel->name)){
        echo '<div align="center"><h1>'.$referentiel->name.'</h1></div>'."\n";
    }


    require_once('onglets.php'); // menus sous forme d'onglets 
    //     $tab_onglets = new Onglets($context, $referentiel, $referentiel_referentiel, $cm, $course, $currenttab, $select_acc, $data_f); $tab_onglets->display();

    echo '<div align="center"><h2><img src="'.$icon.'" border="0" title=""  alt="" /> '.$strpagename.' '.$OUTPUT->help_icon('importreferentielh','referentiel').'</h2></div>'."\n";


    echo $OUTPUT->box_start('generalbox');
    $mform->display();
    echo $OUTPUT->box_end();
    echo $OUTPUT->footer();
    die();


?>


