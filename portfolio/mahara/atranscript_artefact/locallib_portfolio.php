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
 * Library of functions for mahara atranscript artefact
 */


require_once($CFG->dirroot . '/mod/referentiel/lib.php');
require_once($CFG->dirroot . '/mod/referentiel/locallib.php');
require_once($CFG->libdir . '/portfolio/caller.php');
require_once($CFG->libdir . '/filelib.php');



/**
 * @package   mod-referentiel
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @copyright 2011 Jean Fruitet  {@link http://univ-nantes.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class atranscript_portfolio_caller extends portfolio_module_caller_base {

    protected $instance;
    protected $occurrence;
    protected $cm;
    protected $course;        
    protected $context;
    
    protected $certificat;    

    protected $name; // Occurrence name
    protected $code; // Occurrence code
    protected $description;  // Ocurrence description
    protected $items;   // Occurrence Competencies list
    protected $etudiant;  // Student scolarship info
    protected $referent;  // User teacher/marker
    protected $timestamp;  // Time stamp of evaluation

    protected $export_format;

    protected $userid;   // of export  call
    protected $certificatid;   // of export  call

    private $adresse_retour;
    
    public static function expected_callbackargs() {
        return array(
            'instanceid' => false,
            'userid'   => false,
            'certificatid'   => false,
            'export_format'  => false,
        );
    }
    /**
     * @param array $callbackargs
     */
    function __construct($callbackargs) {
        parent::__construct($callbackargs);
        if (!$this->instanceid) {
            throw new portfolio_caller_exception('mustprovideinstanceid', 'checklist');
        }
        if (!$this->userid) {
            throw new portfolio_caller_exception('mustprovideuser', 'checklist');
        }
        if (!$this->certificatid) {
            throw new portfolio_caller_exception('mustprovideceretificate', 'checklist');
        }
        if (!isset($this->export_format)) {
            throw new portfolio_caller_exception('mustprovideexportformat', 'checklist');
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
            if (!$this->instance = $DB->get_record('referentiel', array('id' => $this->instanceid))) {
                throw new portfolio_caller_exception('invalidinstanceid', 'referentiel');
            }
            if ($this->instance->ref_referentiel) {
                if (!$this->occurrence = $DB->get_record('referentiel_referentiel', array('id' => $this->instance->ref_referentiel))) {
                    throw new portfolio_caller_exception('invalidoccurrenceid', 'referentiel');
                }                
            }           
        }
        
        if ($this->userid) {
            if (!$this->user = $DB->get_record('user', array('id' => $this->userid))) {
                throw new portfolio_caller_exception('invaliduserid', 'referentiel');
            }
        }

        if ($this->certificatid) {
            if (!$this->certificat = $DB->get_record('referentiel_certificat', array('id' => $this->certificatid))) {
                throw new portfolio_caller_exception('invalidcertificatid', 'referentiel');
            }
        }
        
        if (!$this->cm = get_coursemodule_from_instance('referentiel', $this->instance->id)) {
            throw new portfolio_caller_exception('invalidcoursemodule');
        }
        $this->adresse_retour= '/mod/referentiel/certificat.php?id='.$this->cm->id;
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
            'name' => format_string($this->occurrence->name),
            'link' => $CFG->wwwroot . $this->adresse_retour,
            'type' => 'title'
        );
        return array($navlinks, $this->cm);
    }
    
    /**
     * certificate to atranscript
     *
     * @global object
     * @global object
     * @uses PORTFOLIO_FORMAT_HTML
     * @return mixed
     */
    function prepare_package() {
        global $CFG;
        global $OUTPUT;
        global $USER;
                // exporting a atranscript object
                $content_to_export = htmlspecialchars($this->prepare_certificat_atranscript());
                $name = 'certificat'.'_'.$this->occurrence->name.'_'.$this->userid.'.html';
                // $manifest = ($this->exporter->get('format') instanceof PORTFOLIO_FORMAT_LEAP2A);
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
                    $leapwriter = $this->exporter->get('format')->leap2a_writer($USER);
                    // DEBUG
                    /*
                    echo "<br />DEBUG :: /mod/referentiel/portfolio/mahara/atranscript_artefact/locallib_portfolio.php :: 189 :: LEAPWRITER<br />\n";
                    print_object($leapwriter);
                    echo "<br />\n";
                    //exit;
                    */
                    if ($leapwriter){
                        if ($this->prepare_certificat_leap2a($leapwriter, $content_to_export)){
                            // echo "<br />DEBUG :: 175\n";
                            $content = $leapwriter->to_xml();
                            // DEBUG
                            /*
                            echo "<br /><br />DEBUG :: /mod/referentiel/portfolio/mahara/atranscript_artefact/locallib_portfolio.php :: 197<br />\n";
                            echo htmlspecialchars($content);
                            */
                            $name = $this->exporter->get('format')->manifest_name();
                        }
                    }
                }
                
                // DEBUG
                /*
                echo "<br />DEBUG :: /mod/referentiel/portfolio/mahara/atranscript_artefact/locallib_portfolio.php :: 207 :: CONTENT<br />\n";
                // print_object($content);
                echo htmlspecialchars($content);
                echo "<br />EXIT\n";
                // exit;
                */
                
                $this->get('exporter')->write_new_file($content, $name, $manifest);

    }

    

    /**
     * @return string
     */
    function get_sha1() {
        $filesha = '';
        try {
            $filesha = $this->get_sha1_file();
        } catch (portfolio_caller_exception $e) { } // no files

        if ($this->occurrence->id && $this->occurrence->name && $this->userid){
            return sha1($filesha . ',' . $this->occurrence->id. ',' . $this->occurrence->name. ',' . $this->userid);
        }
        return 0;
    }

    function expected_time() {
        // a file based export
        if ($this->singlefile) {
            return portfolio_expected_time_file($this->singlefile);
        }
        else{
            return PORTFOLIO_TIME_LOW;
        }
    }

    /**
     * @uses CONTEXT_MODULE
     * @return bool
     */
    function check_permissions() {
        $context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
        return true;
    }
    
    /**
     * @return string
     */
    public static function display_name() {
        return get_string('modulename', 'referentiel');
    }

    public static function base_supported_formats() {
        //return array(PORTFOLIO_FORMAT_FILE, PORTFOLIO_FORMAT_PLAINHTML, PORTFOLIO_FORMAT_LEAP2A);
        //return array(PORTFOLIO_FORMAT_FILE);
        return array(PORTFOLIO_FORMAT_LEAP2A);
    }
    
    /**
     * helper function to add a leap2a entry element
     * that corresponds to a single certificate,
     *
     * the entry/ies are added directly to the leapwriter, which is passed by ref
     *
     * @global object $checklist $userid the stdclass object representing the database record
     * @param portfolio_format_leap2a_writer $leapwriter writer object to add entries to
     * @param string $content  the content of the certificate (prepared by {@link prepare_checklist}
     *
     * @return int id of new entry
     */
    private function prepare_certificat_leap2a(portfolio_format_leap2a_writer $leapwriter, $content) {
    global $USER;
        $order   = array( "&nbsp;",  "\r\n", "\n", "\r");
        $replace = ' ';
        $content=str_replace($order, $replace, $content);

        $title=get_string('certificat', 'referentiel').' '.$this->occurrence->name;
        $entry = new portfolio_format_leap2a_entry('atranscript_id' . $this->occurrence->id .'_user'. $this->userid, $title, 'leap2', $content); // proposer ability ?
        $entry->published = time();
        $entry->updated = time();
        $entry->author->id = $this->userid;
        $entry->summary = $this->occurrence->name.' '.strip_tags($this->occurrence->description_referentiel);
        $entry->add_category('life_area', 'atranscript', 'diploma'); // http://wiki.cetis.ac.uk/2009-03/LEAP2A_categories
        
        // DEBUG
        // echo "<br />246 :: ENTRY<br />\n";
        // print_object($entry);
        
        $leapwriter->add_entry($entry);
        /*
        echo "<br />293 :: LEAPWRITER ENTRY<br />\n";
        print_object($entry);
        echo "<br />Exit\n";
        exit;
        */
        return $entry->id;
    }

        /**
     * function to add an atranscript artefact
     * that corresponds to a single certificate,
     * this is a very cut down version of what is in referentiel_certificat print_lib
     *
     * @global object $certificate the stdclass object representing the database record

     * @return object
     */
    private function prepare_certificat_atranscript() {
        global $DB;
        $num_etudiant=get_string('l_inconnu', 'referentiel');
        $code_etablissement=get_string('l_inconnu', 'referentiel');
        
        if (!empty($this->certificat)) {
            $fullname = '';
            $fullnameteacher = get_string('l_inconnu', 'referentiel');

            if(!empty($this->certificat->userid)){
                $user= $DB->get_record('user', array('id' => $this->certificat->userid));
                if ($user){
                    $fullname = fullname($user, true);
                    $login=$user->username;
                    $num_etudiant = $login;
                }
                
               if ($etudiant= $DB->get_record('referentiel_etudiant', array('userid' => $this->certificat->userid))){
                    if (!empty($etudiant->num_etudiant)){
                        $num_etudiant   = $etudiant->num_etudiant;
                    }
                    if (!empty($etudiant->ref_etablissement)){
                        if ($etablissement= $DB->get_record('referentiel_etablissement', array('id' => $etudiant->ref_etablissement))){
                            if (!empty($etablissement->num_etablissement)){
                                $code_etablissement=$etablissement->num_etablissement;
                            }
                        }
                    }

                }

            }

            if (!empty($this->certificat->teacherid)){
                $teacher= $DB->get_record('user', array('id' => $this->certificat->teacherid));
                if ($teacher){
                    $fullnameteacher =fullname($teacher, true);
                    $by->name =$fullnameteacher;
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
// Pour artefact atranscript
/*
           $artefact= array();
            $artefact['username']=$login;
        
            $artefact['code_etudiant'] = $num_etudiant;
            $artefact['code_etablissement'] = $code_etablissement;
            $artefact['code_diplome']       = $this->occurrence->code_referentiel;
            $artefact['libelle_diplome']    = $this->occurrence->name;
            $artefact['annee_diplome']      = date("Y", $this->certificat->date_decision);
            $artefact['note']               = $this->affiche_competences_validees('/',':',$this->certificat->competences_certificat, $liste_empreintes, $liste_description_competences_poids);

            if (!empty($this->certificat->decision_jury)){
                $artefact['resultat_diplome']=get_string('proposedbynameondate', 'referentiel', $by);
            }
            else{
                $artefact['resultat_diplome']=get_string('evaluatedbynameondate', 'referentiel', $by);
            }
            if (!empty($this->certificat->date_decision)){
                $artefact['date_diplome']=$this->certificat->date_decision;
            }
            else{
                $artefact['date_diplome']=time();
            }
            $artefact['annexedesc']         = $this->occurrence->description_referentiel;
            $artefact['pdfa']               = '';
*/     

            $artefact= '';
            $artefact.= '<username>'.$login.'</username>'."\n";
        
            $artefact.= '<code_etudiant>'.$num_etudiant.'</code_etudiant>'."\n";
            $artefact.= '<code_etablissement>'.$code_etablissement.'</code_etablissement>'."\n";
            $artefact.= '<code_diplome>'.$this->occurrence->code_referentiel.'</code_diplome>'."\n";
            $artefact.= '<libelle_diplome>'.$this->occurrence->name.'</libelle_diplome>'."\n";
            if (!empty($this->certificat->date_decision)){
                $artefact.= '<annee_diplome>'.userdate($this->certificat->date_decision).'</annee_diplome>'."\n";
            }
            else{
                $artefact.= '<annee_diplome>'.date("Y",time()).'</annee_diplome>'."\n";
            }
            $artefact.= '<note>'.$liste_competences.'</note>'."\n";

            if (!empty($this->certificat->decision_jury)){
                $artefact.= '<resultat_diplome>'.get_string('proposedbynameondate', 'referentiel', $by).'</resultat_diplome>'."\n";
            }
            else{
                $artefact.= '<resultat_diplome>'.get_string('evaluatedbynameondate', 'referentiel', $by).'</resultat_diplome>'."\n";
            }
            if (!empty($this->certificat->date_decision)){
                $artefact.= '<date_diplome>'.$this->certificat->date_decision.'</date_diplome>'."\n";
            }
            else{
                $artefact.= '<date_diplome>'.userdate(time()).'</date_diplome>'."\n";
            }
            $artefact.= '<annexedesc>'.stripslashes($this->occurrence->description_referentiel).'</annexedesc>'."\n" ;
            $artefact.= '<pdfa></pdfa>'."\n";
       
            // DEBUG
            // echo "<br />$artefact\n";
            return $artefact;
        }
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
    exit;
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
						$s.=$tcc[0].' :: ';
						// $s.='<td colspan="3">'.str_replace('#','</td><td><i>',$t_poids[$i]).'</i></td>'."\n";
         				// $s.=' '.str_replace('#','<!--',$t_poids[$i]).'-->'."\n";
         				$s.=' '.str_replace('#',' (',$t_poids[$i].' ');
					    $s.=' / '.$t_empreinte[$i].' ) ; '."\n";
					    // $s.='<br />'."\n";
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

