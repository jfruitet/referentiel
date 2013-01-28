<?php
require_once($CFG->libdir.'/formslib.php'); //putting this is as a safety as i got a class not found error.

// ############################### MAHARA ARTEFACT REFERENTIEL

class referentiel_mahara_artefact{
/*
DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 355
Array ( [0] => RPC mod/referentiel/portfolio/rpclib.php/referentiel_publish:
ERROR 7:
User with ID 4 attempted to call unauthorised method mod/referentiel/portfolio/rpclib.php/referentiel_publish on host http://localhost/mahara
[1] => Array ( ) )
EXIT
*/

    /** @var object */
    var $cm;
    /** @var object */
    var $course;
    /** @var object */
    var $instance; // The instance object (referentiel object)
    /** @var object */
    var $certificat;   // The certificate object
    /** @var object */
    var $occurrence; // The occurrence object (referentiel_referentiel object)
    /** @var array */
    var $hosts;  // array of Mahara hosts

    var $choosenhost;               // the host choosen

    private $remotehost; // ???

    
    protected $context;

    protected $name; // Occurrence name
    protected $code; // Occurrence code
    protected $description;  // Ocucrrence description
    protected $items;   // Occurrence Competencies list
    protected $etudiant;  // User id of the user certificated
    protected $referent;  // User id of the teacher/marker
    protected $timestamp;  // Time stamp of evaluation


    // ---------------------------------------
    public function referentiel_mahara_artefact($instance, $certificat=NULL) {
    global $DB;
    global $CFG;
        if (empty($instance)) {
            print_error('Referentiel instance is incorrect');
        }
        else {
            $this->instance = $instance;
            if (! $this->occurrence = $DB->get_record("referentiel_referentiel", array("id" => "$instance->ref_referentiel"))) {
                print_error('Occurrence id is incorrect');
            }
            if (! $this->course = $DB->get_record("course", array("id" => "$instance->course"))) {
	            print_error('Course is misconfigured');
            }
            if (! $this->cm = get_coursemodule_from_instance('referentiel', $instance->id, $this->course->id)) {
    	        print_error('Course Module ID is incorrect');
            }

    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
    //} else {
        //  $this->context = context_module::instance($this->cm); 
    //}

            if (!empty($certificat)){
                $this->certificat = $certificat;
                if (!empty($this->certificat) && !empty($this->certificat->ref_referentiel)
                    && ($this->occurrence->id != $this->certificat->ref_referentiel)){
                    print_error('invalidid', 'referentiel');
                }
            }
            else{
                print_error('invalidcertificat', 'referentiel');
            }
        }
    }

    // ----------------------------------------
    function get_hosts() {
        global $DB;
        global $CFG;
        if (empty($this->hosts)){
            // Get Mahara hosts we are doing SSO with
            $sql = "
             SELECT DISTINCT
                 h.id,
                 h.name
             FROM
                 {mnet_host} h,
                 {mnet_application} a,
                 {mnet_host2service} h2s_IDP,
                 {mnet_service} s_IDP,
                 {mnet_host2service} h2s_SP,
                 {mnet_service} s_SP
             WHERE
                 h.id != :mnet_localhost_id AND
                 h.id = h2s_IDP.hostid AND
                 h.deleted = 0 AND
                 h.applicationid = a.id AND
                 h2s_IDP.serviceid = s_IDP.id AND
                 s_IDP.name = 'sso_idp' AND
                 h2s_IDP.publish = '1' AND
                 h.id = h2s_SP.hostid AND
                 h2s_SP.serviceid = s_SP.id AND
                 s_SP.name = 'sso_idp' AND
                 h2s_SP.publish = '1' AND
                 a.name = 'mahara'
             ORDER BY
                 h.name";

            $this->hosts=$DB->get_records_sql($sql, array('mnet_localhost_id'=>$CFG->mnet_localhost_id));
        }
        return ($this->hosts);
    }

/*
    // ---------------------------------------
    function view() {

        global $CFG, $USER, $DB, $OUTPUT;

        $saved = optional_param('saved', 0, PARAM_BOOL);

        if (empty($this->context)){
            // Valable pour Moodle 2.1 et Moodle 2.2
            //if ($CFG->version < 2011120100) {
                $this->context = get_context_instance(CONTEXT_MODULE, $this->cm->id);
            //} else {
                //  $this->context = context_module::instance($this->cm); 
            //}

        }

        // require_capability('mod/referentiel:archive', $this->context); // NON, tout utilisateur peut enregistrer son propre certificat
        echo $OUTPUT->header();
        $OUTPUT->box_start('generalbox boxwidthnormal boxaligncenter centerpara');

        if ($this->certificat) {
            echo '<div><strong>' . get_string('selectedview', 'referentiel') . ': </strong>'
              . '<a href="' . $CFG->wwwroot . '/auth/mnet/jump.php?hostid=' . $this->remote_mnet_host_id()
              . '&amp;wantsurl=' . urlencode($data['url']) . '">'
              . $data['title'] . '</a></div>';
        }

        if ($submission && $editable) {
            echo '<hr />';
        }

        if ($editable) {

            $query = optional_param('q', null, PARAM_TEXT);
            list($error, $views) = $this->get_views($query);

            if ($error) {
                echo $error;
            }
            else {
                $this->remotehost = $DB->get_record('mnet_host', array('id'=>$this->remote_mnet_host_id()));
                $this->remotehost->jumpurl = $CFG->wwwroot . '/auth/mnet/jump.php?hostid=' . $this->remotehost->id;
                echo '<form><div>' . get_string('selectmaharaview', 'assignment_mahara', $this->remotehost) . '</div><br/>'
                  . '<input type="hidden" name="id" value="' . $this->cm->id . '">'
                  . '<label for="q">' . get_string('search') . ':</label> <input type="text" name="q" value="' . $query . '">'
                  . '</form>';
                if ($views['count'] < 1) {
                    if ($query){
                        echo get_string('noviewsfound', 'assignment_mahara', $this->remotehost->name);
                    }
                    else {
                        echo get_string('noviewscreated', 'assignment_mahara', $this->remotehost->name);
                    }
                }
                else {
                    echo '<h4>' . $this->remotehost->name . ': ' . get_string('viewsby', 'assignment_mahara', $views['displayname']) . '</h4>';
                    echo '<table class="formtable"><thead>'
                      . '<tr><th>' . get_string('preview', 'assignment_mahara') . '</th>'
                      . '<th>' . get_string('submit') . '</th></tr>'
                      . '<tr><td style="padding:0 5px 0 5px;">(' . get_string('clicktopreview', 'assignment_mahara') . ')</td>'
                      . '<td style="padding:0 5px 0 5px;">(' . get_string('clicktoselect', 'assignment_mahara') . ')</td></tr>'
                      . '</thead><tbody>';
                    foreach ($views['data'] as &$v) {
                        $windowname = 'view' . $v['id'];
                        $viewurl = $this->remotehost->jumpurl . '&wantsurl=' . urlencode($v['url']);
                        $js = "this.target='$windowname';window.open('" . $viewurl . "', '$windowname', 'resizable,scrollbars,width=920,height=600');return false;";
                        echo '<tr><td><a href="' . $viewurl . '" target="_blank" onclick="' . $js . '">'
                          . '<img align="top" src="' . $OUTPUT->pix_url('f/html') . '" height="16" width="16" alt="html" /> ' . $v['title'] . '</a></td>'
                          . '<td><a href="?id=' . $this->cm->id. '&view=' . $v['id'] . '">' . get_string('submit') . '</a></td></tr>';
                    }
                    echo '</tbody></table>';
                }
            }

        }
        $OUTPUT->box_end();

        echo $OUTPUT->footer();
        die();
    }

*/


    function set_remote_mnet_host_id($host){
        $this->choosenhost=$host;
    }

    function remote_mnet_host_id() {
        return $this->choosenhost;
    }

    function get_mnet_sp() {
        global $CFG, $MNET;
        require_once $CFG->dirroot . '/mnet/peer.php';
        $mnet_sp = new mnet_peer();
        $mnet_sp->set_id($this->remote_mnet_host_id());
        return $mnet_sp;
    }
/*
    function get_views() {
        global $CFG, $USER, $MNET;

        // Valable pour Moodle 2.1 et Moodle 2.2
        //if ($CFG->version < 2011120100) {
            $systemcontext = get_context_instance(CONTEXT_SYSTEM);
        //} else {
        //    $systemcontext = context_system::instance();
        //}

        $query = '';
        $error = false;
        $viewdata = array();
        if (!is_enabled_auth('mnet')) {
            $error = get_string('authmnetdisabled', 'mnet');
        } else if (!has_capability('moodle/site:mnetlogintoremote', $systemcontext, NULL, false)) {
            $error = get_string('notpermittedtojump', 'mnet');
        }
        else {
            // set up the RPC request
            require_once $CFG->dirroot . '/mnet/xmlrpc/client.php';
            $mnet_sp = $this->get_mnet_sp();
            $mnetrequest = new mnet_xmlrpc_client();
            $mnetrequest->set_method('mod/mahara/rpclib.php/get_views_for_user');
            $mnetrequest->add_param($USER->username);
            $mnetrequest->add_param($query);

            if ($mnetrequest->send($mnet_sp) === true) {
                $viewdata = $mnetrequest->response;
            }
            else {
                $error = "RPC mod/mahara/rpclib.php/get_views_for_user:<br/>";
                foreach ($mnetrequest->error as $errormessage) {
                    list($code, $errormessage) = array_map('trim',explode(':', $errormessage, 2));
                    $error .= "ERROR $code:<br/>$errormessage<br/>";
                }
            }
        }
        return array($error, $viewdata);
    }
*/

/**
 *
 *
 *
 *
 */
 
 /*
    function process_outcomes($userid) {
        global $CFG, $MNET, $USER;
        parent::process_outcomes($userid);

        // Export outcomes to the mahara site
        $grading_info = grade_get_grades($this->course->id, 'mod', 'assignment', $this->assignment->id, $userid);

        if (empty($grading_info->outcomes)) {
            return;
        }

        if (!$submission = $this->get_submission($userid)) {
            return;
        }

        $data = unserialize($submission->data2);

        $viewoutcomes = array();

        foreach($grading_info->outcomes as $o) {
            $scale = make_grades_menu(-$o->scaleid);
            if (!isset($scale[$o->grades[$userid]->grade])) {
                continue;
            }
            // Save array keys; they get lost on the way
            foreach ($scale as $k => $v) {
                $scale[$k] = array('name' => $v, 'value' => $k);
            }
            $viewoutcomes[] = array(
                'name' =>  $o->name,
                'scale' => $scale,
                'grade' => $o->grades[$userid]->grade,
            );
        }

        require_once $CFG->dirroot . '/mnet/xmlrpc/client.php';
        $mnet_sp = $this->get_mnet_sp();
        $mnetrequest = new mnet_xmlrpc_client();
        $mnetrequest->set_method('mod/mahara/rpclib.php/release_submitted_view');
        $mnetrequest->add_param($data['id']);
        $mnetrequest->add_param($viewoutcomes);
        $mnetrequest->add_param($USER->username);
        // Do something if this fails?  Or use cron to export the same data later?
        $mnetrequest->send($mnet_sp);
    }

 */
    /**
     * function to add a referentiel artefact
     * that corresponds to a single certificate,
     *
     * @global object $certificate the stdclass object representing the database record
     * @param portfolio_format_leap2a_writer $leapwriter writer object to add entries to
     * @param string $content  the content of the certificate (prepared by {@link prepare_certificate}
     *
     * @return int id of new entry
     */
    function process_referentiel_mahara() {
        global $CFG, $MNET, $USER;
        global $DB;

        if (!empty($this->certificat) && !empty($this->occurrence)) {
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

            $artefact = array();
            $artefact['name'] = $this->occurrence->name;
            $artefact['code'] = $this->occurrence->code_referentiel;
            $artefact['description'] = $this->occurrence->description_referentiel;
            $artefact['items'] = $this->occurrence->liste_codes_competence;
            $artefact['etudiant'] = $fullname;
            $artefact['referent'] = $fullnameteacher;
            $artefact['timestamp'] = time();

            $liste_empreintes=referentiel_purge_dernier_separateur(referentiel_get_liste_empreintes_competence($this->certificat->ref_referentiel), '/');
		    $liste_description_competences_poids=referentiel_purge_dernier_separateur(referentiel_get_liste_poids($this->certificat->ref_referentiel), '|');


            $artefact['certificat']=$this->affiche_competences_validees('/',':',$this->certificat->competences_certificat, $liste_empreintes, $liste_description_competences_poids);

            if (!empty($this->certificat->decision_jury)){
                $artefact['decision']=get_string('proposedbynameondate', 'referentiel', $by);
            }
            else{
                $artefact['decision']=get_string('evaluatedbynameondate', 'referentiel', $by);
            }
            if (!empty($this->certificat->verrou)){
                $artefact['verrou']=get_string('certificat', 'referentiel').' <i>'.get_string('verrou', 'referentiel').'</i><br />'."\n";
            }
            else{
                $artefact['verrou']=get_string('certificat', 'referentiel').' <i>'.get_string('deverrouille', 'referentiel').'</i><br />'."\n";
            }
            if (!empty($this->certificat->synthese_certificat)){
                $artefact['synthese']=$this->certificat->synthese_certificat;
            }
            if (!empty($this->certificat->date_decision)){
                $artefact['date_decision']=userdate($this->certificat->date_decision);
            }
            if (!empty($this->certificat->commentaire_certificat)){
                $artefact['commentaire']=$this->certificat->commentaire_certificat;
            }
            

            // DEBUG
            // echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 310<br />\n";
            // print_r($artefact);
            // echo "<br />\n";

            // Valable pour Moodle 2.1 et Moodle 2.2
            //if ($CFG->version < 2011120100) {
                $systemcontext = get_context_instance(CONTEXT_SYSTEM);
            //} else {
            //    $systemcontext = context_system::instance();
            //}

            $error = false;
            $viewdata = array();
            if (!is_enabled_auth('mnet')) {
                $error = get_string('authmnetdisabled', 'mnet');
            }
            else if (!has_capability('moodle/site:mnetlogintoremote', $systemcontext, NULL, false)) {
                $error = get_string('notpermittedtojump', 'mnet');
            }
            else {
                // set up the RPC request
                require_once $CFG->dirroot . '/mnet/xmlrpc/client.php';
                $mnet_sp = $this->get_mnet_sp();
                //echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 336<br />MNET_SP<br />\n";
                //print_object($mnet_sp);
                //echo "<br />\n";
                //echo "DEBUG LEVEL MNET : ".get_config('', 'mnet_rpcdebug');
                //echo "<br />\n";
                $mnetrequest = new mnet_xmlrpc_client();
                $mnetrequest->set_method('mod/referentiel/portfolio/rpclib.php/set_external_data');
                $mnetrequest->add_param($artefact);
                $mnetrequest->add_param($USER->username);
                // DEBUG
                // echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 347<br />MNETREQUEST<br />\n";
                // print_object($mnetrequest);
                // echo "<br />\n";
                //exit;

                if ($mnetrequest->send($mnet_sp) === true) {
                    $viewdata = $mnetrequest->response;
                }
                else {
                    $error = "RPC mod/referentiel/portfolio/rpclib.php/set_external_data:<br/>";
                    foreach ($mnetrequest->error as $errormessage) {
                        list($code, $errormessage) = array_map('trim',explode(':', $errormessage, 2));
                        $error .= "ERROR $code:<br/>$errormessage<br/>";
                    }
                }
            }

            // Do something if this fails?  Or use cron to export the same data later?

            // DEBUG
            echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 367<br />\n";
            print_r(array($error, $viewdata));
            // echo "<br />EXIT\n";
            // exit;

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

class upload_host_form  extends moodleform {

    // ----------------------------------------
    function definition() {
        global $CFG, $COURSE, $DB;
        global $OUTPUT;

        $mform = & $this->_form;
        $instance = $this->_customdata;
        // DEBUG
        /*
        echo "<br />./portfolio/mahara_artefact_referentiel.class.php :: Line 328 \n";
        print_object($instance);
        echo "<br />EXIT \n";
        //exit;
        */
        
        $mform->addElement('header', 'general', get_string('exportmahara', 'referentiel'));
        if ($instance['hosts']) {
            foreach ($instance['hosts'] as &$h) {
                $h = $h->name;
            }

            $mform->addElement('select', 'host', get_string('site', 'referentiel'), $instance['hosts']);
            $mform->addHelpButton('host', 'site', 'referentiel');
            $mform->setDefault('host', key($instance['hosts']));
        }
        else {
            // TODO: Should probably error out if no mahara hosts found?
            $mform->addElement('static', '', '', get_string('nomaharahostsfound', 'referentiel'));
        }
        // hidden params
        $mform->addElement('hidden', 'd', $instance['d']);
        $mform->setType('d', PARAM_INT);

        $mform->addElement('hidden', 'certificatid', $instance['certificatid']);
        $mform->setType('certificatid', PARAM_INT);

        $mform->addElement('hidden', 'contextid', $instance['contextid']);
        $mform->setType('contextid', PARAM_INT);

        $mform->addElement('hidden', 'userid', $instance['userid']);
        $mform->setType('userid', PARAM_INT);

        // buttons
        $this->add_action_buttons(true, get_string('select', 'referentiel'));
    }
}