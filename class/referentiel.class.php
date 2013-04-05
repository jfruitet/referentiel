<?php  // $Id:  class/referentiel.class.php,v 1.0 2011/04/21 00:00:00 jfruitet Exp $
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
 * Library of functions and constants for module referentiel
 *
 * @author jfruitet
 * @version $Id: class/referentiel.class.php,v 1.0 2011/04/20 00:00:00 jfruitet Exp $
 * @package referentiel v 6.0.00 2011/04/21 00:00:00
 **/

// Version Moodle 2
// passage en modele objet



// ======================================================================

/**
 * Standard base class for all referentiel table.
 *
 * @package   mod-referentiel
 * @copyright 2011 onwards Jean Fruitete {@link http://www.univ-nantes.fr/}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class referentiel {

    /** @var object */
    var $cm;
    /** @var object */
    var $course;
    /** @var object */
    var $referentiel; // L'instance

    /** @var object */
    var $referentiel_referentiel; // Le réferentiel associe à l'instance
    
    /** @var string */
    var $strreferentiel;
    /** @var string */
    var $strreferentiels;
    /** @var string */
    var $strsubmissions;
    /** @var string */
    var $strlastmodified;
    /** @var string */
    var $pagetitle;
    /** @var bool */
    var $usehtmleditor;
    /**
     * @todo document this var
     */
    var $defaultformat;
    /**
     * @todo document this var
     */
    var $context;
    /*
    var $name;
    var $description_instance;
    var $label_domaine;
    var $label_competence;
    var $label_item;
    var $config;
    var $config_impression;
    var $config_globale;
    var $config_impression_globale;
    var $ref_referentiel;
    var $visible;
    var $intro;
    var $introformat;
    */
    
    /**
     * Constructor for the base referentiel class
     *
     * Constructor for the base referentiel class.
     * If cmid is set create the cm, course, referentiel objects.
     * If the referentiel is hidden and the user is not a teacher then
     * this prints a page header and notice.
     *
     * @global object
     * @global object
     * @param int $cmid the current course module id - not set for new assignments
     * @param object $referentiel usually null, but if we have it we pass it to save db access
     * @param object $cm usually null, but if we have it we pass it to save db access
     * @param object $course usually null, but if we have it we pass it to save db access
     */
    function referentiel($cmid='staticonly', $referentiel=NULL, $cm=NULL, $course=NULL) {
        global $COURSE, $DB;

        if ($cmid == 'staticonly') {
            //use static functions only!
            return;
        }

        global $CFG;

        if ($cm) {
            $this->cm = $cm;
        } else if (! $this->cm = get_coursemodule_from_id('referentiel', $cmid)) {
            print_error('invalidcoursemodule');
        }


        // Valable pour Moodle 2.1 et Moodle 2.2
        ////if ($CFG->version < 2011120100) {
            $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        //} else {
        //    //  $this->context = context_module::instance($this->cm); 
        //}

        if ($course) {
            $this->course = $course;
        } else if ($this->cm->course == $COURSE->id) {
            $this->course = $COURSE;
        } else if (! $this->course = $DB->get_record('course', array('id'=>$this->cm->course))) {
            print_error('invalidid', 'referentiel');
        }

        // valeurs par defaut
        $this->referentiel= new Object();
        
        $this->referentiel->name="";
        $this->referentiel->description_instance="";
        $this->referentiel->label_domaine=trim(get_string('domaine','referentiel'));
        $this->referentiel->label_competence=trim(get_string('competence','referentiel'));
        $this->referentiel->label_item=trim(get_string('item','referentiel'));
        $this->referentiel->label_item=trim(get_string('item','referentiel'));
        $this->referentiel->config=referentiel_creer_configuration('config');
        $this->referentiel->config_impression=referentiel_creer_configuration('impression');
        $this->referentiel->config_globale=$this->referentiel->config;
        $this->referentiel->config_impression_globale=$this->referentiel->config_impression;
        $this->referentiel->ref_referentiel=0;
        $this->referentiel->visible=1;
        $this->referentiel->intro="";
        $this->referentiel->introformat=1;
        $this->referentiel->maxbytes=1048576;

        if ($referentiel) {
            $this->referentiel = $referentiel;
        } else if (! $this->referentiel = $DB->get_record('referentiel', array('id'=>$this->cm->instance))) {
            print_error('invalidid', 'referentiel');
        }

        $this->referentiel->cmidnumber = $this->cm->idnumber; // compatibility with modedit referentiel obj
        $this->referentiel->course   = $this->course->id; // compatibility with modedit referentiel obj

        if (!empty($this->referentiel->ref_referentiel)){ // occurrence associe
            $this->referentiel_referentiel =  $DB->get_record('referentiel_referentiel', array('id'=>$this->referentiel->ref_referentiel));
        }

        $this->strreferentiel = get_string('modulename', 'referentiel');
        $this->strreferentiels = get_string('modulenameplural', 'referentiel');
        $this->strsubmissions = get_string('submissions', 'referentiel');
        $this->strlastmodified = get_string('lastmodified');
        $this->pagetitle = strip_tags($this->course->shortname.': '.$this->strreferentiel.': '.format_string($this->referentiel->name,true));

        // visibility handled by require_login() with $cm parameter
        // get current group only when really needed

        /// Set up things for a HTML editor if it's needed
        $this->defaultformat = editors_get_preferred_format();


    }

    /**
     * Display the referentiel, used by view.php
     *
     * This in turn calls the methods producing individual parts of the page
     */
    function view($mode, $currenttab, $select_acc, $data_filtre) {

        global $CFG, $USER;
        // Valable pour Moodle 2.1 et Moodle 2.2
        ////if ($CFG->version < 2011120100) {
            $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        //} else {
        //    //  $this->context = context_module::instance($this->cm); 
        //}

        require_capability('mod/referentiel:view', $this->context);

        add_to_log($this->course->id, "referentiel", "view", "view.php?id={$this->cm->id}",
                   $this->referentiel->id, $this->cm->id);

        $this->view_header();

        //
        // lien vers le referentiel lui-meme
        if (!$this->get_referentiel_referentiel()){
            // redirect ($CFG->wwwroot.'/mod/referentiel/add.php?id='.$this->cm->id.'&amp;sesskey='.sesskey());
            $this->view_intro();
            $this->view_instance_referentiel();
            $this->add_referentiel_referentiel();
        }
        else{
                $this->view_intro();
                $this->onglets($mode, $currenttab, $select_acc, $data_filtre);
                $this->view_title();
                $this->view_referentiel_referentiel();
        }
        $this->view_footer();
        die();
    }

    /**
     * Import the referentiel, used by import_instance.php
     *
     * This in turn calls the methods producing individual parts of the page
     */
    function load_referentiel($mode, $format='', $action='') {

        global $CFG, $USER;
        global $PAGE, $OUTPUT;
        
        // check role capability
        ////if ($CFG->version < 2011120100) {
            $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        //} else {
        //    //  $this->context = context_module::instance($this->cm); 
        //}

        require_capability('mod/referentiel:import', $this->context);

        add_to_log($this->course->id, "referentiel", "view", "import_instance.php?id={$this->cm->id}",
                   $this->referentiel->id, $this->cm->id);

        $this->view_header();

	   // get parameters

        $parametres = new stdClass;
        $parametres->choosefile = optional_param('choosefile','',PARAM_PATH);
        $parametres->stoponerror = optional_param('stoponerror', 0, PARAM_BOOL);
        $parametres->override = optional_param('override', 0, PARAM_BOOL);
        $parametres->newinstance = optional_param('newinstance', 0, PARAM_BOOL);

        // get display strings
        $txt = new stdClass();
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
        $txt->importfileupload = get_string('importfileupload','referentiel');
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
 /*
    	/// Print the page header
	    $strreferentiels = get_string('modulenameplural','referentiel');
    	$strreferentiel = get_string('referentiel','referentiel');
	    $strmessage =
	    $icon = '<img class="icon" src="'.$CFG->wwwroot.'/mod/referentiel/icon.gif" alt="'.get_string('modulename','referentiel').'"/>';

	    $strpagename=get_string('modifier_referentiel','referentiel');
*/

        // echo $OUTPUT->heading($strmessage, 'importreferentiel', 'referentiel', $icon);
        echo $OUTPUT->heading(get_string('importreferentiel','referentiel'), 2);
        
        // file upload form submitted
        if (!empty($format)) {
            if (!confirm_sesskey()) {
        	   print_error( 'sesskey' );
            }
            // file checks out ok
            $fileisgood = false;
            // work out if this is an uploaded file
            // or one from the filesarea.

            if (!empty($parametres->choosefile)) {
                $importfile = "{$CFG->dataroot}/{$this->course->id}/{$parametres->choosefile}";
                if (file_exists($importfile)) {
                    $fileisgood = true;
                }
                else {
                    notify($txt->uploadproblem);
                }
            }
            else {
                // must be upload file
                if (empty($_FILES['newfile'])) {
                    notify( $txt->uploadproblem );
                }
                else if ((!is_uploaded_file($_FILES['newfile']['tmp_name']) or $_FILES['newfile']['size'] == 0)) {
                    notify( $txt->uploadproblem );
                }
                else {
                    $importfile = $_FILES['newfile']['tmp_name'];
                // tester l'extention du fichier
                // DEBUG
                // echo "<br />DEBUG : 214 import_instance.php<br />FORMAT : $format<br />IMPORT_FILE $importfile\n";
       			// Les données suivantes sont disponibles après chargement
			    // echo "<br />DEBUG :: Fichier téléchargé : '". $_FILES['newfile']['tmp_name'] ."'\n";
                // echo "<br />DEBUG :: Nom : '". $_FILES['newfile']['name'] ."'\n";
			    // echo "<br />DEBUG :: Erreur : '". $_FILES['newfile']['error'] ."'\n";
			    // echo "<br />DEBUG :: Taille : '". $_FILES['newfile']['size'] ."'\n";

                // echo "<br />DEBUG :: Type : '". $_FILES['newfile']['type'] ."'\n";
                    $nom_fichier_charge_extension = substr( strrchr($_FILES['newfile']['name'], "." ), 1);
			    // echo "<br />DEBUG :: LIGNE 223 :: Extension : '". $nom_fichier_charge_extension ."'\n";
			    // echo "<br />DEBUG :: LE FICHIER EST CHARGE\n";
                    if ($nom_fichier_charge_extension!=$format){
                        notify( $txt->formatincompatible);
                    }
                    else{
                        $fileisgood = true;
                    }
                }
            }

            // process if we are happy, file is ok
            if ($fileisgood) {
                $returnlink=$CFG->wwwroot.'/mod/referentiel/import_instance.php?courseid='.$this->course->id.'&amp;sesskey='.sesskey().'&amp;instance='.$instance.'&amp;mode='.$mode.'&amp;action='.$action;
			    // DEBUG
			    // echo "<br/>RETURNLINK : $returnlink\n";

                if (! is_readable("format/$format/format.php")) {
                    print_error( get_string('formatnotfound','referentiel', $format) );
                }
                require("format.php");  // Parent class
                require("format/$format/format.php");
                $classname = "rformat_$format";
                $rformat = new $classname();
                // load data into class
                $rformat->setIReferentiel( $this->referentiel ); // instance
                // $rformat->setRReferentiel( $this->referentiel_referentiel ); // not yet
                $rformat->setCourse( $this->course );
                $rformat->setContext( $this->context);
                $rformat->setCoursemodule( $cm);
                $rformat->setFilename( $importfile );
                $rformat->setStoponerror( $parametres->stoponerror );
			    $rformat->setOverride( $parametres->override );
			    $rformat->setNewinstance( $parametres->newinstance );
			    $rformat->setAction( $action );

			    // $rformat->setReturnpage("");

                // Do anything before that we need to
                if (! $rformat->importpreprocess()) {
                    print_error( $txt->importerror , $returnlink);
                }

                // Process the uploaded file

                if (! $rformat->importprocess() ) {
                    print_error( $txt->importerror , $returnlink);
                }

                // In case anything needs to be done after
                if (! $rformat->importpostprocess()) {
                    print_error( $txt->importerror , $returnlink);
                }

			    // Verifier si  referentiel charge
                if (! $rformat->new_referentiel_id) {
                    print_error( $txt->importerror_referentiel_id , $returnlink);
                }
                echo '<hr />
<form name="form" method="post" action="add.php?id='.$this->cm->id.'">

<input type="hidden" name="name_instance" value="'.$this->referentiel->name_instance  .'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8')  .'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine  .'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence  .'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item  .'" />

<input type="hidden" name="action" value="importreferentiel" />

<input type="hidden" name="new_referentiel_id" value="'.$rformat->new_referentiel_id.'" />
<input type="hidden" name="action" value="'.$rformat->action.'" />

<input type="hidden" name="sesskey" value="'.  sesskey().'" />
<input type="hidden" name="course" value="'. $this->course->id.'" />
<input type="hidden" name="instance" value="'.  $this->referentiel->id.'" />
<input type="hidden" name="mode" value="'.$mode.'" />
<input type="submit" value="'. get_string("continue").'" />
</form>
<div>
'. "\n";
                $this->view_footer();
                die();
            }
        }

        /// Print upload form

        // get list of available import formats
        $fileformatnames = referentiel_get_import_export_formats( 'import', 'rformat' );

	   //==========
        // DISPLAY
        //==========

        echo '<form id="form" enctype="multipart/form-data" method="post" action="import_instance.php?id='.  $this->cm->id .'">
        <fieldset class="invisiblefieldset" style="display: block;">'."\n";
        //echo $OUTPUT->box_start('generalbox boxwidthnormal boxaligncenter');
        echo $OUTPUT->box_start('generalbox  boxaligncenter');
        echo '
<table cellpadding="5">
<tr>
<td align="right">'.$txt->fileformat.'</td>
<td>'. html_writer::select($fileformatnames, 'format', 'xml', false).'</td>
<td>'. $OUTPUT->help_icon('formath', 'referentiel'); //, "referentiel", $txt->importreferentiel);
        echo '</td>
</tr>
<tr>
<td align="right">'.  $txt->stoponerror.'
</td>
<td>
<input name="stoponerror" type="checkbox" checked="checked" />
</td>
<td>
&nbsp;
</td>
</tr>
<tr>
<td align="right">'.  $txt->override.'
</td>
<td>
<input name="override" type="radio" value="1" /> '.  $txt->choix_override.'
<br />
<input name="override" type="radio"  value="0"  checked="checked" /> '. $txt->choix_notoverride.'
</td>
<td>'. $OUTPUT->help_icon('overrider', 'referentiel').'</td>
</tr>
';
        if (!empty($this->referentiel->ref_referentiel)){
            echo '
<tr>
<td align="right">'.$txt->newinstance.'</td>
<td>
<input name="newinstance" type="radio"  value="1"  checked="checked"/> '.  $txt->choix_newinstance.'
<br />
<input name="newinstance" type="radio"   value="0" /> '.  $txt->choix_notnewinstance.'
</td>
<td>
'. $OUTPUT->help_icon('overrideo', 'referentiel').'
</td>
</tr>
';
        }
        else{
            echo '<input name="newinstance" type="hidden"  value="1" />'."\n";
        }
        echo '
</table>
';
        echo $OUTPUT->box_end();

        echo $OUTPUT->box_start('generalbox  boxaligncenter');

        echo $txt->importfileupload.'
            <table cellpadding="5">
                <tr>
                    <!-- td align="right">'.  $txt->upload.':</td -->
                    <td colspan="2">
';

        // upload_print_form_fragment(1,array('newfile'),null,false,null,$this->course->maxbytes,0,false);
        echo 'upload_print_form_fragment deprecated';
        echo '</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="save" value="'.  $txt->uploadthisfile.'" /></td>
                </tr>
            </table>
';
        echo $OUTPUT->box_end();

        echo $OUTPUT->box_start('generalbox boxaligncenter');
        echo $txt->importfilearea.'
            <table cellpadding="5">
                <tr>
                    <td align="right">'.  $txt->file.':</td>
                    <td><input type="text" name="choosefile" size="60" /></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>
';

        echo $OUTPUT->single_button("/files/index.php?id={$this->course->id}&choose=form.choosefile", $txt->choosefile);
        echo '<br />
<input type="submit" name="save" value="'.  $txt->importfromthisfile.'" /></td>
                </tr>
            </table>
';
        echo $OUTPUT->box_end();
        echo '
<input type="hidden" name="action" value="'.$action.'" />

<input type="hidden" name="name_instance" value="'.$this->referentiel->name  .'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8') .'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine  .'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence  .'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item  .'" />

<!-- These hidden variables are always the same -->

<input type="hidden" name="sesskey" value="'. sesskey().'" />
<input type="hidden" name="course" value="'.  $this->course->id.'" />
<input type="hidden" name="instance" value="'. $this->referentiel->id.'" />
<input type="hidden" name="mode" value="'.$mode.'" />

        </fieldset>
    </form>'."\n";
        $this->view_footer();
        die();
    }
    
    
    /**
     * return Object referentiel_referentiel
     *
     **/
    function get_referentiel_referentiel(){
        // DEBUG
        global $DB;

        if (!empty($this->referentiel->ref_referentiel)){
            $referentiel_referentiel=$DB->get_record('referentiel_referentiel', array("id" => $this->referentiel->ref_referentiel));
            if (!empty($referentiel_referentiel)){
                return $referentiel_referentiel;
            }
        }
        return NULL;
    }

    /**
     * Display the header and top of a page
     *
     * This is used by the view() method to print the header of view.php but
     * it can be used on other pages in which case the string to denote the
     * page in the navigation trail should be passed as an argument
     *
     * @global object
     * @param string $subpage Description of subpage to be used in navigation trail
     */
    function view_header($subpage='') {
        global $CFG, $PAGE, $OUTPUT;

        if ($subpage) {
            $PAGE->navbar->add($subpage);
        }

        $PAGE->set_title($this->pagetitle);
        $PAGE->set_heading($this->course->fullname);

        echo $OUTPUT->header();

        //groups_print_activity_menu($this->cm, $CFG->wwwroot . '/mod/referentiel/view.php?id=' . $this->cm->id.'&non_redirection=1');

        echo '<div class="reportlink">'.$this->submittedlink().'</div>';
        echo '<div class="clearer"></div>';
    }

    /**
     * Display the referentiel intro
     *
     * The default implementation prints the referentiel description in a box
     */
    function view_intro() {

        if (!empty($this->referentiel->name)){
            echo '<div align="center"><h1>'.$this->referentiel->name.'</h1></div>'."\n";
        }
           // plagiarism_print_disclosure($this->cm->id);
    }
    
    /**
     * Display the referentiel intro
     *
     * The default implementation prints the referentiel description in a box
     */
    function view_title() {
        global $OUTPUT;

        $icon = $OUTPUT->pix_url('icon','referentiel');

        $s='<div align="center"><h2><img src="'.$icon.'" border="0" title="" alt="" /> '.get_string('listreferentiel', 'referentiel').' '.get_string('referentiel', 'referentiel').' ';
        if (!empty($this->referentiel_referentiel->code_referentiel)){
            $s.=' '.$this->referentiel_referentiel->code_referentiel;
        }
        $s.=$OUTPUT->help_icon('referentielh','referentiel').'</h2></div>'."\n";
        echo $s;
    }

    /**
     * Display the referentiel instance datas
     *
     */
    /*
    function view_instance_referentiel() {
        global $OUTPUT;
        $icon = $OUTPUT->pix_url('icon','referentiel');
        $s='<h2><img src="'.$icon.'" border="0" title="" alt="" /> '.get_string('referentiel_instance', 'referentiel').' ';
        if (!empty($this->referentiel->name)){
            $s.=' '.$this->referentiel->name;
        }
        $s.=$OUTPUT->help_icon('referentielinstanceh','referentiel').'</h2>'."\n";
        echo $s;
        echo '<table>'."\n";
        if ($this->referentiel->date_instance) {
            echo '<tr><th>'.get_string('availabledate','referentiel').':</th>';
            echo '    <td>'.userdate($this->referentiel->date_instance).'</td></tr>';
        }
        echo '<tr><th>'.get_string('name_instance','referentiel').':</th>';
        echo '    <td>'.htmlentities($this->referentiel->name, ENT_QUOTES, 'UTF-8').'</td></tr>';
        echo '<tr><th>'.get_string('description_instance','referentiel').':</th>';
        echo '    <td>'.htmlentities(this->referentiel->description_instance, ENT_QUOTES, 'UTF-8').'</td></tr>';
        echo '<tr><th>'.get_string('label_domaine','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_domaine.'</td></tr>';
        echo '<tr><th>'.get_string('label_competence','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_competence.'</td></tr>';
        echo '<tr><th>'.get_string('label_item','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_item.'</td></tr>';
        echo '<tr><th colspan="2">'.get_string('maxsize','referentiel', display_size($this->referentiel->maxbytes)).'</th>'."\n";

        echo '</table>'."\n";

    }
    */
    function view_instance_referentiel() {
        global $OUTPUT;
        $icon = $OUTPUT->pix_url('icon','referentiel');
        $s='<h2><img src="'.$icon.'" border="0" title="" alt="" /> '.get_string('referentiel_instance', 'referentiel').' ';
        if (!empty($this->referentiel->name)){
            $s.=' '.$this->referentiel->name;
        }
        $s.=$OUTPUT->help_icon('referentielinstanceh','referentiel').'</h2>'."\n";
        echo $s;
        echo '<table>'."\n";
        if ($this->referentiel->date_instance) {
            echo '<tr><th>'.get_string('availabledate','referentiel').':</th>';
            echo '    <td>'.userdate($this->referentiel->date_instance).'</td></tr>';
        }
        echo '<tr><th>'.get_string('name_instance','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->name .'</td></tr>';
        echo '<tr><th>'.get_string('description_instance','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->description_instance.'</td></tr>';
        echo '<tr><th>'.get_string('label_domaine','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_domaine.'</td></tr>';
        echo '<tr><th>'.get_string('label_competence','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_competence.'</td></tr>';
        echo '<tr><th>'.get_string('label_item','referentiel').':</th>';
        echo '    <td>'.$this->referentiel->label_item.'</td></tr>';
        echo '<tr><th colspan="2">'.get_string('maxsize','referentiel', display_size($this->referentiel->maxbytes)).'</th></tr>'."\n";

        echo '</table>'."\n";

    }

    /**
     * Display the referentiel
     *
     * The default implementation prints the referentiel description in a table
     */
    function view_referentiel_referentiel() {
        referentiel_affiche_referentiel_instance($this->referentiel->id);
    }

    /**
     * Display the referentiel thumbs
     *
     */
    function onglets($mode, $currenttab, $select_acc, $data_filtre) {
    ///////////// TABS ////////////////
    global $USER;
    global $CFG;
    //MODIF JF 2012/09/20
    // Filtres
    $str_filtre='';
    if (isset($select_acc) && !empty($data_filtre)){
        $str_filtre='&amp;select_acc='.$select_acc.'&amp;filtre_auteur='.$data_filtre->filtre_auteur.'&amp;filtre_validation='.$data_filtre->filtre_validation.'&amp;filtre_referent='.$data_filtre->filtre_referent.'&amp;filtre_date_modif='.$data_filtre->filtre_date_modif.'&amp;filtre_date_modif_student='.$data_filtre->filtre_date_modif_student;
    }

    if (empty($currenttab)) {
            $currenttab = 'referentiel';
    }

    // Administrateur ou Auteur ?
    // $isadmin=referentiel_is_admin($USER->id, $this->course->id); // cette fonction necessite l'inscription au cours
    $roles=referentiel_roles_in_instance($this->referentiel->id);
    //print_object($roles);
    $isadmin=$roles->is_admin;
    $isstudent=$roles->is_student;
    if (!empty($this->referentiel_referentiel)){
            $isreferentielauteur=referentiel_is_author($USER->id, $this->referentiel_referentiel, !$isstudent);
    }
    else{
            $isreferentielauteur=false;
    }


// DEBUG
/*
if ($isadmin){
    echo "<br />DEBUG : ADMIN\n";
}
else{
    echo "<br />DEBUG : NON ADMIN\n";
}
if ($isreferentielauteur){
    echo "<br />DEBUG : AUTEUR\n";
}
else{
    echo "<br />DEBUG : NON AUTEUR\n";
}
if ($isstudent){
    echo "<br />DEBUG : ETUDIANT\n";
}
else{
    echo "<br />DEBUG : NON ETUDIANT\n";
}
*/
        

        $tabs = array();
        $row  = array();
        $inactive = NULL;
        $activetwo = NULL;


        // premier onglet
        if (has_capability('mod/referentiel:view', $this->context)) {
        	$row[] = new tabobject('referentiel', $CFG->wwwroot.'/mod/referentiel/view.php?d='.$this->referentiel->id.$str_filtre.'&amp;non_redirection=1', get_string('referentiel','referentiel'));
        }


        if (isloggedin()) {
            // Accompagnement
            if (has_capability('mod/referentiel:write', $this->context)) {
                $addstring = get_string('accompagnement', 'referentiel');
                $row[] = new tabobject('menuacc', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$this->referentiel->id.'&amp;mode=accompagnement'.$str_filtre, $addstring);
            }

            // activites
            if (referentiel_user_can_addactivity($this->referentiel)) {
                // took out participation list here!
                // $addstring = empty($editentry) ? get_string('edit_activity', 'referentiel') : get_string('validation', 'referentiel');
                $addstring = get_string('edit_activity', 'referentiel');
                $row[] = new tabobject('list', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=list'.$str_filtre, $addstring);
            }

            // taches
            if (has_capability('mod/referentiel:addtask', $this->context) || has_capability('mod/referentiel:viewtask', $this->context)) {
                // took out participation list here!
                // $addstring = empty($editentry) ? get_string('edit_activity', 'referentiel') : get_string('validation', 'referentiel');
                $addstring = get_string('tasks', 'referentiel');
                $row[] = new tabobject('task', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$this->referentiel->id.'&amp;mode=listtasksingle'.$str_filtre, $addstring);
            }


            // gestion des certificats
            if (has_capability('mod/referentiel:write', $this->context)) {
                $row[] = new tabobject('certificat', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;mode=listcertif'.$str_filtre, get_string('certificat','referentiel'));
            }


            // scolarite
            $scolarite_locale_visible=referentiel_get_item_configuration('scol', $this->referentiel->id)==0;
            if (($scolarite_locale_visible	&&  has_capability('mod/referentiel:viewscolarite', $this->context))
               || has_capability('mod/referentiel:managescolarite', $this->context)) {
                $row[] = new tabobject('scolarite', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc, get_string('scolarite','referentiel'));
                $row[] = new tabobject('pedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc, get_string('formation','referentiel'));
            }

            $tabs[] = $row;

            // ACCOMPAGNEMENT

            if (isset($currenttab) && has_capability('mod/referentiel:write', $this->context)
                && (
                ($currenttab == 'menuacc')
                || ($currenttab == 'accompagnement')
                || ($currenttab == 'suivi')
                || ($currenttab == 'notification'))
                )
            {
                $row  = array();
                $inactive[] = 'menuacc';
                // accompagnement
                $row[] = new tabobject('accompagnement', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=accompagnement', get_string('accompagnement','referentiel'));
                $row[] = new tabobject('suivi', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=suivi', get_string('repartition','referentiel'));
                if (has_capability('mod/referentiel:managecertif', $this->context)) {      // rôle enseignant
                    $row[] = new tabobject('notification', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=notification', get_string('notification','referentiel'));
                }
                $tabs[] = $row;
                $activetwo = array('menuacc');
            }

            // ACTIVITE
            if (isset($currenttab) && (($currenttab == 'list')
                || ($currenttab == 'listactivity')
                || ($currenttab == 'listactivitysingle')
		        || ($currenttab == 'listactivityall')
		        || ($currenttab == 'addactivity')
		        || ($currenttab == 'updateactivity')
		        || ($currenttab == 'exportactivity'))) {
                $row  = array();
                $inactive[] = 'list';
$row[] = new tabobject('listactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=listactivity'.$str_filtre, get_string('listactivity','referentiel'));
$row[] = new tabobject('listactivityall', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=listactivityall'.$str_filtre, get_string('listactivityall','referentiel'));

                if (has_capability('mod/referentiel:addactivity', $this->context)) {
$row[] = new tabobject('addactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=addactivity'.$str_filtre, get_string('addactivity','referentiel'));
                }
                if (!has_capability('mod/referentiel:managecertif', $this->context)) {      // rôle etudiant : uniquement pour modifier une activite
                    if ($mode=='updateactivity'){
$row[] = new tabobject('updateactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=updateactivity'.$str_filtre, get_string('updateactivity','referentiel'));
    			}
            }
            else {
$row[] = new tabobject('updateactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=updateactivity'.$str_filtre, get_string('updateactivity','referentiel'));

            }
            if (has_capability('mod/referentiel:export', $this->context)) {
$row[] = new tabobject('updateactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$this->referentiel->id.'&amp;mode=updateactivity'.$str_filtre, get_string('updateactivity','referentiel'));

            }
	        $tabs[] = $row;
            $activetwo = array('list');
        }

        // TACHES
        if (isset($currenttab) && ( ($currenttab == 'listtask')
		|| ($currenttab == 'listtasksingle')
		|| ($currenttab == 'selecttask')
		|| ($currenttab == 'imposetask')
		|| ($currenttab == 'addtask')
		|| ($currenttab == 'updatetask')
		|| ($currenttab == 'exporttask')
		|| ($currenttab == 'importtask')
		)) {
            $row  = array();
            $inactive[] = 'task';
		    if (has_capability('mod/referentiel:viewtask', $this->context)) {
			$row[] = new tabobject('listtask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$this->referentiel->id.'&amp;mode=listtask'.$str_filtre,  get_string('listtask','referentiel'));
			$row[] = new tabobject('listtasksingle', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$this->referentiel->id.'&amp;mode=listtasksingle'.$str_filtre,  get_string('listtasksingle','referentiel'));
		}

	    if (has_capability('mod/referentiel:addtask', $this->context)) {
			$row[] = new tabobject('addtask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$this->referentiel->id.'&amp;mode=addtask'.$str_filtre,  get_string('addtask','referentiel'));
			$row[] = new tabobject('updatetask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$this->referentiel->id.'&amp;mode=updatetask'.$str_filtre,  get_string('updatetask','referentiel'));
		}

		// IMPORT a faire

		if (has_capability('mod/referentiel:import', $this->context)) {
			$row[] = new tabobject('importtask', $CFG->wwwroot.'/mod/referentiel/import_task.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importtask',  get_string('import','referentiel'));
		}

		// EXPORT

		if (has_capability('mod/referentiel:export', $this->context)) {
			$row[] = new tabobject('exporttask', $CFG->wwwroot.'/mod/referentiel/export_task.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exporttask',  get_string('export','referentiel'));
		}

		$tabs[] = $row;
        $activetwo = array('task');
        }

    	// CERTIFICATS
	    else if (isset($currenttab) && (($currenttab == 'certificat')
		|| ($currenttab == 'verroucertif')
		|| ($currenttab == 'statcertif')
		|| ($currenttab == 'listcertif')
		|| ($currenttab == 'listcertifsingle')
		|| ($currenttab == 'scolarite')
		|| ($currenttab == 'addcertif')
		|| ($currenttab == 'editcertif')
		|| ($currenttab == 'printcertif')
		|| ($currenttab == 'managecertif'))) {
		$row  = array();
        $inactive[] = 'certificat';

		if (has_capability('mod/referentiel:view', $this->context)) { // afficher un certificat
      	    $row[] = new tabobject('listcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;mode=listcertif&amp;sesskey='.sesskey().$str_filtre, get_string('listcertif', 'referentiel'));
            if (has_capability('mod/referentiel:rate', $this->context)) { // rediger un certificat
                $label_thumb=get_string('editcertif', 'referentiel');
            }
            else{
                $label_thumb=get_string('synthese_certificat', 'referentiel');
            }
            $row[] = new tabobject('editcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;mode=editcertif&amp;sesskey='.sesskey().$str_filtre, $label_thumb);

            if (referentiel_site_can_print_graph($this->referentiel->id) ){
                $row[] = new tabobject('statcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;mode=statcertif&amp;sesskey='.sesskey().$str_filtre, get_string('statcertif', 'referentiel'));
            }
		}
		if (has_capability('mod/referentiel:managecertif', $this->context)) {
      	    $row[] = new tabobject('managecertif', $CFG->wwwroot.'/mod/referentiel/export_certificat.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=managecertif&amp;sesskey='.sesskey(), get_string('managecertif', 'referentiel'));
			if (referentiel_site_can_print_referentiel($this->referentiel->id)) {
      	    	$row[] = new tabobject('printcertif', $CFG->wwwroot.'/mod/referentiel/print_certificat.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=printcertif&amp;sesskey='.sesskey(), get_string('printcertif', 'referentiel'));
			}
      	    $row[] = new tabobject('verroucertif', $CFG->wwwroot.'/mod/referentiel/verrou_certificat.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=verroucertif&amp;sesskey='.sesskey(), get_string('verroucertif', 'referentiel'));
		}
        if ($currenttab == '') {
            $currenttab = $mode = 'listcertif';
        }
        $tabs[] = $row;
        $activetwo = array('certificat');
        }

        // SCOLARITE
        else if (isset($currenttab)
        &&  (has_capability('mod/referentiel:viewscolarite', $this->context)
        || has_capability('mod/referentiel:managescolarite', $this->context))
        &&
		(   $scolarite_locale_visible &&
			($currenttab == 'scolarite')
			|| ($currenttab == 'listetudiant')
			|| ($currenttab == 'manageetab')
			|| ($currenttab == 'addetab')
			|| ($currenttab == 'listeetab')
			|| ($currenttab == 'exportetudiant')
			|| ($currenttab == 'importetudiant')
			|| ($currenttab == 'editetudiant')

		)
		)
        {
            $row  = array();
            $inactive[] = 'scolarite';

            $row[] = new tabobject('listetudiant', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listetudiant&amp;sesskey='.sesskey(), get_string('listetudiant', 'referentiel'));

            if (has_capability('mod/referentiel:managescolarite', $this->context)) { // import export
                if ($currenttab == 'editetudiant'){
                    $row[] = new tabobject('editetudiant', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=updateetudiant&amp;sesskey='.sesskey(), get_string('editetudiant', 'referentiel'));
                }
                $row[] = new tabobject('exportetudiant', $CFG->wwwroot.'/mod/referentiel/export_etudiant.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exportetudiant&amp;sesskey='.sesskey(), get_string('exportetudiant', 'referentiel'));
                $row[] = new tabobject('importetudiant', $CFG->wwwroot.'/mod/referentiel/import_etudiant.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importetudiant&amp;sesskey='.sesskey(), get_string('importetudiant', 'referentiel'));
        	}
            if (has_capability('mod/referentiel:viewscolarite', $this->context)) { // etablissement
                $row[] = new tabobject('listeetab', $CFG->wwwroot.'/mod/referentiel/etablissement.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listeetab&amp;sesskey='.sesskey(), get_string('etablissements', 'referentiel'));
            }
            if (has_capability('mod/referentiel:managescolarite', $this->context)) { // etablissement
                $row[] = new tabobject('manageetab', $CFG->wwwroot.'/mod/referentiel/etablissement.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=addetab&amp;sesskey='.sesskey(), get_string('manageetab', 'referentiel'));
            }

            if ($currenttab == '') {
                $currenttab = $mode = 'listetudiant';
            }
            $tabs[] = $row;
            $activetwo = array('scolarite');
        }

        // PEDAGOGIE
        else if (isset($currenttab)
        &&  (has_capability('mod/referentiel:viewscolarite', $this->context)
        || has_capability('mod/referentiel:managescolarite', $this->context))
        &&
		(   $scolarite_locale_visible &&
		    ($currenttab == 'pedago')
			|| ($currenttab == 'addpedago')
			|| ($currenttab == 'editpedago')
			|| ($currenttab == 'listpedago')
            || ($currenttab == 'listasso')
            || ($currenttab == 'editasso')
			|| ($currenttab == 'importpedago')
			|| ($currenttab == 'exportpedago')

		)
		)
        {
            $row  = array();
            $inactive[] = 'pedago';
            $row[] = new tabobject('listpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listpedago&amp;sesskey='.sesskey(), get_string('listpedago', 'referentiel'));
            $row[] = new tabobject('listasso', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listasso&amp;sesskey='.sesskey(), get_string('listasso', 'referentiel'));

            if (has_capability('mod/referentiel:managescolarite', $this->context)) { // import export
                $row[] = new tabobject('addpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=addpedago&amp;sesskey='.sesskey(), get_string('addpedago', 'referentiel'));
                if ($currenttab == 'editpedago'){
                    $row[] = new tabobject('editpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=updatepedago&amp;sesskey='.sesskey(), get_string('editpedago', 'referentiel'));
                }

                $row[] = new tabobject('editasso', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=editasso&amp;sesskey='.sesskey(), get_string('editasso', 'referentiel'));
     	        $row[] = new tabobject('importpedago', $CFG->wwwroot.'/mod/referentiel/import_pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importpedago&amp;sesskey='.sesskey(), get_string('importpedago', 'referentiel'));
			    $row[] = new tabobject('exportpedago', $CFG->wwwroot.'/mod/referentiel/export_pedagogie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exportpedago&amp;sesskey='.sesskey(), get_string('exportpedago', 'referentiel'));

        	}

            if ($currenttab == '') {
                $currenttab = $mode = 'listpedago';
            }
            $tabs[] = $row;
            $activetwo = array('pedago');
        }
        // REFERENTIELS
	    else if (isset($currenttab) && (($currenttab == 'configref')
        || ($currenttab == 'protocole')
        || ($currenttab == 'referentiel') || ($currenttab == 'listreferentiel')
        || ($currenttab == 'editreferentiel') || ($currenttab == 'deletereferentiel')
        || ($currenttab == 'import')  || ($currenttab == 'import_simple')
        || ($currenttab == 'export'))) {
	   	$row  = array();
		$inactive[] = 'referentiel';

		if (has_capability('mod/referentiel:view', $this->context)) {
			$row[] = new tabobject('listreferentiel', $CFG->wwwroot.'/mod/referentiel/view.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listreferentiel&amp;non_redirection=1',  get_string('listreferentiel','referentiel'));
		}

		// NOUVEAU CONTROLE v3.0
		//
        // DEBUG
        /*
        if (referentiel_site_can_write_or_import_referentiel($this->referentiel->id)){
            echo "<br />DEBUG :: tabs.php :: 1057 :: VRAI\n";
        }
        else{
            echo"<br />DEBUG :: tabs.php :: 1060 :: FAUX\n";
        }
        */

        if (
            (isset($isadmin) && $isadmin) || (isset($isreferentielauteur) && $isreferentielauteur)
            ||
            (referentiel_site_can_write_or_import_referentiel($this->referentiel->id) && empty($isstudent) )
        )
        {
            // 2012/02/13
            $row[] = new tabobject('protocole', $CFG->wwwroot.'/mod/referentiel/edit_protocole.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=protocole&amp;sesskey='.sesskey(),  get_string('protocole','referentiel'));

            if (has_capability('mod/referentiel:writereferentiel', $this->context)) {
                // 2010/10/18
                $row[] = new tabobject('configref', $CFG->wwwroot.'/mod/referentiel/config_ref.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=configref&amp;sesskey='.sesskey(),  get_string('configref','referentiel'));
    	    	$row[] = new tabobject('editreferentiel', $CFG->wwwroot.'/mod/referentiel/edit.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=editreferentiel&amp;sesskey='.sesskey(),  get_string('editreferentiel','referentiel'));
    	    	$row[] = new tabobject('deletereferentiel', $CFG->wwwroot.'/mod/referentiel/delete.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=deleteferentiel&amp;sesskey='.sesskey(),  get_string('deletereferentiel','referentiel'));
			}
			if (has_capability('mod/referentiel:import', $this->context)) {
                $row[] = new tabobject('import', $CFG->wwwroot.'/mod/referentiel/import.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=import',  get_string('import','referentiel'));
            }
/*
			if (has_capability('mod/referentiel:import', $this->context) && referentiel_editor_is_ok()){
                $row[] = new tabobject('import_simple', $CFG->wwwroot.'/mod/referentiel/editor/import_referentiel_simplifie.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=import',  get_string('import_referentiel_xml','referentiel'));
			}
*/
        }
        else{
            // MODIF JF 2012/02/13
    	    $row[] = new tabobject('protocole', $CFG->wwwroot.'/mod/referentiel/protocole.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=protocole&amp;sesskey='.sesskey(),  get_string('protocole','referentiel'));
        }

        if (has_capability('mod/referentiel:export', $this->context)) {
    		$row[] = new tabobject('export', $CFG->wwwroot.'/mod/referentiel/export.php?d='.$this->referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=export',  get_string('export','referentiel'));
        }

		if ($currenttab == '') {
            $currenttab = $mode = 'listreferentiel';
        }

		// print_r($row);
		// exit;
	    $tabs[] = $row;
		$activetwo = array('referentiel');
        }

        /// Print out the tabs and continue!
        // print_r($tabs);
        // exit;
        print_tabs($tabs, $currenttab, $inactive, $activetwo);
    }

}

    /**
     * Display the bottom and footer of a page
     *
     * This default method just prints the footer.
     * This will be suitable for most referentiel types
     */
    function view_footer() {
        global $OUTPUT;
        echo $OUTPUT->footer();
    }

    /**
     * Add / import a referentiel_referentiel
     *
     *
     */
    function add_referentiel_referentiel(){
    // proposer l'import ou la creation d'un référentiel
        global $OUTPUT;
        echo $OUTPUT->box_start('generalbox boxaligncenter', 'associer');
        echo '<h2 align="center">'.get_string('aide_creer_referentiel','referentiel').'</h2>';


        $ok_existe_au_moins_un_referentiel=(referentiel_referentiel_exists()>0);
        $ok_creer_importer_referentiel=referentiel_get_item_configuration('creref', $this->referentiel->id);
        // creation importation possible si $ok_selectionner_referentiel
        $ok_selectionner_referentiel=referentiel_get_item_configuration('selref', $this->referentiel->id);

        // debut
        if (($ok_selectionner_referentiel==0) || ($ok_creer_importer_referentiel==0)){
            echo '<h3 align="center">'.get_string('associer_referentiel','referentiel').'</h3>';
            if (($ok_selectionner_referentiel==0) && $ok_existe_au_moins_un_referentiel) {
                // formulaire de selection dans la liste des referentiels existants
                echo '<div align="center">
<form name="form" method="post" action="selection.php?id='.$this->cm->id.'">
<input type="hidden" name="name_instance" value="'. $this->referentiel->name.'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8').'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine.'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence.'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item.'" />

<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$this->referentiel->course.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="instance"      value="'.$this->referentiel->id.'" />
<input type="hidden" name="mode"          value="add" />
<input type="submit" value="'.get_string('selectreferentiel','referentiel').'" />
</form>
</div>'."\n";
            }
            if ($ok_creer_importer_referentiel==0) {
                echo '<div align="center">
<form name="form" method="post" action="import_instance.php?id='.$this->cm->id.'">
<input type="hidden" name="name_instance" value="'.$this->referentiel->name .'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8').'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine.'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence.'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item.'" />
<input type="hidden" name="intro" value="'.htmlentities($this->referentiel->intro, ENT_QUOTES, 'UTF-8').'" />
<input type="hidden" name="config" value="'.$this->referentiel->config.'" />
<input type="hidden" name="config_impression" value="'.$this->referentiel->config_impression.'" />

<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$this->referentiel->course.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="instance"      value="'.$this->referentiel->id.'" />
<input type="hidden" name="mode"          value="add" />
<input type="submit" value="'.get_string('importreferentiel','referentiel').'" />
</form>
</div>'."\n";
            }
            echo $OUTPUT->box_end();
            // creer un referentiel de toutes pieces
            if ($ok_creer_importer_referentiel==0) {
                echo '<div align="center">'."\n";
                // Editeur wysiwyg
                $this->edition_wysiwyg_referentiel();
                echo '</div>'."\n";
                // CREATION avec formualires
                $this->creation_referentiel();
            }
        }

    }

    /**
     * new referentiel_referentiel
     *
     *
     */
    function edition_wysiwyg_referentiel(){
    // developpement en cours
    global $OUTPUT, $CFG;

        if (referentiel_editor_is_ok()){
            // editeur de referentiel
            echo $OUTPUT->box_start('generalbox boxaligncenter', 'wysiwyg');
            echo '<br />
<h3 align="center">'.get_string('editer_referentiel_xml','referentiel').'</h3>'."\n";
            echo '<p align="center"> ';
            echo '<a href="'.$CFG->wwwroot.'/mod/referentiel/editor/editeur_referentiel.php?id='.$this->cm->id.'&amp;sesskey='.sesskey().'&amp;return_link=add.php" target="_blank">'.get_string('editeur_referentiel','referentiel').'</a></p>'."\n";
            echo '<h3 align="center">'.get_string('import_referentiel_xml','referentiel').'</h3>'."\n";
            echo '

<table class="saisie">
<tr valign="top"> <td align="center">
<form name="form" method="post"  action="editor/import_referentiel_xml.php?id='.$this->cm->id.'">

<!-- instance -->
<input type="hidden" name="instance" value="'.$this->referentiel->id.'" />
<input type="hidden" name="name_instance" value="'.$this->referentiel->name.'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8').'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine.'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence.'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item.'" />

<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.  $this->course->id  .'" />
<input type="hidden" name="sesskey"     value="'.  sesskey() .'" />

<input type="hidden" name="mode"          value="add" />
<input type="submit" value="'.get_string('import_referentiel_xml','referentiel').'" />
</form>
</td>
</tr>
</table>
';
            echo $OUTPUT->box_end();

        }
    }

    /**
     * new referentiel_referentiel
     *
     *
     */
    function creation_referentiel(){
    // boite de saisie de la creation d'un référentiel
        global $OUTPUT;
                $nb_domaines = '1';
                $num_domaine = '1';
	            $nb_competences = '1';
                $num_competence = '1';
                $nb_item_competences = '1';
                $poids_item = '1.0';
                $empreinte_item = '1';
                $type_domaine= '0';
                $seuil_domaine='0.0';
                $type_competence= '0';
                $seuil_competence='0.0';
                $type_item = '0';
                $num_item = '1';

                echo $OUTPUT->box_start('generalbox boxaligncenter', 'creer');
                echo '
<br />
<h3 align="center">'. get_string('creer_nouveau_referentiel','referentiel')  .'</h3>
<form name="form" method="post" action="add.php?id='.$this->cm->id.'&amp;sesskey='.sesskey() .'">
<table class="saisie">
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('name','referentiel')   .':</b></td>
    <td class="saisie" align="left">
        <input type="text" name="name" size="60" maxlength="80" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('code','referentiel')  .':</b></td>
    <td class="saisie" align="left">
        <input type="text" name="code_referentiel" size="20" maxlength="20" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('pass_referentiel','referentiel').'</b> : <br /><span class="small"><i>'.get_string('aide_pass_referentiel','referentiel').'</i></span>' .'</td>
    <td class="saisie" align="left">
        <input type="password" name="pass_referentiel" size="20" maxlength="20" value="" />

    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('description','referentiel')  .':</b></td>
    <td class="saisie" align="left">
		<textarea cols="60" rows="5" name="description_referentiel"></textarea>
    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('url','referentiel')  .':</b></td>
    <td class="saisie" align="left">
        <input type="text" name="url_referentiel" size="60" maxlength="255" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('seuil_certificat','referentiel')  .':</b></td>
    <td class="saisie" align="left">
        <input type="text" name="seuil_certificat" size="5" maxlength="10" value="0" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('referentiel_global','referentiel')  .':</b></td>
    <td class="saisie" align="left">
<input type="radio" name="local" value="0" checked="checked" />'. get_string("yes").'
<input type="radio" name="local" value="1" />'. get_string("no").'
</td>
</tr>
<tr valign="top">
    <td class="saisie" align="right"><b>'.get_string('nombre_domaines_supplementaires','referentiel')  .':</b></td>
    <td class="saisie" align="left">
        <input type="text" name="nb_domaines" size="2" maxlength="2" value="'.$nb_domaines.'" />
    </td>
</tr>
</table>



<!-- DOMAINE -->
<h3 align="center">'.get_string('creation_domaine','referentiel')  .'</h3>
<table class="saisie_domaine">
<tr valign="top">
    <td class="saisie_domaine" align="left" colspan="4"><i>'.get_string('domaine','referentiel')  .'</i></td>
</tr>

<tr valign="top">
    <td class="saisie_domaine" align="right"><b>'.get_string('code','referentiel')  .':</b></td>
    <td class="saisie_domaine" align="left" colspan="3">
        <input type="text" name="code_domaine" size="20" maxlength="20" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_domaine" align="right"><b>'.get_string('description','referentiel')  .':</b></td>
    <td class="saisie_domaine" align="left" colspan="3">
		<textarea cols="60" rows="5" name="description_domaine"></textarea>
    </td>
</tr>
<tr valign="top">
    <td class="saisie_domaine" align="right"><b>'.get_string('type_domaine','referentiel').':</b></td>
    <td class="saisie_domaine" align="left">
';
    // MODIF JF 2012/02/20
    if (!empty($type_domaine)){
        echo get_string('yes'). ' <input type="radio" name="type_domaine" id="type_domaine" value="1" checked="checked" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_domaine" id="type_domaine" value="0" />'."\n";
    }
    else{
        echo get_string('yes'). ' <input type="radio" name="type_domaine" id="type_domaine" value="1" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_domaine" id="type_domaine" value="0" checked="checked" />'."\n";
    }
    echo '
    </td>
    <td class="saisie_domaine" align="left"><b>'.get_string('seuil_domaine','referentiel').':</b> </td>
    <td class="saisie_domaine" align="left">
';
    // MODIF JF 2012/02/20
    echo '<input type="text" name="seuil_domaine" size="5" maxlength="10" value="'.s($seuil_domaine).'" />'."\n";
    echo '</td>
</tr>

<tr valign="top">
    <td class="saisie_domaine" align="right"><b>'.get_string('numero','referentiel')  .':</b></td>
    <td class="saisie_domaine" align="left" colspan="3">
        <input type="text" name="num_domaine" size="2" maxlength="2" value="'. $num_domaine  .'" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_domaine" align="right"><b><i>'.get_string('nombre_competences_supplementaires','referentiel')  .'</i></b>:</td>
    <td class="saisie_domaine" align="left" colspan="3">
        <input type="text" name="nb_competences" size="2" maxlength="2" value="'.   $nb_competences  .'" />
    </td>
</tr>

</table>
';


    echo ' <!-- COMPETENCE -->
<h3 align="center">'.get_string('creation_competence','referentiel')  .'</h3>
<table class="saisie_competence">

<tr valign="top">
    <td class="saisie_competence" align="left" colspan="4"><i>'.get_string('competence','referentiel')  .'</i></td>
</tr>
<tr valign="top">
    <td class="saisie_competence" align="right"><b>'.get_string('code','referentiel')  .':</b></td>
    <td class="saisie_competence" align="left" colspan="3">
        <input type="text" name="code_competence" size="20" maxlength="20" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_competence" align="right"><b>'.get_string('description','referentiel')  .':</b></td>
    <td class="saisie_competence" align="left" colspan="3">
		<textarea cols="60" rows="5" name="description_competence"></textarea>
    </td>
</tr>
';
    echo '<tr valign="top">
    <td class="saisie_competence" align="right"><b>'.get_string('type_competence','referentiel').':</b></td>
    <td class="saisie_competence" align="left">
';

    // MODIF JF 2012/02/20
    if (!empty($type_competence)){
        echo get_string('yes'). ' <input type="radio" name="type_competence" id="type_competence" value="1" checked="checked" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_competence" id="type_competence" value="0" />'."\n";
    }
    else {
        echo get_string('yes'). ' <input type="radio" name="type_competence" id="type_competence" value="1" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_competence" id="type_competence" value="0" checked="checked" />'."\n";
    }
    echo '</td>
    <td class="saisie_competence" align="left"><b>'.get_string('seuil_competence','referentiel').':</b> </td>
    <td class="saisie_competence" align="left">
';
    // MODIF JF 2012/02/20
    echo '<input type="text" name="seuil_competence" size="5" maxlength="10" value="'.s($seuil_competence).'" />'."\n";
    echo '
    </td>
</tr>

<tr valign="top">
    <td class="saisie_competence" align="right"><b>'.get_string('numero','referentiel')  .':</b></td>
    <td class="saisie_competence" align="left" colspan="3">
        <input type="text" name="num_competence" size="2" maxlength="2" value="'.  $num_competence  .'" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_competence" align="right"><b><i>'.get_string('nombre_item_competences_supplementaires','referentiel')  .'</i></b>:</td>
    <td class="saisie_competence" align="left" colspan="3">
        <input type="text" name="nb_item_competences" size="2" maxlength="2" value="'.  $nb_item_competences  .'" />
    </td>
</tr>

</table>

<!-- ITEM -->
<h3 align="center">'.get_string('creation_item','referentiel')  .'</h3>

<table class="saisie_item">

<tr valign="top">
    <td class="saisie_item" align="left" colspan="2"><i>'.get_string('item','referentiel')  .'</i></td>
</tr>

<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('code','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
        <input type="text" name="code_item" size="20" maxlength="20" value="" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('description','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
		<textarea cols="60" rows="5" name="description_item"></textarea>
    </td>
</tr>
<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('type_item','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
';
    // MODIF JF 2012/02/20
    if (!empty($type_item)){
        echo get_string('yes'). ' <input type="radio" name="type_item" id="type_item" value="1" checked="checked" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_item" id="type_item" value="0" />'."\n";
    }
    else{
        echo get_string('yes'). ' <input type="radio" name="type_item" id="type_item" value="1" />'."\n";
        echo get_string('no'). ' <input type="radio" name="type_item" id="type_item" value="0" checked="checked" />'."\n";
    }
    echo '</td>
</tr>
<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('poids_item','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
        <input type="text" name="poids_item" size="5" maxlength="10" value="'. $poids_item  .'" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('empreinte_item','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
        <input type="text" name="empreinte_item" size="3" maxlength="3" value="'.   $empreinte_item  .'" />
    </td>
</tr>
<tr valign="top">
    <td class="saisie_item" align="right"><b>'.get_string('numero','referentiel')  .':</b></td>
    <td class="saisie_item" align="left">
        <input type="text" name="num_item" size="2" maxlength="2" value="'.   $num_item  .'" />
    </td>
</tr>

</table>
<br />

<input type="hidden" name="action" value="modifierreferentiel" />
<input type="hidden" name="mail_auteur_referentiel" value="" />
<input type="hidden" name="old_pass_referentiel" value="" />
<input type="hidden" name="cle_referentiel" value="" />

<input type="hidden" name="liste_codes_competence" value="" />
<input type="hidden" name="liste_empreintes_competence" value="" />
<input type="hidden" name="liste_poids_competence" value="" />
<input type="hidden" name="logo_referentiel" value="" />

<!-- instance -->
<input type="hidden" name="instance" value="'.$this->referentiel->id.'" />
<input type="hidden" name="name_instance" value="'.$this->referentiel->name.'" />
<input type="hidden" name="description_instance" value="'.htmlentities($this->referentiel->description_instance, ENT_QUOTES, 'UTF-8').'" />
<input type="hidden" name="label_domaine" value="'.$this->referentiel->label_domaine.'" />
<input type="hidden" name="label_competence" value="'.$this->referentiel->label_competence.'" />
<input type="hidden" name="label_item" value="'.$this->referentiel->label_item.'" />

<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.  $this->course->id  .'" />
<input type="hidden" name="sesskey"     value="'.  sesskey() .'" />
<input type="hidden" name="mode"          value="add" />
<input type="submit" value="'.get_string("savechanges")  .'" />
<input type="submit" name="cancel" value="'.get_string("quit", "referentiel")  .'" />
</form>'."\n";
        echo $OUTPUT->box_end();
    }

     /**
     * Returns a link with info about the state of the referentiel submissions
     *
     * This is used by view_header to put this link at the top right of the page.
     * For teachers it gives the number of submitted activities declaration with a link
     * For students it gives the time of their declarations.
     *
     * @global object
     * @global object
     * @param bool $allgroup print all groups info if user can access all groups, suitable for index.php
     * @return string
     */
    function submittedlink($allgroups=false) {
        global $USER;
        global $CFG;

        $submitted = '';

       /*******************

        // A REPRENDRE
                        $urlbase = "{$CFG->wwwroot}/mod/referentiel/";

        ////if ($CFG->version < 2011120100) {
            $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        //} else {
        //    //  $this->context = context_module::instance($this->cm); 
        //}

        if (has_capability('mod/referentiel:grade', $this->context)) {
            if ($allgroups and has_capability('moodle/site:accessallgroups', $this->context)) {
                $group = 0;
            }
            else {
                $group = groups_get_activity_group($this->cm);
            }
            if ($count = $this->count_real_submissions($group)) {
                $submitted = '<a href="'.$urlbase.'submissions.php?id='.$this->cm->id.'">'.
                             get_string('viewsubmissions', 'referentiel', $count).'</a>';
            }
            else {
                $submitted = '<a href="'.$urlbase.'submissions.php?id='.$this->cm->id.'">'.
                             get_string('noattempts', 'referentiel').'</a>';
            }
        }
        else {
            if (isloggedin()) {
                if ($submission = $this->get_submission($USER->id)) {
                    if ($submission->timemodified) {
                        if ($submission->timemodified <= $this->referentiel->timedue || empty($this->referentiel->timedue)) {
                            $submitted = '<span class="early">'.userdate($submission->timemodified).'</span>';
                        } $
                        else {
                            $submitted = '<span class="late">'.userdate($submission->timemodified).'</span>';
                        }
                    }
                }
            }
        }
        **************/
        return $submitted;
    }



    /**
     * Create a new referentiel activity
     *
     * Given an object containing all the necessary data,
     * (defined by the form in mod_form.php) this function
     * will create a new instance and return the id number
     * of the new instance.
     * The due data is added to the calendar
     *
     * @global object
     * @global object
     * @param object $referentiel The data from the form on mod_form.php
     * @return int The id of the referentiel instance
     */
    function add_instance($referentiel) {
        global $COURSE, $DB;

        $referentiel->date_instance = time();
        $referentiel->id = $referentiel->instance;
        $referentiel->description_instance=$referentiel->intro;
        // $referentiel->introformat = 1;

        $returnid = $DB->insert_record("referentiel", $referentiel);
        $referentiel->id = $returnid;

        if ($referentiel->id) {
            $event = new stdClass();
            $event->name        = $referentiel->name;
            $event->description = $referentiel->description_instance; // format_module_intro('referentiel', $referentiel, $referentiel->coursemodule);
            $event->courseid    = $referentiel->course;
            $event->groupid     = 0;
            $event->userid      = 0;
            $event->modulename  = 'referentiel';
            $event->instance    = $returnid;
            $event->eventtype   = 'due';
            $event->timestart   = $referentiel->date_instance;
            $event->timeduration = 0;

            calendar_event::create($event);
        }
        // pas de notation de cette activite
        // referentiel_grade_item_update($referentiel);

        return $returnid;
    }

    /**
     * Deletes an referentiel activity
     *
     * Deletes all database records, files and calendar events for this referentiel.
     *
     * @global object
     * @global object
     * @param object $referentiel The referentiel to be deleted
     * @return boolean False indicates error
     */
    function delete_instance($referentiel) {
        global $CFG, $DB;

        $result = true;

        // now get rid of all files
        $fs = get_file_storage();
        if ($cm = get_coursemodule_from_instance('referentiel', $referentiel->id)) {
            $context = get_context_instance(CONTEXT_MODULE, $cm->id);
            $fs->delete_area_files($context->id);
        }

        // suppression des activites associees
        $activites=referentiel_get_activites_instance($referentiel->id);
        if ($activites){
            foreach ($activites as $activite){
                referentiel_delete_activity_record($activite->id);
            }
        }
        // suppression des taches associees
        $taches=referentiel_get_tasks_instance($referentiel->id);
        if ($taches){
            foreach ($taches as $tache){
                referentiel_delete_task_record($tache->id);
            }
        }

        // suppression des accompagnements
        $accompagnements=referentiel_get_accompagnements($referentiel->id);
        if ($accompagnements){
            foreach ($accompagnements as $accompagnement){
                referentiel_delete_accompagnement_record($accompagnement->id);
            }
        }

        // suppression des certificats associes
        // il serait préférable de ne pas les supprimer
        // mais plutôt de les recalculer

        $certificats=referentiel_get_certificats($referentiel->ref_referentiel);
        if ($certificats){
            foreach ($certificats as $certificat){
                referentiel_recalcule_certificat($certificat);
            }
        }


        // suppression des evenements du calendrier
        if (! $DB->delete_records('event', array('modulename'=>'referentiel', 'instance'=>$referentiel->id))) {
            $result = false;
        }

        if (! $DB->delete_records('referentiel', array('id'=>$referentiel->id))) {
            $result = false;
        }

        // $mod = $DB->get_field('modules','id',array('name'=>'referentiel'));

        // referentiel_grade_item_delete($referentiel);

        return $result;
    }

    /**
     * Updates a new referentiel activity
     *
     * Given an object containing all the necessary data,
     * (defined by the form in mod_form.php) this function
     * will update the referentiel instance and return the id number
     * The due date is updated in the calendar
     *
     * @global object
     * @global object
     * @param object $referentiel The data from the form on mod_form.php
     * @return bool success
     */
    function update_instance($referentiel) {
        global $COURSE, $DB;

        $referentiel->date_instance = time();
        $referentiel->id = $referentiel->instance;
        // $referentiel->intro = $referentiel->description_instance;
        $referentiel->description_instance=$referentiel->intro;
        // $referentiel->introformat = 1;

        $DB->update_record('referentiel', $referentiel);

        if ($referentiel->date_instance) {
            $event = new stdClass();

            if ($event->id = $DB->get_field('event', 'id', array('modulename'=>'referentiel', 'instance'=>$referentiel->id))) {

                $event->name        = $referentiel->name;
                $event->description = $referentiel->description_instance; // format_module_intro('referentiel', $referentiel, $referentiel->coursemodule);
                $event->timestart   = $referentiel->date_instance;

                $calendarevent = calendar_event::load($event->id);
                $calendarevent->update($event);
            }
            else {
                $event = new stdClass();
                $event->name        = $referentiel->name;
                $event->description = $referentiel->description_instance; // format_module_intro('referentiel', $referentiel, $referentiel->coursemodule);
                $event->courseid    = $referentiel->course;
                $event->groupid     = 0;
                $event->userid      = 0;
                $event->modulename  = 'referentiel';
                $event->instance    = $referentiel->id;
                $event->eventtype   = 'due';
                $event->timestart   = $referentiel->date_instance;
                $event->timeduration = 0;

                calendar_event::create($event);
            }
        }
        else {
            $DB->delete_records('event', array('modulename'=>'referentiel', 'instance'=>$referentiel->id));
        }

        // get existing grade item
        // referentiel_grade_item_update($referentiel);

        return true;
    }

    /**
     * @todo Document this function
     * a partir d'une chaine de configuration affiche les boites de saisie
     *
     */
     // -----------------------------
    function selection_configuration(&$mform, $str_config, $type){
    // item = 'scol', 'creref', 'selref', 'impcert', refcert, instcert, numetu, nometu, etabetu, ddnetu, lieuetu, adretu, pourcent, compdec, compval, referent, jurycert, comcert,
    // 'scol:0;creref:0;selref:0;impcert:0;graph:0;refcert:1;instcert:0;numetu:1;nometu:1;etabetu:0;ddnetu:0;lieuetu:0;adretu:0;detail:1;pourcent:0;compdec:0;compval:1;nomreferent:0;jurycert:1;comcert:0;'
    // retourne une liste de selecteurs
    // $type : config ou config_impression
    global $CFG;

	   if ($str_config==''){
            $str_config=referentiel_creer_configuration($type);
	   }
	   // DEBUG
	   // echo "<br />DEBUG :: lib.php :: 754 ::  $str_config\n";
	   if ($str_config!=''){
	    $mform->addElement('header', 'configuration', get_string('configuration','referentiel'));
        $mform->addElement('html', get_string('aide_referentiel_config_local','referentiel'));

		$tconfig=explode(';',$str_config);
		$n=count($tconfig);
		if ($n>0){
			$i=0;
			while ($i<$n){
				$tconfig[$i]=trim($tconfig[$i]);
				if ($tconfig[$i]!=''){
					list($cle, $val)=explode(':',$tconfig[$i]);
					$cle=trim($cle);
					$val=trim($val);
					if ($cle!=''){

						$str_conf=referentiel_associe_item_configuration($cle);
						// creer le parametre si necessaire
						if (!isset($CFG->$str_conf)){
							$CFG->$str_conf=0;
						}
						if ($CFG->$str_conf==2){
							// $s.= '<input type="hidden" name="'.$cle.'" value="2" /> <b>'.get_string('config_verrouillee','referentiel').'</b>'."\n";
                            $mform->addElement('hidden', $cle, 2);
                            $mform->setType($cle, PARAM_NUM);
                            $mform->setDefault($cle, 2);
 						}
						else{
                            $radioarray=array();
                            $radioarray[] = & $mform->createElement('radio', $cle, '', get_string('no'), 0, $cle);
                            $radioarray[] = & $mform->createElement('radio', $cle, '', get_string('yes'), 1, $cle);
                            if ($val==1){
                                $mform->setDefault($cle, 1);
     						}
						    else {
                                $mform->setDefault($cle, 0);
                            }
                            $mform->addGroup($radioarray, 'radioar'.$cle, get_string($cle, 'referentiel'), array(' '), false);
						}
					}
				}
				$i++;
			}
		}
	    }
    }

    /**
     * @todo Document this function
     */
    function setup_elements(&$mform, $referentielinstance) {
        global $CFG, $COURSE;

        if (empty($referentielinstance->id)){
            // $this->selection_configuration($mform, $referentielinstance->config, 'config');
        }
        else if (!empty($referentielinstance->id) && referentiel_site_can_config_referentiel($referentielinstance->id)){
            $this->selection_configuration($mform, $referentielinstance->config, 'config');
        }
        else{
            // niveau supérieur de configuration
            // echo '<i>'.get_string('referentiel_config_local_interdite','referentiel').'</i>'."\n";
            // echo '<br /><br />'.referentiel_affiche_config($referentielinstance->config_globale, 'config');
            // echo '<input type="hidden" name="config" value="'.$mform->config.'" />'."\n";
            $mform->addElement('hidden', 'config', $referentielinstance->config_globale);
            $mform->setType('config', PARAM_TEXT);
            $mform->setDefault('config', $referentielinstance->config_globale);
        }



        ////if ($CFG->version < 2011120100) {
            $course_context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
        //} else {
            //$course_context = context_course::instance($COURSE->id);
        //}

        // plagiarism_get_form_elements_module($mform, $course_context);
    }


    /**
     * Any preprocessing needed for the settings form for
     * this referentiel type
     *
     * @param array $default_values - array to fill in with the default values
     *      in the form 'formelement' => 'value'
     * @param object $form - the form that is to be displayed
     * @return none
     */
    function form_data_preprocessing(&$default_values, $form) {
    }

    /**
     * Any extra validation checks needed for the settings
     * form for this referentiel type
     *
     * See lib/formslib.php, 'validation' function for details
     */
    function form_validation($data, $files) {
        return array();
    }


}
// Fin de la classe
// ======================================================================



?>
