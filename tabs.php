<?php  // $Id: tabs.php,v 1.24.2.5 2007/09/24 17:15:31 skodak Exp $
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
/// This file to be included so we can assume config.php has already been included.
/// We also assume that $user, $course, $currenttab have been set

//MODIF JF 2012/09/20
// Filtres
$str_filtre='';
if (isset($select_acc) && !empty($data_filtre)){
    $str_filtre='&amp;select_acc='.$select_acc.'&amp;filtre_auteur='.$data_filtre->filtre_auteur.'&amp;filtre_validation='.$data_filtre->filtre_validation.'&amp;filtre_referent='.$data_filtre->filtre_referent.'&amp;filtre_date_modif='.$data_filtre->filtre_date_modif.'&amp;filtre_date_modif_student='.$data_filtre->filtre_date_modif_student;
}

// accompagnement
if (!isset($select_acc)){ 
    $select_acc=0 ;
}

if (empty($referentiel) or empty($course) or empty($cm)) {
       // print_error('You cannot call this script in that way');
		print_error(get_string('erreurscript','referentiel','Erreur01 : tabs.php'));
}

if (!isset($currenttab) || empty($currenttab)) {
    $currenttab = 'referentiel';
}


// Administrateur ou Auteur ?
// $isadmin=referentiel_is_admin($USER->id,$course->id);
$roles=referentiel_roles_in_instance($referentiel->id);
// print_object($roles);
$isadmin=$roles->is_admin;
$isstudent=$roles->is_student;
if (!empty($referentiel_referentiel)){
    $isreferentielauteur=referentiel_is_author($USER->id, $referentiel_referentiel, !$isstudent);
}
else{
    $isreferentielauteur=false;
}

// verifier si l'utilisateur peut ajouter des activites ou modifier son certificat
$edition_autorisee=true;
if ($isstudent){
    $edition_autorisee=!referentiel_certificat_user_is_closed($USER->id, $referentiel_referentiel->id);
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
if (has_capability('mod/referentiel:view', $context)) {
	$row[] = new tabobject('referentiel', $CFG->wwwroot.'/mod/referentiel/view.php?d='.$referentiel->id.'&amp;non_redirection=1'.$str_filtre, get_string('referentiel','referentiel'));
}


if (isloggedin()) {	
    // Accompagnement
    if (has_capability('mod/referentiel:write', $context)) {
		$addstring = get_string('accompagnement', 'referentiel');
        $row[] = new tabobject('menuacc', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$referentiel->id.'&amp;mode=accompagnement'.$str_filtre, $addstring);
	}

	// activites
	if (referentiel_user_can_addactivity($referentiel)) { 
		// took out participation list here!
    	// $addstring = empty($editentry) ? get_string('edit_activity', 'referentiel') : get_string('validation', 'referentiel');
		$addstring = get_string('edit_activity', 'referentiel');
        $row[] = new tabobject('list', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=list'.$str_filtre, $addstring);
    }

	// taches
	if (has_capability('mod/referentiel:addtask', $context) || has_capability('mod/referentiel:viewtask', $context)) {
		// took out participation list here!
    	// $addstring = empty($editentry) ? get_string('edit_activity', 'referentiel') : get_string('validation', 'referentiel');
		$addstring = get_string('tasks', 'referentiel');
        $row[] = new tabobject('task', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=listtasksingle'.$str_filtre, $addstring);
    }


	// gestion des certificats
	if (has_capability('mod/referentiel:write', $context)) {
    	$row[] = new tabobject('certificat', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id.'&amp;mode=listcertif'.$str_filtre, get_string('certificat','referentiel'));
 	}
	

	// scolarite
	$scolarite_locale_visible=referentiel_get_item_configuration('scol', $referentiel->id)==0;
	if (($scolarite_locale_visible	&&  has_capability('mod/referentiel:viewscolarite', $context)) 
   || has_capability('mod/referentiel:managescolarite', $context)) {
		$row[] = new tabobject('scolarite', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc, get_string('scolarite','referentiel'));
		$row[] = new tabobject('pedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc, get_string('formation','referentiel'));
	}

	$tabs[] = $row;
	
    // ACCOMPAGNEMENT

    if (isset($currenttab) && has_capability('mod/referentiel:write', $context)
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
		    $row[] = new tabobject('accompagnement', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=accompagnement', get_string('accompagnement','referentiel'));
		    $row[] = new tabobject('suivi', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=suivi', get_string('repartition','referentiel'));
            if (has_capability('mod/referentiel:managecertif', $context)) {      // r�le enseignant
    		    $row[] = new tabobject('notification', $CFG->wwwroot.'/mod/referentiel/accompagnement.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=notification', get_string('notification','referentiel'));
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
		|| ($currenttab == 'exportactivity')
        ))
    {
		$row  = array();
        $inactive[] = 'list';
		$row[] = new tabobject('listactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=listactivity'.$str_filtre,  get_string('listactivity','referentiel'));
		$row[] = new tabobject('listactivityall', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=listactivityall'.$str_filtre,  get_string('listactivityall','referentiel'));

        if (has_capability('mod/referentiel:addactivity', $context)) {
            if ($edition_autorisee){
                $row[] = new tabobject('addactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=addactivity'.$str_filtre,  get_string('addactivity','referentiel'));
                // Ne fonctionne pas
                // $row[] = new tabobject('addactivity', $CFG->wwwroot.'/mod/referentiel/activite_add_update.php?d='.$referentiel->id.'&amp;mode=addactivity'.$str_filtre,  get_string('addactivity','referentiel'));
            }
        }
        if (!has_capability('mod/referentiel:managecertif', $context)) {      // r?le etudiant : uniquement pour modifier une activite
			if ($mode=='updateactivity'){
				$row[] = new tabobject('updateactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=updateactivity'.$str_filtre,  get_string('updateactivity','referentiel'));
			}
        }
        else {
            $row[] = new tabobject('updateactivity', $CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel->id.'&amp;mode=updateactivity'.$str_filtre, get_string('updateactivity','referentiel'));
        }
        if (has_capability('mod/referentiel:export', $context)) {
			$row[] = new tabobject('exportactivity', $CFG->wwwroot.'/mod/referentiel/export_activite.php?d='.$referentiel->id.'&amp;mode=exportactivity'.$str_filtre, get_string('export','referentiel'));
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
		))
    {
		$row  = array();
        $inactive[] = 'task';
		if (has_capability('mod/referentiel:viewtask', $context)) { 
			$row[] = new tabobject('listtask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=listtask'.$str_filtre,  get_string('listtask','referentiel'));
			$row[] = new tabobject('listtasksingle', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=listtasksingle'.$str_filtre,  get_string('listtasksingle','referentiel'));
		}
		/*
		// inutile
		if (has_capability('mod/referentiel:selecttask', $context)) { 
			$row[] = new tabobject('selecttask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=selecttask',  get_string('selecttask','referentiel'));
		}
		*/
	    if (has_capability('mod/referentiel:addtask', $context)) {
			$row[] = new tabobject('addtask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=addtask'.$str_filtre,  get_string('addtask','referentiel'));
			$row[] = new tabobject('updatetask', $CFG->wwwroot.'/mod/referentiel/task.php?d='.$referentiel->id.'&amp;mode=updatetask'.$str_filtre,  get_string('updatetask','referentiel'));
		}
		
		// IMPORT a faire
		
		if (has_capability('mod/referentiel:import', $context)) {
			$row[] = new tabobject('importtask', $CFG->wwwroot.'/mod/referentiel/import_task.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importtask',  get_string('import','referentiel'));
		}
		
		// EXPORT
		
		if (has_capability('mod/referentiel:export', $context)) {
			$row[] = new tabobject('exporttask', $CFG->wwwroot.'/mod/referentiel/export_task.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exporttask',  get_string('export','referentiel'));
		}
		
		$tabs[] = $row;
        $activetwo = array('task');
    }
	
	// CERTIFICATS
    if (isset($currenttab) && (($currenttab == 'certificat')
		|| ($currenttab == 'verroucertif')
		|| ($currenttab == 'statcertif')
		|| ($currenttab == 'listcertif') 
		|| ($currenttab == 'listcertifsingle') 
		|| ($currenttab == 'scolarite') 
		|| ($currenttab == 'addcertif')
		|| ($currenttab == 'editcertif')
		|| ($currenttab == 'printcertif')
		|| ($currenttab == 'managecertif')
		|| ($currenttab == 'importcertif')
		|| ($currenttab == 'manageobjectif')
        || ($currenttab == 'archive') ))
    {
		$row  = array();
        $inactive[] = 'certificat';
		
		if (has_capability('mod/referentiel:view', $context)) { // afficher un certificat
      	    $row[] = new tabobject('listcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id.'&amp;mode=listcertif&amp;sesskey='.sesskey().$str_filtre, get_string('listcertif', 'referentiel'));
            if (has_capability('mod/referentiel:rate', $context)) { // rediger un certificat
                $label_thumb=get_string('editcertif', 'referentiel');
            }
            else{
                $label_thumb=get_string('synthese_certificat', 'referentiel');
            }
            $row[] = new tabobject('editcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id.'&amp;mode=editcertif&amp;sesskey='.sesskey().$str_filtre, $label_thumb);

            if (referentiel_site_can_print_graph($referentiel->id) ){
                $row[] = new tabobject('statcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id.'&amp;mode=statcertif&amp;sesskey='.sesskey().$str_filtre, get_string('statcertif', 'referentiel'));
            }
		}
		/*
		if (has_capability('mod/referentiel:rate', $context)) { // rediger un certificat
      	    $row[] = new tabobject('addcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id..'&amp;mode=addcertif&amp;sesskey='.sesskey().$str_filtre, get_string('addcertif', 'referentiel'));
		}

		if (has_capability('mod/referentiel:rate', $context)) { // rediger un certificat
      	    $row[] = new tabobject('editcertif', $CFG->wwwroot.'/mod/referentiel/certificat.php?d='.$referentiel->id.'&amp;mode=editcertif&amp;sesskey='.sesskey().$str_filtre, get_string('editcertif', 'referentiel'));
		}
		*/
		if (has_capability('mod/referentiel:managecertif', $context)) {
      	    $row[] = new tabobject('managecertif', $CFG->wwwroot.'/mod/referentiel/export_certificat.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=managecertif&amp;sesskey='.sesskey(), get_string('managecertif', 'referentiel'));
      	    $row[] = new tabobject('importcertif', $CFG->wwwroot.'/mod/referentiel/import_certificat.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importcertif&amp;sesskey='.sesskey(), get_string('importcertif', 'referentiel'));

			if (referentiel_site_can_print_referentiel($referentiel->id)) { 
      	    	$row[] = new tabobject('printcertif', $CFG->wwwroot.'/mod/referentiel/print_certificat.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=printcertif&amp;sesskey='.sesskey(), get_string('printcertif', 'referentiel'));
			}
      	    $row[] = new tabobject('verroucertif', $CFG->wwwroot.'/mod/referentiel/verrou_certificat.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=verroucertif&amp;sesskey='.sesskey(), get_string('verroucertif', 'referentiel'));

            // OUTCOMES
            if (!empty($CFG->enableoutcomes) && (REFERENTIEL_OUTCOMES)){
                $row[] = new tabobject('manageobjectif', $CFG->wwwroot.'/mod/referentiel/outcomes.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=manageobjectif&amp;sesskey='.sesskey(), get_string('outcomes', 'referentiel'));
            }

		}
        // archiver les activites et le certificat
        if (has_capability('mod/referentiel:archive', $context)) {
            $row[] = new tabobject('archive', $CFG->wwwroot.'/mod/referentiel/archive.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=archive',  get_string('archive','referentiel'));
        }

        if ($currenttab == '') {
            $currenttab = $mode = 'listcertif';
        }
        $tabs[] = $row;
        $activetwo = array('certificat');
    }
	
	// SCOLARITE
    if (isset($currenttab)
        &&  (has_capability('mod/referentiel:viewscolarite', $context)
        || has_capability('mod/referentiel:managescolarite', $context))    
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
		
            $row[] = new tabobject('listetudiant', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listetudiant&amp;sesskey='.sesskey(), get_string('listetudiant', 'referentiel'));

            if (has_capability('mod/referentiel:managescolarite', $context)) { // import export
                if ($currenttab == 'editetudiant'){
                    $row[] = new tabobject('editetudiant', $CFG->wwwroot.'/mod/referentiel/etudiant.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=updateetudiant&amp;sesskey='.sesskey(), get_string('editetudiant', 'referentiel'));
                }
                $row[] = new tabobject('exportetudiant', $CFG->wwwroot.'/mod/referentiel/export_etudiant.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exportetudiant&amp;sesskey='.sesskey(), get_string('exportetudiant', 'referentiel'));
                $row[] = new tabobject('importetudiant', $CFG->wwwroot.'/mod/referentiel/import_etudiant.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importetudiant&amp;sesskey='.sesskey(), get_string('importetudiant', 'referentiel'));
        	}
            if (has_capability('mod/referentiel:viewscolarite', $context)) { // etablissement
                $row[] = new tabobject('listeetab', $CFG->wwwroot.'/mod/referentiel/etablissement.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listeetab&amp;sesskey='.sesskey(), get_string('etablissements', 'referentiel'));
            }
            if (has_capability('mod/referentiel:managescolarite', $context)) { // etablissement
                $row[] = new tabobject('manageetab', $CFG->wwwroot.'/mod/referentiel/etablissement.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=addetab&amp;sesskey='.sesskey(), get_string('manageetab', 'referentiel'));
            }

            if ($currenttab == '') {
                $currenttab = $mode = 'listetudiant';
            }
            $tabs[] = $row;
            $activetwo = array('scolarite');
    }

	// PEDAGOGIE
    if (isset($currenttab)
        &&  (has_capability('mod/referentiel:viewscolarite', $context)
        || has_capability('mod/referentiel:managescolarite', $context))    
        &&
		(   $scolarite_locale_visible && 
		    ($currenttab == 'pedago')
			|| ($currenttab == 'addpedago')
			|| ($currenttab == 'editpedago')
			|| ($currenttab == 'listpedago')
            || ($currenttab == 'listasso')
            || ($currenttab == 'selectasso')
            || ($currenttab == 'editasso')
			|| ($currenttab == 'importpedago')
			|| ($currenttab == 'exportpedago')
			
		)
		) 
    {
            $row  = array();
            $inactive[] = 'pedago';
            $row[] = new tabobject('listpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listpedago&amp;sesskey='.sesskey(), get_string('listpedago', 'referentiel'));
            $row[] = new tabobject('listasso', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listasso&amp;sesskey='.sesskey(), get_string('listasso', 'referentiel'));

            if (has_capability('mod/referentiel:managescolarite', $context)) { // import export
                $row[] = new tabobject('addpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=addpedago&amp;sesskey='.sesskey(), get_string('addpedago', 'referentiel'));
                if ($currenttab == 'editpedago'){
                    $row[] = new tabobject('editpedago', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=updatepedago&amp;sesskey='.sesskey(), get_string('editpedago', 'referentiel'));
                }

                $row[] = new tabobject('editasso', $CFG->wwwroot.'/mod/referentiel/pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=editasso&amp;sesskey='.sesskey(), get_string('editasso', 'referentiel'));
     	        $row[] = new tabobject('importpedago', $CFG->wwwroot.'/mod/referentiel/import_pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=importpedago&amp;sesskey='.sesskey(), get_string('importpedago', 'referentiel'));
			    $row[] = new tabobject('exportpedago', $CFG->wwwroot.'/mod/referentiel/export_pedagogie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=exportpedago&amp;sesskey='.sesskey(), get_string('exportpedago', 'referentiel'));

        	}

            if ($currenttab == '') {
                $currenttab = $mode = 'listpedago';
            }
            $tabs[] = $row;
            $activetwo = array('pedago');
    }

	// REFERENTIELS
	if (isset($currenttab) && (($currenttab == 'configref')
    || ($currenttab == 'protocole')
    || ($currenttab == 'referentiel') || ($currenttab == 'listreferentiel')
    || ($currenttab == 'editreferentiel') || ($currenttab == 'deletereferentiel')
    || ($currenttab == 'import')  || ($currenttab == 'import_simple')
    || ($currenttab == 'export'))) {
		$row  = array();
		$inactive[] = 'referentiel';

		if (has_capability('mod/referentiel:view', $context)) {
			$row[] = new tabobject('listreferentiel', $CFG->wwwroot.'/mod/referentiel/view.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=listreferentiel&amp;non_redirection=1',  get_string('listreferentiel','referentiel'));
		}

		// NOUVEAU CONTROLE v3.0
		//
        // DEBUG
        /*
        if (referentiel_site_can_write_or_import_referentiel($referentiel->id)){
            echo "<br />DEBUG :: tabs.php :: 271 :: VRAI\n";
        }
        else{
            echo"<br />DEBUG :: tabs.php :: 271 :: FAUX\n";
        }
        */

        if (
            (isset($isadmin) && $isadmin) || (isset($isreferentielauteur) && $isreferentielauteur)
            ||
            (referentiel_site_can_write_or_import_referentiel($referentiel->id) && empty($isstudent))
            )
        {
            // 2012/02/13
            $row[] = new tabobject('protocole', $CFG->wwwroot.'/mod/referentiel/edit_protocole.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=protocole&amp;sesskey='.sesskey(),  get_string('protocole','referentiel'));

            if (has_capability('mod/referentiel:writereferentiel', $context)) {
                // 2010/10/18
                $row[] = new tabobject('configref', $CFG->wwwroot.'/mod/referentiel/config_ref.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=configref&amp;sesskey='.sesskey(),  get_string('configref','referentiel'));
    	    	$row[] = new tabobject('editreferentiel', $CFG->wwwroot.'/mod/referentiel/edit.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=editreferentiel&amp;sesskey='.sesskey(),  get_string('editreferentiel','referentiel'));
    	    	$row[] = new tabobject('deletereferentiel', $CFG->wwwroot.'/mod/referentiel/delete.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=deleteferentiel&amp;sesskey='.sesskey(),  get_string('deletereferentiel','referentiel'));
			}
			if (has_capability('mod/referentiel:import', $context)) {
                $row[] = new tabobject('import', $CFG->wwwroot.'/mod/referentiel/import.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=import',  get_string('import','referentiel'));
            }
/*
			if (has_capability('mod/referentiel:import', $context) && referentiel_editor_is_ok()){
                $row[] = new tabobject('import_simple', $CFG->wwwroot.'/mod/referentiel/editor/import_referentiel_simplifie.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=import',  get_string('import_referentiel_xml','referentiel'));
			}
*/
        }
        else{
            // MODIF JF 2012/02/13
    	    $row[] = new tabobject('protocole', $CFG->wwwroot.'/mod/referentiel/protocole.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=protocole&amp;sesskey='.sesskey(),  get_string('protocole','referentiel'));
        }

        if (has_capability('mod/referentiel:export', $context)) {
    		$row[] = new tabobject('export', $CFG->wwwroot.'/mod/referentiel/export.php?d='.$referentiel->id.'&amp;select_acc='.$select_acc.'&amp;mode=export',  get_string('export','referentiel'));
        }

		if ($currenttab == '') {
            $currenttab = $mode = 'listreferentiel';
        }

		// print_r($row);
		// exit;
	    $tabs[] = $row;
		$activetwo = array('referentiel');
    }

}
else{ // pas d'autre possibilite que l'affichage du r�ferentiel
	$tabs[] = $row;
	$currenttab='referentiel';
}

/// Print out the tabs and continue!
// print_r($tabs);
// exit;
print_tabs($tabs, $currenttab, $inactive, $activetwo);
	
?>
