<?php
    require_once("../../../config.php");

    /*
      ajouter les fonctions de gestion des artefacts dans 
      mahara/api/xmlprc/lib.php
    
    */
        global $CFG, $MNET, $USER;
        global $DB;

            $fullname = 'Etudiant LAMBDA';
            $login='lambda';
            $fullnameteacher = 'Professeur LAMBDA';
            $loginteacher='plambda';

            $by = new stdClass();
            $by->name = $fullnameteacher;
            $by->date = date("Y-m-d H:i:s");

            $artefact = array();
            $artefact['name'] = 'Référentiel C2i2e - 2011';
            $artefact['code'] = 'C2i2e - 2011';
            $artefact['description'] = 'Certificat Informatique et Internet niveau 2 Enseignant - 2011';
            $artefact['items'] = '2 	A.1-1/A.1-2/A.1-3/A.1-4/A.1-5/A.2-1/A.2-2/A.2-3/A.3-1/A.3-2/A.3-3/A.3-4/B.1-1/B.1-2/B.1-3/B.2-1/B.2-2/B.2-3/B.2-4/B.2-5/B.3-1/B.3-2/B.3-3/B.3-4/B.3-5/B.4-1/B.4-2/B.4-3/';
            $artefact['etudiant'] = $fullname.' ('.$login.')';
            $artefact['referent'] = $fullnameteacher.' ('.$loginteacher.')';
            $artefact['timestamp'] = time();

            $artefact['certificat']='<ul><li>A.1-1 : Identifier les personnes ressources Tic et leurs rôles respectifs au niveau local, régional et national.</li><li>A.2-2 : Se référer à des travaux de recherche liant savoirs, apprentissages et Tice.</li></ul>';
            $artefact['decision']='<b>Ajourné</b>';
            $artefact['verrou']='<i>Non verrouillé</i>';


            // DEBUG
            echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 31<br />\n";
            print_r($artefact);
            echo "<br />\n";

        // Valable pour Moodle 2.1 et Moodle 2.2
        //if ($CFG->version < 2011120100) {
            $systemcontext = get_context_instance(CONTEXT_SYSTEM);
        //} else {
        //    $systemcontext = context_system::instance();
        //}

            $error = false;
            $viewdata = array();
            /*
            if (!is_enabled_auth('mnet')) {
                $error = get_string('authmnetdisabled', 'mnet');
            }
            else if (!has_capability('moodle/site:mnetlogintoremote', $systemcontext, NULL, false)) {
                $error = get_string('notpermittedtojump', 'mnet');
            }
            else {
            */
                // set up the RPC request
                require_once $CFG->dirroot . '/mnet/xmlrpc/client.php';
                $mnet_sp = 3;

                $mnetrequest = new mnet_xmlrpc_client();
                $mnetrequest->set_method('mod/referentiel/portfolio/rpclib.php/referentiel_publish');
                $mnetrequest->add_param($artefact);
                $mnetrequest->add_param($USER->username);

                // DEBUG
                echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 57<br />\n";
                print_object($mnetrequest);
                echo "<br />\n";


                if ($mnetrequest->send($mnet_sp) === true) {
                    $viewdata = $mnetrequest->response;
                }
                else {
                    $error = "RPC mod/referentiel/portfolio/rpclib.php/referentiel_publish:<br/>";
                    foreach ($mnetrequest->error as $errormessage) {
                        list($code, $errormessage) = array_map('trim',explode(':', $errormessage, 2));
                        $error .= "ERROR $code:<br/>$errormessage<br/>";
                    }
                }
           // }

            // Do something if this fails?  Or use cron to export the same data later?

            // DEBUG
            echo "<br />DEBUG :: ./mod/referentiel/portfolio/mahara_artefact_referentiel.php :: Line 77<br />\n";
            print_r(array($error, $viewdata));
            echo "<br />EXIT\n";
            exit;
 /*
 http://localhost/moodle2/admin/mnet/testclient.php?hostid=3&servicename=sso_in
 http://localhost/moodle2/admin/mnet/testclient.php?hostid=3&method=1&servicename=referentiel_publish
 */

?>
