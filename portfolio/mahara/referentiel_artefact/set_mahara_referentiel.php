<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   mod-referentiel
 * @copyright 2011 Jean Fruitet <jean.fruitet@univ-nantes.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

    // require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
    require_once("../../../config.php");
    require_once("$CFG->dirroot/repository/lib.php");
    require_once($CFG->dirroot . '/mod/referentiel/lib.php');
    // l'artefact Mahara / referentiel est utilise
    require_once($CFG->dirroot . '/mod/referentiel/portfolio/mahara_artefact_referentiel.class.php');

    $id    = optional_param('id', 0, PARAM_INT);    // course module id
	$d     = optional_param('d', 0, PARAM_INT);    // referentielbase id

    // $import   = optional_param('import', 0, PARAM_INT);    // show import form

    $certificatid   = optional_param('certificatid', 0, PARAM_INT);    //record certificat id
    $action  	= optional_param('action','', PARAM_ALPHANUMEXT); // pour distinguer differentes formes de traitements
    $mode       = optional_param('mode','', PARAM_ALPHANUMEXT);
    $add        = optional_param('add','', PARAM_ALPHA);
    $courseid   = optional_param('courseid', 0, PARAM_INT);
    $groupmode  = optional_param('groupmode', -1, PARAM_INT);
    $cancel     = optional_param('cancel', 0, PARAM_BOOL);
	$userid     = optional_param('userid', 0, PARAM_INT);
	$select_acc = optional_param('select_acc', 0, PARAM_INT);      // accompagnement

    // nouveaute Moodle 1.9 et 2
    $url = new moodle_url('/mod/referentiel/portfolio/set_mahara_referentiel.php');

	if ($d) {     // referentiel_referentiel_id
        if (! $referentiel = $DB->get_record("referentiel", array("id" => "$d"))) {
            print_error('Referentiel instance is incorrect');
        }
        if (! $referentiel_referentiel = $DB->get_record("referentiel_referentiel", array("id" => "$referentiel->ref_referentiel"))) {
            print_error('Réferentiel id is incorrect');
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
		print_error(get_string('erreurscript','referentiel','Erreur01 : portfolio/set_mahara_referentiel.php'), 'referentiel');
	}

    require_login($course->id, false, $cm);

    if (!isloggedin() or isguestuser()) {
        redirect($CFG->wwwroot.'/mod/referentiel/view.php?id='.$cm->id.'&amp;non_redirection=1');
    }


    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}




	if ($certificatid) { // id certificat
        if (! $certificat = $DB->get_record("referentiel_certificat", array("id" => "$certificatid"))) {
            print_error('certificat ID is incorrect');
        }
	}
	else{
        print_error('certificat ID is incorrect');
    }

    if ($cancel) {
        if (!empty($SESSION->returnpage)) {
            $return = $SESSION->returnpage;
            unset($SESSION->returnpage);
            redirect($return);
        }
        else {
            redirect('certificat.php?d='.$referentiel->id);
        }
    }

// AFFICHAGE DE LA PAGE
    $strreferentiel = get_string('modulename', 'referentiel');
    $strreferentiels = get_string('modulenameplural', 'referentiel');
    $strmessage = get_string('exportcertificat','referentiel');
    $strpagename=get_string('exportcertificat','referentiel');
    $strlastmodified = get_string('lastmodified');
    $pagetitle = strip_tags($course->shortname.': '.$strreferentiel.': '.format_string($referentiel->name,true));
    // $icon = $OUTPUT->pix_url('icon','referentiel');

    $artefact_referentiel=new referentiel_mahara_artefact($referentiel, $certificat);
    //DEBUG
    //echo "<br />DEBUG :: ./mod/referentiel/portfolio/set_mahara_referentiel.php :: Line 126 :: \n";
    //print_object($artefact_referentiel);
    //echo "<br />\n";
    
    if (!empty($artefact_referentiel) && empty($artefact_referentiel->hosts)){
        $hosts=$artefact_referentiel->get_hosts();
    }
    $hosts=$artefact_referentiel->hosts;

    //DEBUG
    //echo "<br />DEBUG :: ./mod/referentiel/portfolio/set_mahara_referentiel.php :: Line 138 :: \n";
    //print_object($artefact_referentiel);
    //echo "<br />\n";

    if ($hosts){
        // $action_url=new moodle_url('/mod/referentiel/portfolio/set_mahara_referentiel.php', array('id'=>$cm->id, 'certificatid' => $certificat->id ));
        // $mform = new upload_host_form($action_url, array('d'=>$referentiel->id, 'contextid'=>$context->id, 'userid'=>$USER->id, 'certificatid'=>$certificat->id, 'hosts'=>$hosts));
        $mform = new upload_host_form(null, array('d'=>$referentiel->id, 'contextid'=>$context->id, 'userid'=>$USER->id, 'certificatid'=>$certificat->id, 'hosts'=>$hosts));

        if ($mform->is_cancelled()) {
                    redirect(new moodle_url('/mod/referentiel/certificat.php', array('id'=>$cm->id)));
        }
        else {
                if ($formdata = $mform->get_data()) {
                    if (!empty($formdata->host)) {
                        $artefact_referentiel->set_remote_mnet_host_id($formdata->host);
                        // traiter le formulaire
                        $artefact_referentiel->process_referentiel_mahara();
                        // DEBUG
                        echo "<br />DEBUG :: set_mahara_referentiel.php :: 157\n<br />EXIT\n";
                        exit;
                        redirect(new moodle_url('/mod/referentiel/certificat.php', array('id'=>$cm->id)));
                    }
                }
                else{
                    $PAGE->set_url($url);
                    $PAGE->set_title($pagetitle);
                    $PAGE->navbar->add($strpagename);
                    $PAGE->set_heading($course->fullname);

                    echo $OUTPUT->header();
                    echo $OUTPUT->box_start('generalbox');
                    $mform->display();
                    echo $OUTPUT->box_end();
                    echo $OUTPUT->footer();
                    die();
                }
        }
    }


?>
