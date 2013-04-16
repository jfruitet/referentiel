<?php  // $Id:  lib.php,v 2.0 2011/04/20 00:00:00 jfruitet Exp $
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
 * @version $Id: lib.php,v 2.0 2011/04/20 00:00:00 jfruitet Exp $
 * @package referentiel v 6.0.00 2011/20/01 00:00:00
 **/
 
// Version Moodle 2
// passage en modele objet

/** Include eventslib.php */
require_once($CFG->libdir.'/eventslib.php');
/** Include formslib.php */
require_once($CFG->libdir.'/formslib.php');
/** Include calendar/lib.php */
require_once($CFG->dirroot.'/calendar/lib.php');

// 2010/10/18 : configuration

require_once ("lib_config.php");
require_once ("lib_referentiel.php");     // MODIF JF 2012/03/08
require_once ("lib_users.php");
require_once ("lib_cron.php");
require_once ("lib_accompagnement.php");
require_once ("lib_repartition.php"); // version 1.2 decembre 2011
require_once ("lib_protocole.php"); // protocole de certification // A partir de la version 2.1.05
require_once ("lib_backup.php");   // nouveauté Moodle 2.0

require_once ("class/referentiel.class.php");
require_once ("locallib.php");

// les constantes suivantes permettent de tuner le fonctionnement du module
// a ne modifier qu'avec précaution
// La bibliothèque Overlib n'est plus intégrée par défaut depuis Moodle 2.3
// or je l'utilise pour afficher les compétences en survol...
$OverlibJs='/mod/referentiel/overlib/overlib.js';

// Utilise dans le module report/referentiel
define ("JOURS_DESHERENCE", 28);  // delai au dela duquel les activités non évaluées sont signalées au gestionnaire / administrateur

// Mahara atranscript est un artefact Mahara en cours de développement
// Il n'est pas disponible opour le moment
// define ('MAHARA_ARTEFACT_ATRANSCRIPT', 1);  // placer à 0 pour desactiver le traitement
define ('MAHARA_ARTEFACT_ATRANSCRIPT', 0);  // placer à 1 pour activer le traitement

// Suppression des fichiers d'ARCHIVES
define('REFERENTIEL_ARCHIVE_OBSOLETE', 7); // Après 7 jours le cron supprime les fichiers d'Archive

// traitement des activites evaluées par objectifs
define ('REFERENTIEL_OUTCOMES', 1);   // placer à 0 pour désactiver le traitement

// DEBUG ?
// si à 1 le cron devient très bavard :))
// et les messages en attente portent sur une semaine au lieu de deux jours.
define ('REFERENTIEL_DEBUG', 0);    // DEBUG INACTIF
// define ('REFERENTIEL_DEBUG', 1);       // DEBUG ACTIF  : le cron devient très bavard :))
define ('OUTCOMES_SUPER_DEBUG', 0);       // SUPER DEBUG OUTCOMES INACTIF
// define ('OUTCOMES_SUPER_DEBUG', 1);       // SUPER DEBUG OUTCOMES ACTIF : affichage tres detaille

define('MAXLENCODE', 220);// Longueur maximale de la liste des codes d'item
// au delà de laquelle les certificats ne sont plus affichés dans un tableau par la fonction locallib.php::referentiel_affiche_certificat_consolide()

define('TIME_LIMIT', 360);// temps  maximal d'exécution d'un script si PHP ne fonctionne pas en safe_mode

define('EDITOR_ON', 1);// editeur de referentiels simplifié wysiwyg actif (necessite le dossier mod/referentiel/editor sur le serveur)
// define('EDITOR_ON', 0);   // editeur inactif

define('MAXBOITESSELECTION', 6);  // à réduire si le nombre de boites de selection des etudiants
// ne tient pas dans la page sans ascenceur horizontal

define('NOTIFICATION_TACHES', 1); // placer à 0 pour désactiver la notification
define('NOTIFICATION_ACTIVITES', 1); // placer à 0 pour désactiver la notification
define('NOTIFICATION_CERTIFICATS', 1); // placer à 0 pour désactiver la notification

define('NOTIFICATION_TACHES_AUX_REFERENTS', 0); // placer à 1 pour activer la notification de la tâches aux enseignants du cours ; en general inutile
define('NOTIFICATION_JURY', 0); // placer à 1 pour activer la notification la certification ; notification en général inutile
define('NOTIFICATION_AUTEUR_ACTIVITE', 0); // placer à 0 pour désactiver la notification de l'auteur de la declaration d'activite ; notification en général inutile

define('NOTIFICATION_DELAI', 0); // placer à la valeur à 1 pour activer la temporisation de la notification entre le moment ou l'activité
// est validée et celui où elle est notifiée
define('NOTIFICATION_INTERVALLE_JOUR', 2); // 2 jours d'intervalle de temps d'action du cron
// augmenter la valeur pour prendre en compte des évaluatios anciennes
// cela aura pour effet de réactiver des prise en compte d'évaluation par objectifs
// et de relancer des notifications
// surtout utile pour deboguer si le cron ne s'est pas exercé depuis un temps certain

// CONSTANTES  NE PAS MODIFIER
define('TYPE_ACTIVITE', 0);    // Ne pas modifier
define('TYPE_TACHE', 1);       // Ne pas modifier
define('TYPE_CERTIFICAT', 2);  // Ne pas modifier

define('MAXLIGNEGRAPH', 15);  // Nombre de lignes par graphique





/// Liste des rubriques (non exhaustive)

/// CRON
/// CONFIGURATION
/// ACTIVITES
/// TACHES
/// CERTIFICATS
/// URL


/// FONCTIONS A ECRIRE /////////////////////////////////////////////////////////////////////////

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all instance of referentiel
 * and clean up any related data.
 *
 * @global object
 * @global object
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function referentiel_reset_userdata($data) {
    global $CFG, $DB;
    $componentstr = get_string('modulenameplural', 'referentiel');
    $status = array();

    $strstatus='';
    if ($instances=$DB->get_records('referentiel', array('course' => $data->courseid ))){
        foreach ($instances as $instance){
            referentiel_delete_instance($instance->id);
            $strstatus.=$instance->name.", ";
        }
        $status[] = array('component'=>$componentstr, 'item'=>$strstatus, 'error'=>false);

    }

    return $status;
}


/**
 * Must return an array of users who are participants for a given instance
 * of referentiel. Must include every user involved in the instance,
 * independient of his role (student, teacher, admin...). The returned
 * objects must contain at least id property.
 * See other modules as example.
 *
 * @param int $referentielid ID of an instance of this module
 * @return boolean|array false if no participants, array of objects otherwise
 */

function referentiel_get_participants($referentielid) {
    global $CFG, $DB;
    return false;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 * DEPRECATED with Moodle 2
 * @return boolean true if success, false on error
 */
// function referentiel_uninstall() {
//     return true;
// }

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in referentiel activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function referentiel_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;

    return false;  //  True if anything was printed, otherwise false 
}
// ################################### EDITOR

function  referentiel_editor_is_ok(){
// editeur wisiwyg  appele depuis mod.html
// non implanté car en cours de developpement :))
    return EDITOR_ON;
}

// pour suppprimer le probleme des noms de fichiers incorrects
function referentiel_purge_caracteres_incorrects($str){
    $replace_pairs = array("\\" => "","'" => "_", " " => "_");
    return strtr ( $str , $replace_pairs );
}


/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $referentielid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function referentiel_grades($referentielid) {
// A FAIRE
// renvoie le carnet de notes de l'instance Ã  Moodle, afin que la plate-forme l'intÃ¨gre dans son carnet de notes global 
// pour l'Ã©tudiant. Cette fonction DOIT retourner un combinÃ© de deux tableau associatif : 
// { 'grades' => { userId => array of double }, 'maxgrades' => { userId = > array of double }}.
// Le premier tableau renvoie les notes obtenues, le deuxiÃ¨me renvoie les notes maximales 
// (ex : je renvoie pour l'utilisateur 23 les notes 7/20 et 13/15 :
// { 'grades' => { 23 => (7, 13)}, 'maxgrades' => { 23 => (20, 15)}}
// Un module peut donc renvoyer une sÃ©rie de notes pour chaque Ã©tudiant. 

   return false;
}


/**
 * This function returns if a scale is being used by one referentiel
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $referentielid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function referentiel_scale_used ($referentielid, $scaleid) {
    $return = false;
    // thows dml exception
    //$rec = $DB->get_record("referentiel", array("id"=>"$referentielid","scale"=>"-$scaleid");
    //
    //if (!empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}


/**
 * Checks if scale is being used by any instance of refrentiel
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any referentiel
 */
function referentiel_scale_used_anywhere($scaleid) {
global $DB;
/*
    $params = array('grade' => -$scaleid);
    if ($scaleid and $DB->record_exists('referentiel', $params)) {
        return true;
    }
    else {
        return false;
    }
*/
    return false;
}





/// OTHER STANDARD FUNCTIONS ////////////////////////////////////////////////////////

/**
 * Deletes an referentiel instance
 *
 * This is done by calling the delete_instance() method of the referentiel type class
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any activity and certificate and task ank accompagnement
 * that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */


function referentiel_delete_instance($id){
    global $CFG, $DB;

    if (! $referentiel = $DB->get_record('referentiel', array('id'=>$id))) {
        return false;
    }

    $ref = new referentiel();
    return $ref->delete_instance($referentiel);
}


/**
 * Updates an referentiel instance
 *
 * This is done by calling the update_instance() method of the referentiel type class
 */
function referentiel_update_instance($referentiel){
    global $CFG;
    $ref = new referentiel();
    return $ref->update_instance($referentiel);
}

/**
 * Adds an referentiel instance
 *
 * This is done by calling the add_instance() method of the referentiel type class
 */
function referentiel_add_instance($referentiel) {
    $ref = new referentiel();
    return $ref->add_instance($referentiel);
}

/**
 * Given an object containing all the necessary referentiel,
 * (defined by the form in mod.html) this function
 * will update instance and return true or false
 *
 * @param object $form An object from the form in edit.html
 * @return int The id of the newly inserted referentiel record
 **/
/*

function referentiel_associe_referentiel_instance($form){
// importation ou selection ou creation
global $DB;
	if (!empty($form->instance)
		&& !empty($form->new_referentiel_id)){
		// id referentiel doit etre numerique
		$referentiel_id = intval(trim($form->instance));
		$referentiel_referentiel_id = intval(trim($form->new_referentiel_id));
		$referentiel = referentiel_get_referentiel($referentiel_id);
		$referentiel->ref_referentiel=$referentiel_referentiel_id;
		// DEBUG
		// echo "<br />DEBUG :: lib.php :: 152 :: referentiel_associe_referentiel_instance()<br />\n";
		// print_object($referentiel);
		// echo "<br />";
		return ($DB->update_record("referentiel", $referentiel));
	}
	return 0;
}
*/

// MODIF JF 2012/03/05

function referentiel_associe_referentiel_instance($instanceid, $refrefid){
// importation ou selection ou creation
global $DB;
	if (!empty($instanceid) && !empty($refrefid) ){
        return $DB->set_field("referentiel", "ref_referentiel", $refrefid, array("id"=>$instanceid));
	}
	return 0;
}



/**
 * Given an object containing referentiel id, 
 * will set referentiel_id to 0
 *
 * @param id 
 * @return 0
 **/
function referentiel_de_associe_referentiel_instance($id){
global $DB;
// suppression de la reference vers un referentiel_referentiel
	if (!empty($id)){
		// id referentiel doit Ãªtre numerique
		$id = intval(trim($id));
		$referentiel = referentiel_get_referentiel($id);
		$referentiel->ref_referentiel=0;

		// DEBUG
		// print_object($referentiel);
		// echo "<br />";
		return ($DB->update_record("referentiel", $referentiel));
	}
	return 0;
}

//////////////////////////////////////////////////////////////////////////////////////
/// Any other referentiel functions go here.  Each of them must have a name that 
/// starts with referentiel_

/**
 * This function returns max id from table passed
 *
 * @param table name
 * @return id
 * @todo Finish documenting this function
 **/
function referentiel_get_max_id($table){
global $DB;
	if (isset($table) && ($table!="")){
        $sql="SELECT MAX(id) as m FROM {$table}";
        $r=$DB->get_record_sql($sql, NULL);
		if (!empty($r) && !empty($r->m)){
			return $r->m;
		}
	}
	return 0;
}

/**
 * This function returns min id from table passed
 *
 * @param table name
 * @return id
 * @todo Finish documenting this function
 **/
function referentiel_get_min_id($table){
global $DB;
	if (isset($table) && ($table!="")){
		$r=$DB->get_record_sql("SELECT MIN(id) as m FROM {$table}", NULL);
		if (!empty($r) && !empty($r->m)){
			return $r->m;
		}
	}
	return 0;
}


function referentiel_get_table($id, $table) {
global $DB;
// retourn un objet  
    // DEBUG
    // temp added for debugging
    // echo "DEBUG : GET INSTANCE CALLED";
    // echo "<br />";
	
	// referentiel
	$objet = $DB->get_record($table, array("id" => "$id"));
    // DEBUG
	// print_object($objet);
    // echo "<br />";
	return $objet;
}

/**
 * This function returns nomber of domains from table referentiel
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_nb_domaines($id){
global $DB;
	if (isset($id) && ($id>0)){
        $params = array("id" => "$id");
        $sql="SELECT nb_domaines FROM {referentiel_referentiel} WHERE id=:id";
		return $DB->get_record_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns records from table referentiel_domaine
 *
 * @param ref
 * @return record
 * @todo Finish documenting this function
 **/
function referentiel_get_domaines($ref_referentiel){
global $DB;
	if (!empty($ref_referentiel)){
        $params = array("refid" => "$ref_referentiel");
        $sql="SELECT * FROM {referentiel_domaine} WHERE ref_referentiel=:refid ORDER BY num_domaine ASC";
		return $DB->get_records_sql($sql, $params);
	}
	else 
		return 0; 
}


/**
 * This function returns nomber of competences from table referentiel_domaine
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_nb_competences($id){
global $DB;
	if (isset($id) && ($id>0)){
        $params = array("id" => "$id");
        $sql="SELECT nb_competences FROM {referentiel_domaine} WHERE id=:id";
        return $DB->get_record_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns records from table referentiel_item_competence
 *
 * @param ref
 * @return id
 * @todo Finish documenting this function
 **/
function referentiel_get_competences($ref_domaine){
global $DB;
	if (!empty($ref_domaine)){
        $params = array("refdomaine" => "$ref_domaine");
        $sql="SELECT * FROM {referentiel_competence} WHERE ref_domaine=:refdomaine ORDER BY num_competence ASC";
        return $DB->get_records_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns nomber of items from table referentiel_competence
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_nb_item_competences($id){
global $DB;
	if (!empty($id)){
        $params = array("id" => "$id");
        $sql="SELECT nb_item_competences FROM {referentiel_competence} WHERE id=:id";
		return $DB->get_record_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns records from table referentiel_item_competence
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_item_competences($ref_competence){
global $DB;
	if (!empty($ref_competence)){
	    $params = array("refcompetence" => "$ref_competence");
        $sql="SELECT * FROM {referentiel_item_competence} WHERE ref_competence=:refcompetence ORDER BY num_item ASC ";
        return $DB->get_records_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns an int from table referentiel_item_competence
 *
 * @param id
 * @return int of poids
 * @todo Finish documenting this function
 **/
function referentiel_get_poids_item($code, $referentiel_id){
global $DB;
	if (!empty($code)){
	    $params=array("code" => "$code", "refid" => "$referentiel_id");
        $sql="SELECT poids_item FROM {referentiel_item_competence} WHERE code_item=:code AND ref_referentiel=:refid ";
		$record=$DB->get_record_sql($sql, $params);
		if ($record){
			return $record->poids_item;
		}
	}
	return 0;
}


/**
 * This function returns an int from table referentiel_item_competence
 *
 * @param referentiel id
 * @return int of empreinte
 * @todo Finish documenting this function
 **/
function referentiel_get_empreinte_item($code, $referentiel_id){
global $DB;
	if (!empty($code)){
	    $params=array("code" => "$code", "refid" => "$referentiel_id");
        $sql="SELECT empreinte_item FROM {referentiel_item_competence} WHERE code_item=:code AND ref_referentiel=:refid";
		$record=$DB->get_record_sql($sql, $params);
		if ($record){
			return $record->empreinte_item;
		}
	}
	return 0;
}


/**
 * This function returns a string from table referentiel_item_competence
 *
 * @param referentiel id
 * @return string of description#poids|
 * @todo Finish documenting this function
 **/
function referentiel_get_liste_poids($referentiel_id){
global $DB;
$liste="";
    $params=array("refid" => "$referentiel_id");
    $sql="SELECT id, description_item, poids_item FROM {referentiel_item_competence} WHERE ref_referentiel=:refid ORDER BY id ";
	$records=$DB->get_records_sql($sql, $params);
	if ($records){
		 foreach ($records as $record) {
		 	$liste.= strtr(stripslashes($record->description_item),'#|/:','    ').'#'.$record->poids_item.'|';
		 }
	}
	return $liste;
}



/**
 * This function returns a string from table referentiel_item_competence
 *
 * @param code, referentiel id
 * @return string
 * @todo Finish documenting this function
 **/

function referentiel_get_description_item($code, $referentiel_id=0){
global $DB;
	if (!empty($code)){
		if ($referentiel_id){
            $params=array("code" => "$code", "refid" => "$referentiel_id");
            $sql="SELECT description_item FROM {referentiel_item_competence} WHERE code_item=:code AND ref_referentiel=:refid";
            $record=$DB->get_record_sql($sql, $params);
			if ($record){
				return $record->description_item;
			}
		}
		else{
            $params=array("code" => "$code");
            $sql="SELECT description_item FROM {referentiel_item_competence} WHERE code_item=:code";
			$records=$DB->get_records_sql($sql, $params);
			if ($records){
				$s="";
				foreach ($records as $record){
					$s.=$record->description_item." ";
				}
				return $s;
			}
		}
	}
	return "";
}

/**
 * This function returns records from table referentiel
 *
 * @param $id : int id refrentiel to filter
 * $params filtering clause
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_filtrer($id, $data){
global $DB;
	if (!empty($id)){
        $params=array("id" => "$id", "zero" => "0");
		$where = "WHERE id=:id ";
        if (isset($data)){
			if (isset($data->filtrerinstance) && ($data->filtrerinstance!=0)){
				if (isset($data->localinstance) && ($data->localinstance==0)){
					$where .= " AND local=:zero ";
				}
				else {
					$where .= " AND local!=:zero ";
				}
			}
			// $data->referentiel_pass
			if (isset($data->referentiel_pass) && ($data->referentiel_pass!='')){
                $params=array("id" => "$id", "zero" => "0", "refpass" => "$data->referentiel_pass");
				$where .= " AND pass_referentiel=:refpass ";
			}
		}
		$sql="SELECT * FROM {referentiel_referentiel} $where ";
		$record = $DB->get_record_sql($sql, $params);
		if ($record){
			return $record->id;
		}
		else {
			return 0;
		}
	}
	else{
		return 0;
	}
}

/**
 * This function returns records from table referentiel_referentiel
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_referentiel_referentiel($id){
global $DB;
 if (!empty($id)){
        $params = array("id" => "$id");
        $sql="SELECT * FROM {referentiel_referentiel} WHERE id=:id ";
		return $DB->get_record_sql($sql, $params);
	}
	else 
		return 0; 
}

/**
 * This function returns string from table referentiel_referentiel
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_nom_referentiel($id){
global $DB;
$s="";
 if (!empty($id)){
        $params = array("id" => "$id");
        $sql="SELECT * FROM {referentiel_referentiel} WHERE id=:id ";
        $record=$DB->get_record_sql($sql, $params);
		if ($record){
			$s=$record->name;
		}
	}
	return $s; 
}


/**
 * This function returns records from table referentiel
 *
 * @param $params filtering clause
 * @return records
 * @todo Finish documenting this function
 **/
function referentiel_get_referentiel_referentiels($data){
global $DB;
	$where = "";
	if (isset($data)){
		if (isset($data->filtrerinstance) && ($data->filtrerinstance!=0)){
			if (isset($data->localinstance) && ($data->localinstance==0)){
				$where = " WHERE local=0 ";
			}
			else {
				$where = " WHERE local!=0 ";
			}
		}
	}
	$sql="SELECT * FROM {referentiel_referentiel} $where ORDER BY id ASC ";
	return $DB->get_records_sql($sql, NULL);
}

/**
 * This function returns records from table referentiel
 *
 * @param $params filtering clause
 * @return records
 * @todo Finish documenting this function
 **/
function referentiel_get_infos_from_code_ref($code){
global $DB;
	$trefref = array();
	if (!empty($code)){
        $params=array("code" => "$code");
        $sql="SELECT * FROM {referentiel_referentiel} WHERE code_referentiel=:code ORDER BY id ASC ";
        $recs=$DB->get_records_sql($sql, $params);
        if ($recs){
            foreach($recs as $rec){
                $trefref[]=$rec->id.':'.stripslashes($rec->code_referentiel).':'.stripslashes($rec->name);
            }
       }
    }
    return $trefref;
}

/**
 * This function returns records from table referentiel
 *
 * @param id
 * @return int
 * @todo Finish documenting this function
 **/
function referentiel_get_referentiel($id){
global $DB;
	if (!empty($id)){
	    $params = array("id" => "$id");
        $sql="SELECT * FROM {referentiel} WHERE id=:id ";
		return $DB->get_record_sql($sql, $params);
	}
	else 
		return 0; 
}


	// ------------------
	function referentiel_get_logo($referentiel){
	// A TERMINER
	global $OUTPUT;
		// Moodle 1.9
        // return "pix/logo_men.jpg";
        // Moodle 2.0
        return $OUTPUT->pix_url('logo_men','referentiel');
	}


    // ################################ URL  ###############################

    /**
     * display an url accorging to moodle file mangement
     * @return string active link
	 * @ input $url : an uri
	 * @ input $etiquette : a label
     */

    function referentiel_affiche_url($url, $etiquette="", $cible="") {
    // ADAPTE MOODLE2
	global $CFG;
	// Moodle 1.9
		/*
		$importfile = "{$CFG->dataroot}/{$url}";
		if (file_exists($importfile)) {
	        if ($CFG->slasharguments) {
    	    	$efile = "{$CFG->wwwroot}/file.php/$url";
        	}
		    else {
				$efile = "{$CFG->wwwroot}/file.php?file=/$url";
        	}
		}
		else{
			$efile = "$url";
		}
		*/
		// Moodle 2.0
		if (!preg_match("/http/",$url)){ // fichier telecharge
            // l'URL a été correctement formée lors de la création du fichier
            $efile =  $CFG->wwwroot.'/pluginfile.php'.$url;
        }
        else{
            $efile = $url;
        }

		if ($etiquette==""){
			$l=strlen($url);
			$posr=strrpos($url,'/');
			if ($posr===false){ // pas de separateur
				$etiquette=$url;
			}
			else if ($posr==$l-1){ // separateur en fin
				$etiquette=get_string("etiquette_inconnue", "referentiel");
			}
			else if ($posr==0){ // separateur en tete et en fin !
				$etiquette=get_string("etiquette_inconnue", "referentiel");
			}
			else {
				$etiquette=substr($url,$posr+1);
			}
		}
		
        if ($cible){
            return "<a href=\"$efile\" target=\"".$cible."\">$etiquette</a>";
        }
        else{
            return "<a href=\"$efile\">$etiquette</a>";
        }

    }


    /**
     * display an url accorging to moodle file mangement
     * clone of referentiel_affiche_url()
     * @return string active link
	 * @ input $url : an uri
	 * @ input $etiquette : a label
	 * @ input $cible :
     */
    function referentiel_get_url($url, $etiquette="", $cible="") {
    // clone de referentiel_affiche_url()
         return referentiel_affiche_url($url, $etiquette, $cible);
    }



// ############################ MOODLE 1.9 FILE API #########################
    /**
     * get directory into which export is going
     * @return string file path
	 * @ input $course_id : id of current course
	 * @ input $sous_repertoire : a relative path
     */
    function referentiel_get_export_dir($course_id, $sous_repertoire="") {
	global $CFG;
	/*
    // ensure the files area exists for this course
	// $path_to_data=referentiel_get_export_dir($course->id,"$referentiel->id/$USER->id");
	$path_to_data=referentiel_get_export_dir($course->id);
    make_upload_directory($path_to_data);
	*/
        $dirname = get_string('exportfilename', 'referentiel');
        $path = $course_id.'/'.$CFG->moddata.'/'.$dirname;
		if ($sous_repertoire!=""){
			$pos=strpos($sous_repertoire,'/');
			if (($pos===false) || ($pos!=0)){ // separateur pas en tete
				// RAS
			}
			else {
				$sous_repertoire = substr($sous_repertoire,$pos+1);
			}
			$path .= '/'.$sous_repertoire;
		}
        return $path;
    }




    /**
     * write a file
     * @return boolean
	 * @ input $path_to_data : a data path
	 * @ input $filename : a filename
     */
    function referentiel_enregistre_fichier($path_to_data, $filename, $expout) {
        global $CFG;
        // create a directory for the exports (if not already existing)
        if (! $export_dir = make_upload_directory($path_to_data)) {
              print_error( get_string('cannotcreatepath', 'referentiel', $export_dir) );
			  return "";
        }
        $path = $CFG->dataroot.'/'.$path_to_data;

        // write file
        $filepath = $path."/".$filename;

		// echo "<br />DEBUG : 2580 :: FILENAME : $filename <br />PATH_TO_DATA : $path_to_data <br />PATH : $path <br />FILEPATH : $filepath\n";

        if (!$fh=fopen($filepath,"w")) {
            return "";
        }
        if (!fwrite($fh, $expout, strlen($expout) )) {
            return "";
        }
        fclose($fh);
        return $path_to_data.'/'.$filename;
    }

    /**
     * write a file
     * @return boolean
	 * @ input $path_to_data : a data path
	 * @ input $filename : a filename
     */
    function referentiel_upload_fichier($path_to_data, $filename_source, $filename_dest) {
        global $CFG;
        // create a directory for the exports (if not already existing)
        if (! $export_dir = make_upload_directory($path_to_data)) {
              print_error( get_string('cannotcreatepath', 'referentiel', $export_dir) );
			  return "";
        }
        $path = $CFG->dataroot.'/'.$path_to_data;

		if (referentiel_deplace_fichier($path, $filename_source, $filename_dest, '/', true)){
			return $path_to_data.'/'.$filename_dest;
		}
		else {
			return "";
		}
    }

// ------------------
function referentiel_deplace_fichier($dest_path, $source, $dest, $sep, $deplace) {
// recopie un fichier sur le serveur
// pour effectuer un deplacement $deplace=true
// @ devant une fonction signifie qu'aucun message d'erreur n'est affiche
// $dest_path est le dossier de destination du fichier
// source est le nom du fichier source (sans chemin)
// dest est le nom du fichier destination (sans chemin)
// $sep est le separateur de chemin
// retourne true si tout s'est bien deroule

	// Securite
	if (strstr($dest, "..") || strstr($dest, $sep)) {
		// interdire de remonter dans l'arborescence
		// la source est detruite
		if ($deplace) @unlink($source);
		return false;
	}

	// repertoire de stockage des fichiers
	$loc = $dest_path.$sep.$dest;
// 	$ok = @copy($source, $loc);
	$ok =  @copy($source, $loc);
	if ($ok){
		// le fichier temporaire est supprime
		if ($deplace)  @unlink($source);
	}
	else{
		// $ok = @move_uploaded_file($source, $loc);
		$ok =  @move_uploaded_file($source, $loc);
	}
	return $ok;
}


	// ------------------
	function referentiel_get_file($filename, $course_id, $path="" ) {
	// retourne un path/nom_de_fichier dans le dossier moodledata
 		global $CFG; global $DB;
 		if ($path==""){
			$currdir = $CFG->dataroot."/$course_id/$CFG->moddata/referentiel/";
  		}
		else {
			$currdir = $CFG->dataroot."/$course_id/$CFG->moddata/referentiel/".$path;
		}

	    if (!file_exists($currdir.'/'.$filename)) {
      		return "";
      	}
		else{
			return $currdir.'/'.$filename;
		}
 	}





// ############################ MOODLE 2.0 FILE API #########################

/**
 * Serves activite documents and other files.
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - just send the file
 */
function referentiel_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
// fonction appelée par le gestionnaire de fichier
    global $CFG, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_login($course, false, $cm);

    // verifier qu'un referentiel est installé dans ce cours
    if (! $referentiel = $DB->get_record("referentiel", array("id" => "$cm->instance"))) {
        return false;
    }

    return referentiel_send_file($course, $cm, $context, $filearea, $args);
}

/**
 * Serves activite documents and other files.
 *
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @return bool false if file not found, does not return if found - just send the file
 */

function referentiel_send_file($course, $cm, $context, $filearea, $args) {
// affiche le contenu d'un fichier
        global $CFG, $DB, $USER;
        require_once($CFG->libdir.'/filelib.php');

        require_login($course, false, $cm);

        // DEBUG
        // echo "<br />DEBUG :: lib.php :: 5619 :: <br />CONTEXT : $context->id :: FILEAREA : $filearea<br />ARGS:\n";
        // print_r($args);
        //
        $docid = (int)array_shift($args);

        // verifier les fileareas acceptables
        $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie', 'outcomes', 'archive');
        if (!in_array($filearea, $fileareas)) {
            return false;
        }

        if ($filearea=='document'){
            if (!$document = $DB->get_record('referentiel_document', array('id'=>$docid))) {
                return false;
            }
        }
        else if ($filearea=='consigne'){
            if (!$document = $DB->get_record('referentiel_consigne', array('id'=>$docid))) {
                return false;
            }
        }
        
        $relativepath = implode('/', $args);
        $fullpath = '/'.$context->id.'/mod_referentiel/'.$filearea.'/'.$docid.'/'.$relativepath;

        $fs = get_file_storage();

        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }

        send_stored_file($file, 0, 0, true); // download MUST be forced - security!
}

//------------------
function referentiel_upload_document($mform, $referentiel_id){
// Traite le formulaire de saisie d'un fichier
// mise à jour des tables document ou consigne
global $CFG, $USER, $DB, $OUTPUT;

    // DEBUG
    // echo "<br />DEBUG :: ./mod/referentiel/lib.php :: upload_moodle2.php :: 1815 :: referentiel_upload_document\n";
    //print_object($mform);

    $viewurl=new moodle_url('/mod/referentiel/view.php', array('d'=>$referentiel_id));

    $retour_url='';

    if ($formdata = $mform->get_data()) {
        // DEBUG
        // echo "<br />DEBUG :: lib.php :: 5682 :: referentiel_upload_document\n";
        // print_object($formdata);

        // documents activites et consignes des tâches
        $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie', 'archive');

        if (empty($formdata->filearea) || !in_array($formdata->filearea, $fileareas)) {
            return false;
        }


        // DEBUG
        // print_object($document);
        // echo "<br />";
        if ($formdata->filearea=='document'){
            // gestion d'un fichier à la fois
            $document = new object();
            $document->url_document='';
            $document->type_document='';
            $document->description_document='';
            $document->ref_activite='';
            $document->cible_document=1;
            $document->etiquette_document='';
            $document->timestamp=time();

            $docid = $DB->insert_record("referentiel_document", $document);
            $retour_url=new moodle_url('/mod/referentiel/activite.php', array('d'=>$referentiel_id, 'userid'=>$formdata->userid, 'activite_id'=>$formdata->activiteid, 'mailnow' => $formdata->mailnow, 'mode' => 'listactivityall', 'select_acc' => 0));
        }
        else if ($formdata->filearea=='consigne'){
            // gestion d'un fichier à la fois
            $document = new object();
            $document->url_consigne='';
            $document->type_consigne='';
            $document->description_consigne='';
            $document->ref_task='';
            $document->cible_consigne=1;
            $document->etiquette_consigne='';
            $document->timestamp=time();
            
            $docid = $DB->insert_record("referentiel_consigne", $document);
            $retour_url= new moodle_url('/mod/referentiel/task.php', array('d'=>$referentiel_id, 'mode' => 'listtasksingle', 'select_acc' => 0));
        }


        $newfilename = '';
        $fullpath = '';

        $fs = get_file_storage();
        // suppression du fichier existant ?   NON
        // $fs->delete_area_files($formdata->contextid, 'mod_referentiel', $formdata->filearea, $docid);

        if ($newfilename = $mform->get_new_filename('referentiel_file')) {
            $file = $mform->save_stored_file('referentiel_file', $formdata->contextid,
                'mod_referentiel',$formdata->filearea,$docid,'/', $newfilename);
            // DEBUG
            // echo "<br />DEBUG :: lib.php 5730 :: $newfilename\n";
            // print_object($file);

            $fullpath = "/$formdata->contextid/mod_referentiel/$formdata->filearea/$docid/$newfilename";
            $link = new moodle_url($CFG->wwwroot.'/pluginfile.php'.$fullpath);
            /*
            // DEBUG
            $messagetext = file_rewrite_pluginfile_urls($newfilename, 'pluginfile.php', $formdata->contextid, 'mod_referentiel', '$formdata->filearea', $docid);

            echo "<br />DEBUG :: 1900 :: $link<br />\n";
            echo "<br />Message:: $messagetext<br />\n";
            */

        }
        else if (!empty($formdata->url)){
            $fullpath = $formdata->url;
        }

        if (!empty($fullpath)){

            if (!empty($docid) && ($formdata->filearea=='document') && !empty($formdata->activiteid)){
                $document = new object();
                $document->id=$docid;
                $document->url_document=$fullpath;
                $document->type_document=$formdata->type;
                if (empty($formdata->description)){
                    $document->description_document=get_string('url', 'referentiel');
                }
                else{
                    $document->description_document=$formdata->description;
                }
                $document->ref_activite=$formdata->activiteid;
                $document->cible_document=$formdata->cible;
                $document->timestamp=time();
            /*
            echo "<br />DOCID : $docid\n";
            echo "<br />URL : $formdata->url\n";
            echo "<br />FILEAREA : $formdata->filearea\n";
            echo "<br />FULLPATH : $fullpath\n";
            echo "<br />DESCRIPTION : $formdata->description\n";
            echo "<br />ACTIVITE ID  : $formdata->activiteid\n";
            echo "<br />Etiquette  : $formdata->etiquette\n";
            */

                if (!empty($formdata->etiquette)){
                    $document->etiquette_document=$formdata->etiquette;
                }
                else{
                    if (!empty($newfilename)){
                        $document->etiquette_document=$newfilename;
                    }
                    else{
                        $document->etiquette_document=get_string('url', 'referentiel');
                    }
                }

                // print_object($document);
                // exit;
                // Modif JF 2013/02/02
                if ($DB->update_record("referentiel_document", $document)){
                    $activite = $DB->get_record('referentiel_activite', array('id' => $document->ref_activite));
                    if ($activite){
                        if ($USER->id==$activite->userid){
                            $ok=$DB->set_field('referentiel_activite','date_modif_student',time(),array('id'=>$activite->id));
                        }
                        else{
                            $ok=$DB->set_field('referentiel_activite','date_modif',time(), array('id'=>$activite->id));
                        }
                    }
                }
            }
            else if (!empty($docid) && ($formdata->filearea=='consigne')  && !empty($formdata->activiteid)){
                $document = new object();
                $document->id=$docid;
                $document->url_consigne=$fullpath;
                $document->type_consigne=$formdata->type;
                if (empty($formdata->description)){
                    $document->description_consigne=get_string('url', 'referentiel');
                }
                else{
                    $document->description_consigne=$formdata->description;
                }
                $document->ref_task=$formdata->activiteid;
                $document->cible_consigne=$formdata->cible;
                $document->timestamp=time();
                if (!empty($formdata->etiquette)){
                    $document->etiquette_consigne=$formdata->etiquette;
                }
                else{
                    if (!empty($newfilename)){
                        $document->etiquette_consigne=$newfilename;
                    }
                    else {
                        $document->etiquette_consigne=get_string('url', 'referentiel');
                    }
                }
                
                if ($DB->update_record("referentiel_consigne", $document)){
                    $task = $DB->get_record('referentiel_task', array('id' => $document->ref_task));
                    if ($task){
                        $ok=$DB->set_field('referentiel_task','date_modif',time(), array('id'=>$task->id));
                    }
                }
            }
            else{
                //
                echo  '<div align="center"><a href="'.$link.'" target="_blank">'.$link.'</a>'."</div>\n";
            }
        }
        if (!empty($retour_url)){
            redirect($retour_url, get_string('uploadedfile'));
        }
    }
    redirect($viewurl);
}

// ------------------
function referentiel_get_area_files($contextid, $filearea, $docid){
// retourne la liste des liens vers des fichiers stockes dans le filearea
global $CFG;
    // fileareas autorisees
    $ok=false;
    $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie', 'archive');
    if (!in_array($filearea, $fileareas)) {
        return false;
    }

    $strfilename=get_string('filename', 'referentiel');
    $strfilesize=get_string('filesize', 'referentiel');
    $strtimecreated=get_string('timecreated', 'referentiel');
    $strtimemodified=get_string('timemodified', 'referentiel');
    $strmimetype=get_string('mimetype', 'referentiel');
    $strurl=get_string('url');

    $table = new html_table();

	$table->head  = array ($strfilename, $strfilesize, $strtimecreated, $strtimemodified, $strmimetype);
    $table->align = array ("center", "left", "left", "left");

    $fs = get_file_storage();
    if ($files = $fs->get_area_files($contextid, 'mod_referentiel', $filearea, $docid, "timemodified", false)) {
         foreach ($files as $file) {
            // print_object($file);
            $filesize = $file->get_filesize();
            $filename = $file->get_filename();
            $mimetype = $file->get_mimetype();
            $filepath = $file->get_filepath();
            $fullpath ='/'.$contextid.'/mod_referentiel/'.$filearea.'/'.$docid.$filepath.$filename;

            $timecreated =  userdate($file->get_timecreated(),"%Y/%m/%d-%H:%M",99,false);
            $timemodified = userdate($file->get_timemodified(),"%Y/%m/%d-%H:%M",99,false);
            $link= new moodle_url($CFG->wwwroot.'/pluginfile.php'.$fullpath);
            $url='<a href="'.$link.'" target="_blank">'.$filename.'</a><br />'."\n";
            $table->data[] = array ($url, $filesize, $timecreated, $timemodified, $mimetype);
            $ok=true;
        }
    }
    if ($ok){
        return html_writer::table($table);
    }
    else{
        return '';
    }
}

// ------------------
function referentiel_get_manage_files($contextid, $filearea, $docid, $titre, $appli){
// retourne la liste des liens vers des fichiers stockes dans le filearea
// propose la suppression
global $CFG;
global $OUTPUT;
    $total_size=0;
    $nfile=0;
    // fileareas autorisees
    $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie', 'archive');
    if (!in_array($filearea, $fileareas)) {
        return false;
    }
    $strfilepath='filepath';
    $strfilename=get_string('filename', 'referentiel');
    $strfilesize=get_string('filesize', 'referentiel');
    $strtimecreated=get_string('timecreated', 'referentiel');
    $strtimemodified=get_string('timemodified', 'referentiel');
    $strmimetype=get_string('mimetype', 'referentiel');
    $strmenu=get_string('delete');

    $strurl=get_string('url');


    $fs = get_file_storage();
    if ($files = $fs->get_area_files($contextid, 'mod_referentiel', $filearea, $docid, "timemodified", false)) {
        $table = new html_table();
	    $table->head  = array ($strfilename, $strfilesize, $strtimecreated, $strtimemodified, $strmimetype, $strmenu);
        $table->align = array ("center", "left", "left", "left", "center");

        foreach ($files as $file) {
            // print_object($file);
            $filesize = $file->get_filesize();
            $filename = $file->get_filename();
            $mimetype = $file->get_mimetype();
            $filepath = $file->get_filepath();
            $fullpath ='/'.$contextid.'/mod_referentiel/'.$filearea.'/'.$docid.$filepath.$filename;
            // echo "<br />FULPATH :: $fullpath \n";
            $timecreated =  userdate($file->get_timecreated(),"%Y/%m/%d-%H:%M",99,false);
            $timemodified = userdate($file->get_timemodified(),"%Y/%m/%d-%H:%M",99,false);

            $link= new moodle_url($CFG->wwwroot.'/pluginfile.php'.$fullpath);
            $url='<a href="'.$link.'" target="_blank">'.$filename.'</a><br />'."\n";

            $delete_link='<input type="checkbox" name="deletefile[]"  value="'.$fullpath.'" />'."\n";
            $table->data[] = array ($url, display_size($filesize), $timecreated, $timemodified, $mimetype, $delete_link);
            $total_size+=$filesize;
            $nfile++;
        }
        $table->data[] = array (get_string('nbfile', 'referentiel',$nfile), get_string('totalsize', 'referentiel', display_size($total_size)),'','','','');

        echo $OUTPUT->box_start('generalbox  boxaligncenter');
        echo '<div align="center">'."\n";
        echo '<h3>'.$titre.'</h3>'."\n";
        echo '<form method="post" action="'.$appli.'">'."\n";
        echo html_writer::table($table);
        echo "\n".'<input type="hidden" name="sesskey" value="'.sesskey().'" />'."\n";
        echo '<input type="submit" value="'.get_string('delete').'" />'."\n";
        echo '</form>'."\n";
        echo '</div>'."\n";
        echo $OUTPUT->box_end();
    }
}

// ------------------
function referentiel_get_a_file($filename, $contextid, $filearea, $itemid=0){
// retourne un fichier
// NON TESTE
global $CFG;
    // fileareas autorisees
    $fileareas = array('referentiel', 'document', 'consigne', 'activite', 'task', 'certificat', 'scolarite', 'pedagogie', 'archive');
    if (!in_array($filearea, $fileareas)) {
        return false;
    }

    $strfilename=get_string('filename', 'referentiel');
    $strfilesize=get_string('filesize', 'referentiel');
    $strtimecreated=get_string('timecreated', 'referentiel');
    $strtimemodified=get_string('timemodified', 'referentiel');
    $strmimetype=get_string('mimetype', 'referentiel');
    $strurl=get_string('url');

    $table = new html_table();

	$table->head  = array ($strfilename, $strfilesize, $strtimecreated, $strtimemodified, $strmimetype);
    $table->align = array ("center", "left", "left", "left");
    $fs = get_file_storage();
    $file = $fs->get_file($contextid, 'mod_referentiel', $filearea, $itemid,'/', $filename);
    if ($file) {
        // DEBUG
        // echo "<br />DEBUG :: 220 :: $filename\n";
        // print_object($file);
        // echo "<br />CONTENU\n";
        // $contents = $file->get_content();
        // echo htmlspecialchars($contents);
        $filesize = $file->get_filesize();
        $filename = $file->get_filename();
        $mimetype = $file->get_mimetype();
        $filepath = $file->get_filepath();
        $fullpath ='/'.$contextid.'/mod_referentiel/'.$filearea.'/'.$docid.$filepath.$filename;

        $timecreated =  userdate($file->get_timecreated(),"%Y/%m/%d-%H:%M",99,false);
        $timemodified = userdate($file->get_timemodified(),"%Y/%m/%d-%H:%M",99,false);
        $link= new moodle_url($CFG->wwwroot.'/pluginfile.php'.$fullpath);
        $url='<a href="'.$link.'" target="_blank">'.$filename.'</a><br />'."\n";
        $table->data[] = array ($url, $filesize, $timecreated, $timemodified, $mimetype);
    }

    echo html_writer::table($table);
}

/**
 * This function wil delete a file
 * fullpath of the form /contextid/mod_referentiel/filearea/itemid.path.filename
 * path  : any path beginning and ending in / like '/' or '/rep1/rep2/'
 * @fullpath string
 * @return nothing
 */
 
// ---------------------------------
function referentiel_delete_a_file($fullpath){
// supprime un fichier
// NON TESTE
// cas 0 : $fullpath de la forme "jf44.png";
// cas 1 : $fullpath de la forme "/30/mod_referentiel/referentiel/0/rep1/rep2/jf44.png"
// cas 2 : $fullpath de la forme "/51/mod_referentiel/referentiel/12/jf44.png"
global $CFG;

    // initialisation par defaut
    $contextid=0;
    $component='mod_referentiel';
    $filearea='referentiel';
    $itemid=0;
    $path='/';
    $filename=$fullpath;

    // Traitement de $fullpath
    if ($fullpath && preg_match('/\//', $fullpath)){
        $t_fullpath=explode('/',$fullpath,6);
        if (!empty($t_fullpath) && empty($t_fullpath[0])){
            $garbage=array_shift($t_fullpath);
        }
        if (!empty($t_fullpath)){
            list($contextid, $component, $filearea, $itemid, $path )  = $t_fullpath;
            if ($path){
                if (preg_match('/\//', $path)){
                    $filename=substr($path, strrpos($path, '/')+1);
                    $path='/'.substr($path, 0, strrpos($path, '/')+1);
                }
                else{
                    $filename=$path;
                    $path='/';
                }
            }
        }
    }
    
    // echo "<br />DEBUG :: lib.php :: Ligne 5918 ::<br /> $contextid, $component, $filearea, $itemid, $path, $filename\n";
    // devrait afficher cas 0  :: 0, mod_referentiel, referentiel, 0, /, jf44.png
    // devrait afficher cas 1  :: 30, mod_referentiel, referentiel, 0, /rep1/rep2/, jf44.png
    // devrait afficher cas 2  :: 51, mod_referentiel, referentiel, 12, /, jf44.png

    require_once($CFG->libdir.'/filelib.php');
    $fs = get_file_storage();

    // Get file
    $file = $fs->get_file($contextid, $component, $filearea, $itemid, $path, $filename);

    // Delete it if it exists
    if ($file) {
        $file->delete();
    }

}

// ----------------------------------------------------
function referentiel_activite_a_suivre($activite, $delai){
global $USER;

    if (empty($activite->approved)){
            // echo "<br>Test: DELAI: $delai secondes\n";
            // print_object($activite);
            // retourne une valeur de couleur si
            if (!empty($activite->date_modif_student)
                    &&
                    ($activite->date_modif < $activite->date_modif_student)
                    &&
                    ($activite->date_modif_student + $delai < time())
                ){

                return 1;
            }
    }
    return 0;
}

/**
 * This function returns records from table referentiel_activite
 *
 * @param id reference instance
 * @param delai : time to backtrack
 * @return objects
 * @todo Finish documenting this function
 **/
function referentiel_get_activites_instance_a_suivre($instance_id, $delai){
global $DB;
	if (!empty($instance_id)){
        $date= time() - $delai;
		$params = array("refid" => $instance_id, 'date' => $date);
        $sql="SELECT * FROM {referentiel_activite} WHERE ref_instance=:refid
 AND (approved=0)
 AND (date_modif_student<>0)
 AND (date_modif_student<:date)
 AND (date_modif<date_modif_student)
 ORDER BY userid ASC, date_creation DESC ";
        return $DB->get_records_sql($sql, $params);
    }
	return NULL;
}



?>
