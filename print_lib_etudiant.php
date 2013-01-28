<?php  // $Id:  print_lib_etudiant.php,v 1.0 2008/04/29 00:00:00 jfruitet Exp $
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
 * Print Library of functions for etudiant of module referentiel
 * 
 * @author jfruitet
 * @version $Id: lib.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
 * @version $Id: lib.php,v 1.0 2008/04/29 00:00:00 jfruitet Exp $
 * @package referentiel
 **/


require_once("lib.php");


// DEBUT DE Etudiant


// Affiche une etudiant 
// *****************************************************************
// input @param a $record_e   of etudiant                          *
// output string                                                   *
// *****************************************************************

// --------------------------------------------
function referentiel_info_etudiant_user($userid, $appli){
	$s='';
	// echo $userid."<br />\n";
	if ($userid){
		$record = referentiel_get_etudiant_user($userid);
		// print_r($record);
		if ($record){
			$s.= '<tr bgcolor="white">';
			$s.= '<td>'.referentiel_get_user_login($userid);
            $s.= '</td><td>'.referentiel_get_user_info($record->userid);
		    $s.= '</td><td>';
            if ($record->num_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->num_etudiant);
            }
		    $s.= '</td><td>';
            if ($record->ddn_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->ddn_etudiant);
            }
	    	$s.= '</td><td>';
            if ($record->lieu_naissance=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->lieu_naissance);
            }
		    $s.= '</td><td>';
            if ($record->departement_naissance=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->departement_naissance);
            }
		    $s.= '</td><td>';
            if ($record->adresse_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->adresse_etudiant);
            }
	    	$s.= '</td><td>';
			$s.=referentiel_select_etablissement($userid, $record->ref_etablissement, $appli);
			$s.='</td></tr>';
			$s.= "\n";
			return $s;
		}
	}
	return "";
}

// --------------------------------------------
function referentiel_print_etudiant($userid, $appli){
	$s='';
	// echo $userid."<br />\n";
	if ($userid){
		$s = referentiel_info_etudiant_user($userid, $appli);
		// print_r($record);
		if ($s==''){
			// creer 
			$id = referentiel_add_etudiant_user($userid);
			// recuperer
			return referentiel_info_etudiant_user($userid, $appli);
		}
		else{
			return $s;
		}
	}
	return "";
}

// ----------------------------------------------------
function referentiel_print_etudiant_2($userid, $referentiel_id, $context, $appli){
//	fusion de referentiel_print_etudiant($record) et de referentiel_menu_etudiant($context, $record->id, $referentiel_instance->id, $record->approved);
global $CFG;
global $USER;
global $OUTPUT;
	$s="";
	if ($userid){
		$record = referentiel_get_etudiant_user($userid);
		if ($record){
			$s.= '<tr bgcolor="white">';
		    $s.= '<td>'.referentiel_get_user_login($userid);
		    $s.= '</td><td>'.referentiel_get_user_info($record->userid);
		    $s.= '</td><td>';
            if ($record->num_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->num_etudiant);
            }
		    $s.= '</td><td>';
            if ($record->ddn_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->ddn_etudiant);
            }
	    	$s.= '</td><td>';
            if ($record->lieu_naissance=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->lieu_naissance);
            }
		    $s.= '</td><td>';
            if ($record->departement_naissance=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->departement_naissance);
            }
		    $s.= '</td><td>';
            if ($record->adresse_etudiant=='l_inconnu'){
                $s.=get_string('l_inconnu', 'referentiel');
            }
            else{
                $s.=stripslashes($record->adresse_etudiant);
            }

    		$s.= '</td><td>';
			$s.=referentiel_select_etablissement($record->userid, $record->ref_etablissement, $appli);
			$s.='</td><td>';
			// menu
			if (has_capability('mod/referentiel:managecertif', $context) 
				or ($USER->id==$record->userid)) {
	        	$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel_id.'&amp;userid='.$record->userid.'&amp;mode=updateetudiant&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
			}
			if (has_capability('mod/referentiel:managecertif', $context)){
	    		$s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel_id.'&amp;userid='.$record->userid.'&amp;mode=deleteetudiant&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
			}
			$s.='</td></tr>'."\n";
		}
	}
	return $s;
}




// *****************************************************************
// input @param id_referentiel   of etudiant                       *
// output null                                                     *
// *****************************************************************
// Affiche les etudiants de ce referentiel
function referentiel_liste_tous_etudiants($referentiel_instance){
//
	$records = referentiel_get_course_users($referentiel_instance);
	if (!$records){
		error(get_string('noetudiant','referentiel'), "etudiant.php?d=".$referentiel_instance->id."&amp;mode=add");
	}
	else {
    	// afficher
		// DEBUG
		// echo "<br/>DEBUG ::<br />\n";
		// print_r($records);
		foreach ($records as $record){
			referentiel_print_etudiant($record->id, "etudiant.php?d=".$referentiel_instance->id."&amp;mode=selectetab");
		}
	}
}

// *****************************************************************
// input @param context                                            *
// input @param id_referentiel                                     *
// input @param id_user                                            *
// output string                                                   *
// *****************************************************************
// Affiche le menu EDITER et SUPPRIMER etudiants de ce referentiel
function referentiel_menu_etudiant($context, $referentiel_id, $userid){
	global $CFG;
	global $USER;
	global $OUTPUT;
	$s="";
	
	if (has_capability('mod/referentiel:managecertif', $context) 
		or ($USER->id==$userid)) {
        $s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel_id.'&amp;userid='.$userid.'&amp;mode=updateetudiant&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
	}
	if (has_capability('mod/referentiel:managecertif', $context)){
	    $s.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel_id.'&amp;userid='.$userid.'&amp;mode=deleteetudiant&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
	}
	return $s;
}


/************************************************************************
 * takes a list of records, the current referentiel, a search string,   *
 * and mode to display                                                  *
 * input @param string  $mode                                           *
 *       @param object $referentiel_instance                            *
 *       @param int $userid_filtre                                      *
 *       @param array $gusers                                           *
 *       @param int $page                                               *
 * output null                                                         *
 ************************************************************************/
function referentiel_print_liste_etudiants($initiale, $userids, $mode, $referentiel_instance, $userid_filtre=0, $gusers=NULL, $select_acc) {
    global $CFG;
    global $USER;
    global $DB;
    static $istutor=false;
    static $isteacher=false;
    static $isauthor=false;
    static $iseditor=false;

    if (!empty($referentiel_instance)){
        $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
        $course = $DB->get_record("course", array("id" => "$cm->course"));

        if (empty($cm) or empty($course)){
            print_error('REFERENTIEL_ERROR 5 :: print_lib_etudiant.php :: You cannot call this script in that way');
        }
		
    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}

		
        $records = array();
        $iseditor = has_capability('mod/referentiel:writereferentiel', $context);
        $isteacher = has_capability('mod/referentiel:approve', $context)&& !$iseditor;
        $istutor = has_capability('mod/referentiel:comment', $context) && !$iseditor  && !$isteacher;
        $isauthor = has_capability('mod/referentiel:write', $context) && !$iseditor  && !$isteacher  && !$istutor;

        // Creer les enregistrements pour les etudiants
		$record_id_users  = referentiel_get_students_course($course->id,0,0);  // seulement les stagiaires
		// echo "<br />DEBUG :: print_lib_etudiant.php :: 219 :: RECORD_ID_USERS<br />\n";
		// print_r($record_id_users);
		// echo "<br />\n";
		
		if ($record_id_users){
			foreach ($record_id_users as $un_user_id){
				// l'enregistrement existe-t-il ?
				// echo "<br />".$un_user_id->userid."\n";
				$re = $DB->get_record("referentiel_etudiant", array("userid" => "$un_user_id->userid"));
				if (!$re) {
            		$id_etudiant=referentiel_add_etudiant_user($un_user_id->userid);
        		}
			}
		}
		
		// selection  sur les utilisateurs ?
		if ($isteacher || $iseditor){
			// cr�er les enrgistrements etudiants si 
			if ($gusers && $record_id_users){ // liste des utilisateurs du groupe courant
				// echo "<br />DEBUG :: print_lib_activite.php :: 740 :: GUSERS<br />\n";
				// print_object($gusers);
				// echo "<br />\n";
				$record_users  = array_intersect($gusers, array_keys($record_id_users));
				// $record_users  = array_intersect_assoc($record_id_users, array_keys($gusers));
				// echo "<br />DEBUG :: print_lib_etudiant.php:: 242 :: RECORD_USERS<br />\n";
				// print_r($record_users  );
				// echo "<br />\n";
				// recopier 
				
				$record_id_users=array();
				foreach ($record_users  as $record_id){
                        $a_obj=new stdClass();
                        $a_obj->userid=$record_id;
                        $record_id_users[]=$a_obj;
				}
			}
			$boite_selection=referentiel_select_users_etudiant($record_id_users, $userid_filtre, $initiale, $select_acc);
		}
		else $boite_selection="";
		
		// filtres
		if ((!$isteacher) && (!$iseditor)){
			$userid_filtre=$USER->id; 
		}
		// recuperer les utilisateurs filtres
		
		$record_id_users = referentiel_get_students_course($course->id, $userid_filtre);
		if ($gusers && $record_id_users){
			$record_users  = array_intersect($gusers, array_keys($record_id_users));
			// recopier 
			$record_id_users=array();
			foreach ($record_users  as $record_id){
                        $a_obj=new stdClass();
                        $a_obj->userid=$record_id;
                        $record_id_users[]=$a_obj;
			}
		}

		// ALPHABETIQUE
		if (!empty($userids)){
            $t_users_select=explode('_', $userids);
            $record_id_users=array();
            foreach($t_users_select as $userid){
                $a_obj=new stdClass();
                $a_obj->userid=$userid;
                $record_id_users[]=$a_obj;
            }

            // DEBUG
            /*
            echo "<br />DEBUG :: print_lib_activite.php :: 2386<br />USERIDS : $userids<br />\n";
            print_r($t_users_select);
            echo "<br />\n";
            print_r($record_id_users);
            exit;
            */
        }
		elseif ((($userid_filtre==$USER->id) || ($userid_filtre==0))
            && ($isteacher || $iseditor|| $istutor)){
			// Ajouter l'utilisateur courant pour qu'il puisse voir ses propres donnees
                $a_obj=new stdClass();
                $a_obj->userid=$USER->id;
                $record_id_users[]=$a_obj;
		}



		if ($record_id_users){
			echo $boite_selection;
			echo '<table class="certificat">
<tr><th>'.get_string('userid','referentiel').'</th><th>'.get_string('nom_prenom','referentiel').'</th><th>'.get_string('num_etudiant','referentiel').'</th><th>'.get_string('ddn_etudiant','referentiel').'</th><th>'.get_string('lieu_naissance','referentiel').'</th><th>'.get_string('departement_naissance','referentiel').'</th><th>'.get_string('adresse_etudiant','referentiel').'</th><th>'.get_string('ref_etablissement','referentiel').'</th></tr>'."\n";
		    foreach ($record_id_users as $record) {   // afficher la liste d'etudiant
				// Afficher 
				// print_r($record);
				if ($record->userid){
					$isauthor = ($USER->id==$record->userid);
					if ($isauthor || $isteacher || $iseditor) {
						// echo referentiel_print_etudiant($record->userid, $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel_instance->id.'&amp;mode=selectetab&amp;sesskey='.sesskey());
						// echo '<tr><td colspan="7" align="center">'.referentiel_menu_etudiant($context, $referentiel_instance->id, $record->userid).'</td></tr>'."\n";
						echo referentiel_print_etudiant_2($record->userid, $referentiel_instance->id, $context, "etudiant.php?d=".$referentiel_instance->id."&amp;mode=selectetab");
    				}
				}
			}
			echo '</table><br /><br />'."\n";
		}
	}
}



/************************************************************************
 * takes a list of records, a search string,                            *
 * input @param array $records of users                                 *
 * input @param string $initiales                                       *
 * input @param int                                                     *
 * output null                                                          *
 ************************************************************************/
function referentiel_select_users_etudiant($record_users, $userid=0, $initiales='', $select_acc=0, $select_all=0){

global $cm;
global $course;
$maxcol=MAXBOITESSELECTION;
$s="";
$t_users=array();
$mode="listetudiant";
$appli="etudiant.php";

	if ($record_users){

		// $s.='<option value="0" selected="selected">'.get_string('choisir', 'referentiel').'</option>'."\n";
        foreach ($record_users as $record_u) {   // liste d'id users
			//
			$firstname= referentiel_get_user_prenom($record_u->userid);
			$lastname = referentiel_get_user_nom($record_u->userid);
            $initiale = mb_strtoupper(substr($lastname,0,1),'UTF-8');

			$t_users[]= array('id' => $record_u->userid, 'lastname' => $lastname, 'firstname' => $firstname, 'initiale' => $initiale);
			$t_users_id[]= $record_u->userid;

			$t_users_lastname[] = $lastname;
			$t_users_firstname[]= $firstname;
		}
		array_multisort($t_users_lastname, SORT_ASC, $t_users_firstname, SORT_ASC, $t_users);
		//
		// echo "<br />Debug :: print_lib_activite.php :: 1419 ::<br />\n";
		// print_r($t_users);
		// exit;
        $alpha  = explode(',', get_string('alphabet', 'referentiel'));
        /*
        print_r($alpha);
		echo "<br /><br />\n";
        */
		foreach ($t_users as $an_user){
            if (!empty($an_user)){
                // print_object($an_user);
                $t_alphabetique[$an_user['initiale']][]=$an_user['id'].",".$an_user['firstname'].",".$an_user['lastname'];
                if (!isset($t_id_alphabetique[$an_user['initiale']])){
                    $t_id_alphabetique[$an_user['initiale']]=$an_user['id'];
                }
                else{
                    $t_id_alphabetique[$an_user['initiale']].='_'.$an_user['id'];
                }
            }
        }

        // Should use this variable so that we don't break stuff every time a variable is added or changed.
        //http://localhost/moodle/mod/referentiel/certificat.php?d=1&amp;mode=editcertif&amp;sesskey=yvj43gJOTd?id=2&amp;userid=6&amp;select_acc=0&amp;action=selectuser&amp;initiale=D&amp;userids=7&amp;select_all=0&amp;mode=editcertif&amp;course=2&amp;sesskey=yvj43gJOTd

        $baseurl = $appli.'?id='.$cm->id.'&amp;select_acc='.$select_acc.'&amp;action=selectuser&amp;initiale=';
        $baseurl1 ='&amp;userids=';
        $baseurl2 = '&amp;select_all='.$select_all.'&amp;mode='.$mode.'&amp;course='.$course->id.'&amp;sesskey='.sesskey();
        if (!empty($userid)){
            $baseurl3 = '&amp;userid='.$userid;
        }
        else{
            $baseurl3 = '';
        }
        // http://localhost/moodle/mod/referentiel/certificat.php?id=2&amp;select_acc=0&amp;action=selectuser&amp;initiale=A&amp;userids=6&amp;select_all=0&amp;mode=listcertif&amp;course=2&amp;sesskey=yvj43gJOTd
        // http://localhost/moodle/mod/referentiel/certificat.php?d=1&amp;mode=editcertif&amp;sesskey=yvj43gJOTd?id=2&amp;select_acc=0&amp;action=selectuser&amp;initiale=A&amp;userids=6&amp;select_all=0&amp;mode=editcertif&amp;course=2&amp;sesskey=yvj43gJOTd
        // http://localhost/moodle/mod/referentiel/certificat.php?d=1&amp;mode=editcertif&amp;sesskey=yvj43gJOTd?id=2&amp;select_acc=0&amp;action=selectuser&amp;initiale=&amp;userids=&amp;select_all=0&amp;mode=editcertif&amp;course=2&amp;sesskey=yvj43gJOTd

        // selection alphabetique
        $s.='<div align="center">'."\n";

        $s.= '<a class="select" href="'.$baseurl.''.$baseurl1.''.$baseurl2.$baseurl3.'">'.get_string('tous', 'referentiel').'</a> '."\n";
        foreach ($alpha as $letter){
            if (!empty($t_alphabetique[$letter])){
                $s.= '<a class="select" href="'.$baseurl.$letter.$baseurl1.$t_id_alphabetique[$letter].$baseurl2.$baseurl3.'">'.$letter.'</a> '."\n";
            }
            else{
                $s.=$letter.' '."\n";
            }
        }
        $s.='</div><br />'."\n";

        // Formulaire de selection individuelle
		$n=count($t_users);
        if ($n>=18){
			$l=$maxcol;
			$c=(int) ($n / $l);
		}
        elseif ($n>=6){
			$l=$maxcol-2;
			$c=(int) ($n / $l);
        }
		else{
			$l=1;
			$c=(int) ($n);
		}

		if ($c*$l==$n){
            $reste=false;
        }
        else{
            $reste=true;
        }
		$i=0;

        $s.='<div align="center">'."\n";
		$s.='<table class="selection">'."\n";
		$s.='<tr>';

		for ($j=0; $j<$l; $j++){
			$s.='<td>';
			if (!empty($userid)){
                $s.="\n".'<form name="form" method="post" action="'.$appli.'?id='.$cm->id.'&amp;userid='.$userid.'&amp;select_acc='.$select_acc.'&amp;action=selectuser">'."\n";
            }
            else{
                $s.="\n".'<form name="form" method="post" action="'.$appli.'?id='.$cm->id.'&amp;select_acc='.$select_acc.'&amp;action=selectuser">'."\n";
            }
			$s.='<select name="userid" id="userid" size="4">'."\n";

            if ($j<$l-1){
                if (($userid=='') || ($userid==0)){
                    $s.='<option value="0" selected="selected">'.get_string('choisir', 'referentiel').'</option>'."\n";
                }
                else{
                    $s.='<option value="0">'.get_string('choisir', 'referentiel').'</option>'."\n";
                }
			}
			else{
                if ($reste){
                    if (($userid=='') || ($userid==0)){
	   	     		    $s.='<option value="0" selected="selected">'.get_string('choisir', 'referentiel').'</option>'."\n";
                    }
                    else{
                        $s.='<option value="0">'.get_string('choisir', 'referentiel').'</option>'."\n";
                    }
                }
                else{
                    if (($userid=='') || ($userid==0)){
                        $s.='<option value="0" selected="selected">'.get_string('tous', 'referentiel').'</option>'."\n";
                    }
                    else{
                        $s.='<option value="0">'.get_string('tous', 'referentiel').'</option>'."\n";
                    }
                }
            }

            for ($k=0; $k<$c; $k++){
                if ($userid==$t_users[$i]['id']){
                    $s.='<option value="'.$t_users[$i]['id'].'" selected="selected">'.$t_users[$i]['lastname'].' '.$t_users[$i]['firstname'].'</option>'."\n";
            	}
	       		else{
                    $s.='<option value="'.$t_users[$i]['id'].'">'.$t_users[$i]['lastname'].' '.$t_users[$i]['firstname'].'</option>'."\n";
                }

                $i++;
            }
            $s.='</select>'."\n";
		    $s.='<br /><input type="submit" value="'.get_string('select', 'referentiel').'" />'."\n";;
		    $s.='
<!-- tous les certificats -->
<input type="hidden" name="select_all" value="'.$select_all.'" />
<!-- accompagnement -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />
</form>'."\n";

            $s.='</td>';
        }

		if ($i<$n){
			$s.='<td>';
			if (!empty($userid)){
                $s.="\n".'<form name="form" method="post" action="'.$appli.'?id='.$cm->id.'&amp;userid='.$userid.'&amp;select_acc='.$select_acc.'&amp;action=selectuser">'."\n";
            }
            else{
                $s.="\n".'<form name="form" method="post" action="'.$appli.'?id='.$cm->id.'&amp;select_acc='.$select_acc.'&amp;action=selectuser">'."\n";
            }

			$s.='<select name="userid" id="userid" size="4">'."\n";
			if (($userid=='') || ($userid==0)){
				$s.='<option value="0" selected="selected">'.get_string('tous', 'referentiel').'</option>'."\n";
			}
			else{
				$s.='<option value="0">'.get_string('tous', 'referentiel').'</option>'."\n";
			}

			while ($i <$n){

				if ($userid==$t_users[$i]['id']){
					$s.='<option value="'.$t_users[$i]['id'].'" selected="selected">'.$t_users[$i]['lastname'].' '.$t_users[$i]['firstname'].'</option>'."\n";
				}
				else{
					$s.='<option value="'.$t_users[$i]['id'].'">'.$t_users[$i]['lastname'].' '.$t_users[$i]['firstname'].'</option>'."\n";
				}
				$i++;
			}
			$s.='</select>'."\n";
			$s.='<br /><input type="submit" value="'.get_string('select', 'referentiel').'" />'."\n";;
			$s.='
<!-- tous les certificats -->
<input type="hidden" name="select_all" value="'.$select_all.'" />
<!-- accompagnement -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />
</form>'."\n";
			$s.='</td>';
		}
		$s.='</tr></table>'."\n";
		$s.='</div>'."\n";
	}

	return $s;
}


?>