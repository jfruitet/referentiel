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
 * Library of functions for forum outside of the core api
 */

require_once($CFG->dirroot . '/mod/referentiel/lib.php');
require_once($CFG->dirroot . '/mod/referentiel/locallib.php');
require_once($CFG->dirroot . '/mod/referentiel/lib_certificat.php');	// AFFICHAGES
require_once($CFG->libdir . '/portfolio/caller.php');
require_once($CFG->libdir . '/filelib.php');

// Artefact MAHARA_REFERENTIEL installé sur Mahara ?
// Au 1/12/2011 cet artefact n'est pas disponible et donc inutile d'essayer de l'utiliser !
define ('MAHARA_ARTEFACT_REFERENTIEL', 0);   // placer à 1 pour activer le traitement
// define ('MAHARA_ARTEFACT_REFERENTIEL', 1);   // placer à 0 pour désactiver le traitement


/**
 * @package   mod-referentiel
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @copyright 2011 Jean Fruitet  {@link http://univ-nantes.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class referentiel_portfolio_caller extends portfolio_module_caller_base {

    protected $instanceid;
    protected $attachment;
    protected $certificatid;
    protected $report;
    protected $export_format;

    protected $cm;
    protected $course;
    protected $referentiel;
    protected $occurrence;
    protected $certificat;
    
    private $adresse_retour;
    
    /**
     * @return array
     */
    public static function expected_callbackargs() {
        return array(
            'instanceid' => false,
            'attachment'   => false,
            'certificatid'   => false,
            'report'  => false,
            'export_format'  => false,
        );
    }
    /**
     * @param array $callbackargs
     */
    function __construct($callbackargs) {
        parent::__construct($callbackargs);
        if (!$this->instanceid) {
            throw new portfolio_caller_exception('mustprovideinstance', 'referentiel');
        }
        if (!$this->attachment && !$this->certificatid) {
            throw new portfolio_caller_exception('mustprovideattachmentorcertificat', 'referentiel');
        }

        if (!isset($this->report)) {
            throw new portfolio_caller_exception('mustprovidereporttype', 'referentiel');
        }

        if (!isset($this->export_format)) {
            throw new portfolio_caller_exception('mustprovideexportformat', 'referentiel');
        }
        else{
            // echo "<br />:: 86 ::$this->export_format\n";
            if ($this->export_format!=PORTFOLIO_FORMAT_FILE) {
                // depending on whether there are files or not, we might have to change richhtml/plainhtml
                $this->supportedformats = array_merge(array($this->supportedformats), array(PORTFOLIO_FORMAT_RICH, PORTFOLIO_FORMAT_LEAP2A));
            }
        }
    }
    /**
     * @global object
     */
    public function load_data() {
        global $DB;
        global $CFG;
        if ($this->instanceid) {
            if (!$this->referentiel = $DB->get_record('referentiel', array('id' => $this->instanceid))) {
                throw new portfolio_caller_exception('invalidinstanceid', 'referentiel');
            }
            if (!$this->occurrence = $DB->get_record('referentiel_referentiel', array('id' => $this->referentiel->ref_referentiel))) {
                throw new portfolio_caller_exception('invalidoccurrence', 'referentiel');
            }
        }
        
        if (!$this->cm = get_coursemodule_from_instance('referentiel', $this->referentiel->id)) {
            throw new portfolio_caller_exception('invalidcoursemodule');
        }

        if ($this->certificatid) {
            if (!$this->certificat = $DB->get_record('referentiel_certificat', array('id' => $this->certificatid))) {
                throw new portfolio_caller_exception('invalidcertificat', 'referentiel');
            }
        }

        if (!$this->report){
            $this->adresse_retour= '/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;select_acc=0&amp;mode=listcertif';
        }
        else{
            $this->adresse_retour= '/admin/report/referentiel/archive.php?i='.$this->referentiel->id;
        }

        if ($this->attachment){
            $this->set_file_and_format_data($this->attachment); // sets $this->singlefile for us
            // depending on whether there are files or not, we might have to change richhtml/plainhtml
            if ($this->export_format==PORTFOLIO_FORMAT_FILE){
                $this->add_format(PORTFOLIO_FORMAT_FILE);
            }
        }

    }

    /**
     * @global object
     * @return string
     */
    function get_return_url() {
        global $CFG;
        return $CFG->wwwroot . $this->adresse_retour;
    }
    /**
     * @global object
     * @return array
     */
    function get_navigation() {
        global $CFG;

        $navlinks = array();
        $navlinks[] = array(
            'name' => format_string($this->referentiel->name),
            // 'link' => $CFG->wwwroot . '/mod/referentiel/certificat.php?d='.$this->referentiel->id.'&amp;select_acc=0&amp;mode=listcertif',
            'link' => $CFG->wwwroot . $this->adresse_retour,
            'type' => 'title'
        );
        return array($navlinks, $this->cm);
    }
    /**
     * either a whole discussion
     * a single post, with or without attachment
     * or just an attachment with no post
     *
     * @global object
     * @global object
     * @uses PORTFOLIO_FORMAT_HTML
     * @return mixed
     */
    function prepare_package() {
        global $CFG;
        global $OUTPUT;
        
        if ($this->attachment){
            // DEBUG
            /*
            echo "<br />".$this->exporter->get('formatclass')." == ".PORTFOLIO_FORMAT_FILE;
            echo "<br />\n";
            print_object($this->exporter) ;
            */
            if ($this->exporter->get('formatclass') == PORTFOLIO_FORMAT_FILE){
                return $this->get('exporter')->copy_existing_file($this->singlefile);
            }
        }
        else if ($this->certificat && $this->occurrence) {
            if (MAHARA_ARTEFACT_REFERENTIEL){
                redirect(new moodle_url('/mod/referentiel/portfolio/set_mahara_referentiel.php', array('id'=>$this->cm->id, 'certificatid' => $this->certificat->id )));
                die();
            }
            else {
                // exporting a single HTML certificat
                $content_to_export = $this->prepare_certificat($this->certificat);
                $name = 'certificat'.'_'.$this->occurrence->code_referentiel.'_'.$this->certificat->userid.'.html';
                // $manifest = ($this->exporter->get('format') instanceof PORTFOLIO_FORMAT_PLAINHTML);
                $manifest = ($this->exporter->get('format') instanceof PORTFOLIO_FORMAT_RICH);

                // DEBUG
                /*
                echo "<br />DEBUG :: 179 :: CONTENT<br />\n";
                echo($content_to_export);
                echo "<br />MANIFEST : $manifest<br />\n";
                echo "<br />FORMAT ".$this->exporter->get('formatclass')."\n";
                */
                
                $content=$content_to_export;

                if ($this->exporter->get('formatclass') == PORTFOLIO_FORMAT_LEAP2A) {
                    $leapwriter = $this->exporter->get('format')->leap2a_writer();
                    // DEBUG
                    //echo "<br />DEBUG :: 169 :: LEAPWRITER<br />\n";
                    //print_object($leapwriter);
                    // exit;
                    if ($leapwriter){
                        // echo "<br />DEBUG :: 190\n";
                        if ($this->prepare_certificat_leap2a($leapwriter, $content_to_export)){
                            // echo "<br />DEBUG :: 175\n";
                            $content = $leapwriter->to_xml();
                            // DEBUG
                            /*
                            echo "<br />DEBUG :: mod/referentiel/locallib_portfolio.php :: 195<br />\n";
                            print_object($content);
                            */
                            $name = $this->exporter->get('format')->manifest_name();
                            //exit;
                        }
                    }
                }
                /*
                // DEBUG
                echo "<br />DEBUG :: 176<br />\n";
                print_object($content);
                */
                $this->get('exporter')->write_new_file($content, $name, $manifest);
            }
        }
    }
    

    /**
     * @return string
     */
    function get_sha1() {
        $filesha = '';
        try {
            $filesha = $this->get_sha1_file();
        } catch (portfolio_caller_exception $e) { } // no files

        if ($this->referentiel){
            if ($this->attachment) {
                return sha1($filesha . ',' . $this->occurrence->code_referentiel. ',' . $this->referentiel->ref_referentiel);
            }
            else if ($this->certificat) {
                return sha1($filesha . ',' . $this->occurrence->code_referentiel. ',' . $this->referentiel->ref_referentiel. ',' . $this->certificat->userid);
            }
        }
    }

    function expected_time() {
        // a file based export
        if ($this->singlefile) {
            return portfolio_expected_time_file($this->singlefile);
        }
        else{
            //return portfolio_expected_time_db(count($this->certificat));
            return PORTFOLIO_TIME_LOW;
        }
    }

    /**
     * @uses CONTEXT_MODULE
     * @return bool
     */
    function check_permissions() {
    global $CFG;
        // $context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        // Valable pour Moodle 2.1 et Moodle 2.2
        //if ($CFG->version < 2011120100) {
            $context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        //} else {
        //    $context = context_module::instance($this->cm);
        //}

        if ($this->attachment){
            return (has_capability('mod/referentiel:archive', $context));
        }
        else{
            return true;
        }
    }
    
    /**
     * @return string
     */
    public static function display_name() {
        return get_string('modulename', 'referentiel');
    }

    public static function base_supported_formats() {
        //return array(PORTFOLIO_FORMAT_FILE, PORTFOLIO_FORMAT_PLAINHTML, PORTFOLIO_FORMAT_LEAP2A);
        return array(PORTFOLIO_FORMAT_FILE);
    }
    
    /**
     * helper function to add a leap2a entry element
     * that corresponds to a single certificate,
     *
     * the entry/ies are added directly to the leapwriter, which is passed by ref
     *
     * @global object $certificate the stdclass object representing the database record
     * @param portfolio_format_leap2a_writer $leapwriter writer object to add entries to
     * @param string $content  the content of the certificate (prepared by {@link prepare_certificate}
     *
     * @return int id of new entry
     */
    private function prepare_certificat_leap2a(portfolio_format_leap2a_writer $leapwriter, $content) {
    global $USER;
        $order   = array( "&nbsp;",  "\r\n", "\n", "\r");
        $replace = ' ';
        $content=str_replace($order, $replace, $content);
        $title=get_string('certificat', 'referentiel').' '.$this->occurrence->code_referentiel;
        $entry = new portfolio_format_leap2a_entry('certificat' . $this->certificat->id, $title, 'leap2', $content); // proposer ability ?
        $entry->published = time();
        $entry->updated = time();
        $entry->author=new StdClass();
        $entry->author->id = $this->certificat->userid;
        $entry->summary = '<p><h3>'.get_string('certificat', 'referentiel').' '.get_string('referentiel', 'referentiel').$this->occurrence->code_referentiel.'</h3>'."\n".'<p>'.$this->occurrence->description_referentiel.'</p>';
        $entry->add_category('web', 'any_type', 'Referentiel');
        // DEBUG
        // echo "<br />266 :: ENTRY<br />\n";
        // print_object($entry);
        $leapwriter->add_entry($entry);
        // echo "<br />272 :: LEAPWRITER<br />\n";
        // print_object($leapwriter);

        return $entry->id;
    }

    /**
     * this is a very cut down version of what is in referentiel_certificat print_lib
     *
     * @global object
     * @return string
     */
    private function prepare_certificat() {
        global $DB;
        $output='';
        if (!empty($this->certificat)) {
            $fullname = '';
            $fullnameteacher = get_string('l_inconnu', 'referentiel');

            if(!empty($this->certificat->userid)){
                $user= $DB->get_record('user', array('id' => $this->certificat->userid));
                if ($user){
                    $fullname = fullname($user, true);
                    $login=$user->username;
                }
            }

            if (!empty($this->certificat->teacherid)){
                $teacher= $DB->get_record('user', array('id' => $this->certificat->teacherid));
                if ($teacher){
                    $fullnameteacher =fullname($teacher, true);
                }
            }

            $by = new stdClass();
            $by->name = $fullnameteacher;
            $by->date = date("Y-m-d H:i:s");

            $liste_empreintes=referentiel_purge_dernier_separateur(referentiel_get_liste_empreintes_competence($this->certificat->ref_referentiel), '/');
		    $liste_description_competences_poids=referentiel_purge_dernier_separateur(referentiel_get_liste_poids($this->certificat->ref_referentiel), '|');
            // $liste_competences=referentiel_affiche_competences_certificat('/',':',$this->certificat->competences_certificat, $liste_empreintes);
            $liste_competences=$this->affiche_competences_validees('/',':',$this->certificat->competences_certificat, $liste_empreintes, $liste_description_competences_poids);

            // format the body
            $s='<h3>'.get_string('certification','referentiel').' ';
            if (!empty( $this->occurrence->url_referentiel)){
                $s.=get_string('referentiel', 'referentiel').' <a href="'.$this->occurrence->url_referentiel.'" target="_blank">'.$this->occurrence->code_referentiel.'</a></h3>'."\n";
            }
            else{
                $s.=get_string('referentiel', 'referentiel').' '. $this->occurrence->code_referentiel.'</h3>'."\n";
            }
            
            $s.='<p><b>'.get_string('name','referentiel').':</b> '.$fullname.' (<i>'.$login.'</i>)<br />'
                    .'<b>'.get_string('userid', 'referentiel').'</b>: #'.$this->certificat->userid.'<br />'
                    .'<b>'.get_string('id', 'referentiel').get_string('certificat', 'referentiel').'</b>: #'.$this->certificat->id.'<br />'
                    .'<b>'.get_string('competences_certificat', 'referentiel').':</b><br />'.$liste_competences.'<br />'."\n";
//                    .'<b>'.get_string('competences_declarees', 'referentiel', $fullname).':</b><br />'.$this->certificat->competences_activite.'<br />'
            if (!empty($this->certificat->verrou)){
                $s.='<i>'.get_string('certificat', 'referentiel').' '.get_string('verrou', 'referentiel').'</i><br />'."\n";
            }

            if (!empty($this->certificat->synthese_certificat)){
                $s.= '<b>'.get_string('synthese_certificat', 'referentiel').':</b> '.$this->certificat->synthese_certificat.'<br />'."\n";
            }
            if (empty($this->certificat->decision_jury)){
                $s.= '<b>'.get_string('decisionnotfound', 'referentiel', date("Y-m-d")).'</b><br />';
            }
            else{
                $s.= '<b>'.get_string('decision_jury', 'referentiel').':</b> '.$this->certificat->decision_jury.'<br />';
            }
            if (!empty($this->certificat->teacherid)){
                $s.= '<b>'.get_string('referent', 'referentiel').':</b> '.referentiel_get_user_info($this->certificat->teacherid).'<br />';
            }
            if (!empty($this->certificat->date_decision)){
                $s.= '<b>'.get_string('date_decision', 'referentiel').':</b> '.userdate($this->certificat->date_decision).'<br />';
            }
            if (!empty($this->certificat->commentaire_certificat)){
                $s.='<b>'.get_string('commentaire_certificat', 'referentiel').': </b>'.$this->certificat->commentaire_certificat.'</p>'."\n";
            }
            $s.='</p>'."\n";
            
            // echo $s;
            // exit;
            
            $options = portfolio_format_text_options();
            $format = $this->get('exporter')->get('format');
            $formattedtext = format_text($s, FORMAT_HTML, $options);
        
            // $formattedtext = portfolio_rewrite_pluginfile_urls($formattedtext, $this->context->id, 'mod_referentiel', 'certificat', $certificat->id, $format);

            $output = '<table border="0" cellpadding="3" cellspacing="1" bgcolor="#333300">';
            $output .= '<tr valign="top" bgcolor="#ffffff"><td>';
            $output .= '<div><b>'.get_string('certificat', 'referentiel').' '. format_string($this->occurrence->code_referentiel).'</b></div>';
            if (!empty($this->certificat->decision_jury)){
                $output .= '<div>'.get_string('proposedbynameondate', 'referentiel', $by).'</div>';
            }
            else{
                $output .= '<div>'.get_string('evaluatedbynameondate', 'referentiel', $by).'</div>';
            }
            $output .= '</td></tr>';
            $output .= '<tr valign="top" bgcolor="#ffffff"><td align="left">';
            $output .= $formattedtext;
            $output .= '</td></tr></table>'."\n\n";

        }
        return $output;
    }

    // ----------------------------------------------------
    function affiche_competences_validees($separateur1, $separateur2, $liste, $liste_empreintes, $liste_poids){

    $t_empreinte=explode($separateur1, $liste_empreintes);
	$t_poids=explode('|', $liste_poids);

    // DEBUG
    /*
    print_r($t_empreinte);
    echo "<br />\n";
    print_r($t_poids);
    echo "<br />\n";
    //exit;
    */
    $s='';
	$tc=array();
	$liste=referentiel_purge_dernier_separateur($liste, $separateur1);
		if (!empty($liste) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste);
			$i=0;
			while ($i<count($tc)){
				if ($tc[$i]!=''){
					$tcc=explode($separateur2, $tc[$i]);

					if (isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
						$s.='<b>'.$tcc[0].'</b> '."\n";
						// $s.='<td colspan="3">'.str_replace('#','</td><td><i>',$t_poids[$i]).'</i></td>'."\n";
         				$s.=' '.str_replace('#','<!--',$t_poids[$i]).'-->'."\n";
					    // $s.='<td>'.$t_empreinte[$i].'</td>'."\n";
					    $s.='<br />'."\n";
					}
					/*
                    else{
						$s.='<td> <span class="invalide"><i>'.$tcc[0].'</i></span></td>'."\n";
						//$s.='<td>'.referentiel_jauge_activite($tcc[1], $t_empreinte[$i]).'</td>'."\n";
						$s.='<td colspan="3">'.str_replace('#','</td><td><i>',$t_poids[$i]).'</i></td>'."\n";
					}
					*/
				}
				$i++;
			}
		}
	return $s;
    }



}
