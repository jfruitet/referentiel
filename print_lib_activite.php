<?php  // $Id:  print_lib_activite.php,v 1.0 2008/04/29 00:00:00 jfruitet Exp $
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
 * Print Library of functions for activities of module referentiel
 * 
 * @author jfruitet
 * @version $Id: print_lib_activite.php,v 1.0 2008/04/29 00:00:00 jfruitet Exp $
 * @version $Id: print_lib_activite.php,v 2.0 2009/11/30 00:00:00 jfruitet Exp $
 * @package referentiel
 **/

 
require_once("lib.php");
require_once("overlib_item.php");


// MODIF JF 2012/02/24
// Nouvelle boite de saisie des items
// ----------------------------------------------------
function referentiel_selection_liste_codes_item_hierarchique($refrefid, $fonction=0){
// input : liste de code de la forme 'CODE''SEPARATEUR'
// retourne le selecteur
global $OK_REFERENTIEL_DATA;
global $t_domaine;
global $t_domaine_coeff;
global $t_domaine_description;

// COMPETENCES
global $t_competence;
global $t_competence_coeff;
global $t_competence_description;

// ITEMS
global $t_item_code;
global $t_item_coeff; // coefficient poids determine par le modele de calcul (soit poids soit poids / empreinte)
global $t_item_domaine; // index du domaine associe a un item
global $t_item_competence; // index de la competence associee a un item
global $t_item_poids; // poids
global $t_item_empreinte;
global $t_nb_item_domaine;
global $t_nb_item_competence;

global $t_item_description_competence;

$s='';

    // donnees globales du referentiel
	if ($refrefid){

		if (!isset($OK_REFERENTIEL_DATA) || ($OK_REFERENTIEL_DATA==false) ){
			$OK_REFERENTIEL_DATA=referentiel_initialise_data_referentiel($refrefid);
		}

		if (isset($OK_REFERENTIEL_DATA) && ($OK_REFERENTIEL_DATA==true)){


// DEBUG
// echo "<br />DEBUG :: print_lib_activite.php :: 77\n";
// print_object($t_domaine_description);

//echo "<br /> T_ITEM_CODE<br />\n";
//print_object($t_item_code);

//echo "<br /> T_ITEM_DESCRIPTION<br />\n";
//print_object($t_item_description_competence);

/*
echo "<br /> T_DOMAINE<br />\n";
print_object($t_domaine);
echo "<br /> T_ITEM_DOMAINE<br />\n";
print_object($t_item_domaine);
echo "<br /> T_NB_ITEM_DOMAINE<br />\n";
print_object($t_nb_item_domaine);

echo "<br /> T_COMPETENCE<br />\n";
print_object($t_competence);
echo "<br /> T_ITEM_COMPETENCE<br />\n";
print_object($t_item_competence);
echo "<br /> T_NB_ITEM_COMPETENCE<br />\n";
print_object($t_nb_item_competence);

echo"<br />EXIT: 98\n";
exit;
*/
            $nl='';
            $s1=' <input type="checkbox" id="code_item_';
            $s2='" name="code_item[]" value="';
            $s3='" />';
            $s4='<label for="code_item_';
            $s5='">';
            $s6='</label> '."\n";

            $ne=count($t_item_code);
            $select='';

            $index_code_domaine=$t_item_domaine[0];
            $code_domaine=$t_domaine[$index_code_domaine];

            $index_code_competence=$t_item_competence[0];
            $code_competence=$t_competence[$index_code_competence];

            $s.='&nbsp; &nbsp; &nbsp; <b>'.$code_domaine.'</b> : '.$t_domaine_description[$index_code_domaine]."\n";      // ouvrir domaine
            $s.='<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";     // ouvrir competence

            $i=0;
            while ($i<$ne){
                //echo $code_domaine.' '.$code_competence;
                //echo $t_item_domaine[$i].' '.$t_item_competence[$i];

                // domaine
                if ($t_item_domaine[$i] != $index_code_domaine){
                    $index_code_domaine=$t_item_domaine[$i];
                    $code_domaine=$t_domaine[$index_code_domaine];
                    // competence
                    $s.= '<br /> &nbsp; &nbsp; &nbsp; <b>'.$code_domaine.'</b> : '.$t_domaine_description[$index_code_domaine]."\n";  // nouveau domaine
                    // nouvelle competence
                    $index_code_competence=$t_item_competence[$i];
                    $code_competence=$t_competence[$index_code_competence];
                    $s.= '<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";
                }

                // competence
                if ($t_item_competence[$i] != $index_code_competence){
                    $index_code_competence=$t_item_competence[$i];
                    $code_competence=$t_competence[$index_code_competence];
                    $s.= '<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";
                }
                // item
                if (trim($t_item_code[$i])!=""){
                    // $nl.=$s1.$i.$s2.$tl[$i].$s3.$s4.$i.$s5.referentiel_affiche_overlib_un_item($separateur, $tl[$i]).$s6;
                    $s.= $s1.$i.$s2.$t_item_code[$i].$s3.$s4.$i.$s5.referentiel_affiche_overlib_un_item('/', $t_item_code[$i]).$s6;
                }

                $i++;
            }
            $s.= '<br />'."\n"; // refermer competence
            // refermer domaine

        }
    }
    if ($fonction) return $s; else echo $s;
}

/**
 *  Affichage hierarchique de la boite de selection des items de competence
 *
 *  @input
 *  refrefid : referentiel_referentiel id
 *  liste_saisie : string : les competences qui seront spécialement cochées
 *  is_task : boolean : activite de type tache, on n'affiche pas les autres items que ceux de la liste saisie
 *  activite_id : activity id , utile si l'activité est modifiée
 *  comportement : ??
 *  @author jf
 *  @output
 */

// ----------------------------------------------------
function referentiel_modifier_selection_codes_item_hierarchique($refrefid, $liste_saisie, $is_task=false, $id_activite=0, $comportement='', $fonction=0){
// MODIF JF 2012/02/24

// input : liste de code de la forme 'CODE''SEPARATEUR'
// input : liste2 de code de la forme 'CODE''SEPARATEUR' codes declares
// retourne le selecteur
	// DEBUG
	// echo "$liste_saisie<br />\n";
global $OK_REFERENTIEL_DATA;
global $t_domaine;
global $t_domaine_coeff;
global $t_domaine_description;

// COMPETENCES
global $t_competence;
global $t_competence_coeff;
global $t_competence_description;

// ITEMS
global $t_item_code;
global $t_item_coeff; // coefficient poids determine par le modele de calcul (soit poids soit poids / empreinte)
global $t_item_domaine; // index du domaine associe a un item
global $t_item_competence; // index de la competence associee a un item
global $t_item_poids; // poids
global $t_item_empreinte;
global $t_nb_item_domaine;
global $t_nb_item_competence;


global $t_item_description_competence;

$s='';

    $separateur='/';
	$nl='';

    if ($id_activite==0){
            $s1='<input type="checkbox" id="code_item_';
            $s2='" name="code_item[]" value="';
            $s3='"';
            $s4=' />';
            $s5='<label for="code_item_';
            $s6='">';
            $s7='</label> '."\n";
	}
	else{
            $s1='<input type="checkbox" id="code_item_'.$id_activite.'_';
            $s2='" name="code_item_'.$id_activite.'[]" value="';
            $s3='"';
            if (!empty($comportement)){
                $s4=' '.$comportement.' />';
            }
            else{
                $s4=' />';
            }
            $s5='<label for="code_item_'.$id_activite.'_';
	   	    $s6='">';
		    $s7='</label> '."\n";
	}


	$checked=' checked="checked"';
	/*
    $tl=explode($separateur, $liste_complete);
    */

	if ($refrefid){

		if (!isset($OK_REFERENTIEL_DATA) || ($OK_REFERENTIEL_DATA==false) ){
			$OK_REFERENTIEL_DATA=referentiel_initialise_data_referentiel($refrefid);
		}

		if (isset($OK_REFERENTIEL_DATA) && ($OK_REFERENTIEL_DATA==true)){

        // DEBUG
/*
echo "<br />DEBUG :: print_lib_activite.php :: 227\n";
echo "<br /> T_ITEM_CODE<br />\n";
print_object($t_item_code);
*/
    $tl=$t_item_code;

    $liste_saisie=strtr($liste_saisie, $separateur, ' ');
	$liste_saisie=trim(strtr($liste_saisie, '.', '_'));
	// echo "<br />DEBUG :: 201 :: $liste_saisie<br />\n";
	$ne=count($tl);
	$select='';

    $index_code_domaine=$t_item_domaine[0];
    $code_domaine=$t_domaine[$index_code_domaine];

    $index_code_competence=$t_item_competence[0];
    $code_competence=$t_competence[$index_code_competence];

    $s.= '&nbsp; &nbsp; &nbsp; <b>'.$code_domaine.'</b> : '.$t_domaine_description[$index_code_domaine]."\n";      // ouvrir domaine
    $s.= '<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br />&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";     // ouvrir competence

    $i=0;
    while ($i<$ne){
        //echo $code_domaine.' '.$code_competence;
        //echo $t_item_domaine[$i].' '.$t_item_competence[$i];

        // domaine
        if ($t_item_domaine[$i] != $index_code_domaine){
                    $index_code_domaine=$t_item_domaine[$i];
                    $code_domaine=$t_domaine[$index_code_domaine];
                    // competence
                    $s.='<br /> &nbsp; &nbsp; &nbsp; <b>'.$code_domaine.'</b> : '.$t_domaine_description[$index_code_domaine]."\n";  // nouveau domaine
                    // nouvelle competence
                    $index_code_competence=$t_item_competence[$i];
                    $code_competence=$t_competence[$index_code_competence];
                    $s.='<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";
        }

        // competence
        if ($t_item_competence[$i] != $index_code_competence){
                    $index_code_competence=$t_item_competence[$i];
                    $code_competence=$t_competence[$index_code_competence];
                    $s.= '<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <i>'.$code_competence.'</i> : <span class="small">'.$t_competence_description[$index_code_competence].'</span><br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'."\n";
        }
                // item

		$code=trim($tl[$i]);

		$le_code=referentiel_affiche_overlib_un_item($separateur, $code);

		if ($code!=""){
			// $code_search='/'.strtr($code, '.', '_').'/';
			// echo "RECHERCHE '$code_search' dans '$liste_saisie'<br />\n";
			// echo "<br />DEBUG :: print_lib_activite :: 213 :: $code_search<br />\n";
			// if (preg_match($code_search, $liste_saisie)){

			$code_search=strtr($code, '.', '_');
			// if (eregi($code_search, $liste_saisie)){
			if (stristr($liste_saisie, $code_search)){
				$s.= $s1.$i.$s2.$code.$s3.$checked.$s4.$s5.$i.$s6.$le_code.$s7;
			}
			else {
				if (!$is_task){
					$s.=$s1.$i.$s2.$code.$s3.$s4.$s5.$i.$s6.$le_code.$s7;
				}
				else{
					$s.=' &nbsp; '. $s5.$i.$s6.$le_code.$s7;
				}
			}
		}
		$i++;
	}

 }
 }
    if ($fonction) return $s; else echo $s;
}



//
/************** A FINIR ********
function referentiel_upload_ressources($referentiel, $activite, $userid, $select_acc){

$s='';

    $s.='
<form name="form" method="post" action="upload.php?d='.$referentiel->id.'">

<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- input type="hidden" name="old_liste_competences" value="'.$activite->old_liste_competences.'" / -->
<input type="hidden" name="ref_activite" value="'.$activite->id.'" />
<input type="hidden" name="approved" value="'.$activite->approved.'" />
<input type="hidden" name="userid" value="'.$activite->userid.'" />
<input type="hidden" name="teacherid" value="'.$activiteteacherid.'" />
<input type="hidden" name="activite_id" value="'.$activite->id.'" />
<input type="hidden" name="ref_referentiel" value="'.$activite->ref_referentiel.'" />
<input type="hidden" name="ref_course" value="'.$activite->ref_course.'" />
<input type="hidden" name="ref_instance" value="'.$activite->ref_instance.'" />
<input type="hidden" name="action" value="creer_document" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$form->course.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="modulename"    value="'.$form->modulename.'" />
<input type="hidden" name="instance"      value="'.$form->instance.'" />
<input type="hidden" name="mode"          value="'.$mode.'" />
<input type="submit" value="'.get_string('document_ajout', 'referentiel').'" />
<!-- input type="submit" name="cancel" value="'.get_string("quit","referentiel").'" / -->

}
*/



// ----------------------------------------------------
function referentiel_activite_prioritaire($activite){
global $USER;
    if (empty($activite->approved)){
        if ($USER->id != $activite->userid) {
            // retourne une valeur de couleur si
            if ( ($activite->date_modif && $activite->date_modif_student
             && ($activite->date_modif < $activite->date_modif_student))
             || (!$activite->teacherid)){
                return 1;
            }
        }
        else{
            // retourne une valeur de couleur si
            if ($activite->date_modif && $activite->date_modif_student
                && ($activite->date_modif >= $activite->date_modif_student))
            {
                return -1;
            }
        }
    }
    return 0;
}


// ----------------------------------------------------
function referentiel_affiche_competences_declarees($separateur1, $separateur2, $liste_certificat, $liste_activite, $liste_empreintes){
// Affiche les codes competences declarees en tenant compte de l'empreinte et de la validite
// Necessaire à l'affichage des overlib

    $MAXCOL=30;
    // Modif JF   2012/01/30
    // Adapter le nombre de colonnes à la taille des codes à afficher

    $lca=strlen($liste_activite);
    // echo "<br />Longueur : $lca\n";
    if  ($lca>600){
        $MAXCOL=round($lca/20)+1 ;
    }
    else if ($lca>390){
        $MAXCOL=round($lca/13)+1 ;
    }
    else if ($lca>300){
        $MAXCOL=round($lca/10)+1 ;
    }
    else if ($lca>180){
        $MAXCOL=round($lca/6)+1 ;
    }
    else if ($lca>150){
        $MAXCOL=round($lca/5)+1 ;
    }
    else{
        $MAXCOL=30;
    }
    // echo "<br />NB COLONNES : $MAXCOL<br /> \n";
    // exit;
    
	$t_empreinte=explode($separateur1, $liste_empreintes);
	$okc=false;
	$oka=false;
	$s='';
	$tc=array();
	$liste_certificat=referentiel_purge_dernier_separateur($liste_certificat, $separateur1);
	$liste_activite=referentiel_purge_dernier_separateur($liste_activite, $separateur1);	
	if ((!empty($liste_certificat) || !empty($liste_activite)) && ($separateur1!="") && ($separateur2!="")){
		if (!empty($liste_certificat)){
			$tc = explode ($separateur1, $liste_certificat);
			$okc=true;
		}
		if (!empty($liste_activite)){
			$ta = explode ($separateur1, $liste_activite);
			$oka=true;
		}
		// DEBUG 
		// echo "<br />CODE <br />\n";
		// print_r($tc);
		if ($oka){
			$i=0;
			
			$s.="\n".'<p>'."\n";
			while ($i<count($ta)){
				// CODE1:N1
				// DEBUG 
				// echo "<br />".$tc[$i]." <br />\n";
				// exit;
				$tca=explode($separateur2, $ta[$i]);
				if ($okc){
					$tcc=explode($separateur2, $tc[$i]);
				}
				// echo "<br />".$tc[$i]." <br />\n";
				// print_r($tcc);
				// exit;
				if (($i!=0) && ($i%$MAXCOL==0)){
					//$s.='</tr>'."\n".'<tr>';
					$s.='<br />'."\n";
				}
				
				
				if ($okc && isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
					// Overlib
					$code_s=referentiel_affiche_overlib_un_item($separateur2, $tca[0], "vert");				
					$s.='<b>'.$code_s.'</b> ';
				}
				else if ($okc && isset($tcc[1]) && ($tcc[1]>0)){
					$code_s=referentiel_affiche_overlib_un_item($separateur2, $tca[0], "orange");								
					$s.='<b>'.$code_s.'</b> ';
				}
				else if (isset($tca[1]) && ($tca[1]>0)){
					$code_s=referentiel_affiche_overlib_un_item($separateur2, $tca[0], "rouge");								
					$s.='<i>'.$code_s.'</i> ';
				}
				else {
					$code_s=referentiel_affiche_overlib_un_item($separateur2, $tca[0], "nondefini");								
					$s.=$code_s.' ';
				}
				$i++;
			} 
			if ($i>$MAXCOL){
				$k=$MAXCOL-$i%$MAXCOL;
				$j=0;
				while ($j<$k){
					$s.='<span class="nondefini">&nbsp;</span>';
					$j++;
				}
			}
			$s.='</p>'."\n";
		}
	}
	return $s;
}

/******************** OLD ***************

// ----------------------------------------------------
function referentiel_affiche_competences_declarees($separateur1, $separateur2, $liste_certificat, $liste_activite, $liste_empreintes){
// Affiche les codes competences declarees en tenant compte de l'empreinte et de la validite
// Necessaire à l'affichage des overlib

    $MAXCOL=20;
    // Modif JF   2012/01/30
    // Adapter le nombre de colonnes à la taille des codes à afficher

    $lca=strlen($liste_activite);
    // echo "<br />Longueur : $lca\n";
    if  ($lca>600){
        $MAXCOL=round($lca/30)+1 ;
    }
    else if ($lca>400){
        $MAXCOL=round($lca/20)+1 ;
    }
    else if ($lca>300){
        $MAXCOL=round($lca/15)+1 ;
    }
    else if ($lca>200){
        $MAXCOL=round($lca/10)+1 ;
    }
    else if ($lca>160){
        $MAXCOL=round($lca/8)+1 ;
    }
    else{
        $MAXCOL=20;
    }
    // echo "<br />NB COLONNES : $MAXCOL<br /> \n";
    // exit;
    
	$t_empreinte=explode($separateur1, $liste_empreintes);
	$okc=false;
	$oka=false;
	$s='';
	$tc=array();
	$liste_certificat=referentiel_purge_dernier_separateur($liste_certificat, $separateur1);
	$liste_activite=referentiel_purge_dernier_separateur($liste_activite, $separateur1);	
	if ((!empty($liste_certificat) || !empty($liste_activite)) && ($separateur1!="") && ($separateur2!="")){
		if (!empty($liste_certificat)){
			$tc = explode ($separateur1, $liste_certificat);
			$okc=true;
		}
		if (!empty($liste_activite)){
			$ta = explode ($separateur1, $liste_activite);
			$oka=true;
		}
		// DEBUG 
		// echo "<br />CODE <br />\n";
		// print_r($tc);
		if ($oka){
			$i=0;
			
			$s.="\n".'<table class="activite">'."\n".'<tr>';
			while ($i<count($ta)){
				// CODE1:N1
				// DEBUG 
				// echo "<br />".$tc[$i]." <br />\n";
				// exit;
				$tca=explode($separateur2, $ta[$i]);
				if ($okc){
					$tcc=explode($separateur2, $tc[$i]);
				}
				// echo "<br />".$tc[$i]." <br />\n";
				// print_r($tcc);
				// exit;
				if (($i!=0) && ($i%$MAXCOL==0)){
					$s.='</tr>'."\n".'<tr>';
				}
				
				// Overlib
				$code_s=referentiel_affiche_overlib_un_item($separateur2, $tca[0]);
				
				if ($okc && isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
					$s.='<td class="vert"><b>'.$code_s.'</b></td> ';
				}
				else if ($okc && isset($tcc[1]) && ($tcc[1]>0)){
					$s.='<td class="orange"><b>'.$code_s.'</b></td> ';
				}
				else if (isset($tca[1]) && ($tca[1]>0)){
					$s.='<td class="rouge"><i>'.$code_s.'</i></td> ';
				}
				else {
					$s.='<td class="nondefini">'.$code_s.'</td> ';
				}
				$i++;
			} 
			if ($i>$MAXCOL){
				$k=$MAXCOL-$i%$MAXCOL;
				$j=0;
				while ($j<$k){
					$s.='<td class="nondefini">&nbsp;</td>';
					$j++;
				}
			}
			$s.='</tr>'."\n".'</table>'."\n";
		}
	}
	return $s;
}

********************************/

// Retourne un tableau de competences declarees
// *****************************************************************
// input @param a user id and a referentiel_referentiel id         *
// output string jauge competence declarees                        *
// *****************************************************************

function referentiel_print_jauge_activite($userid, $referentiel_referentiel_id ){
// MODIF JF 2009/11/28
// affiche la liste des competences declarees dans les activites par userid pour le referentiel $referentiel_referentiel_id
	$s="";
	
	if ($userid && $referentiel_referentiel_id){ 
		if (!referentiel_certificat_user_exists($userid, $referentiel_referentiel_id)){
			// CREER ce certificat
			referentiel_genere_certificat($userid, $referentiel_referentiel_id);
		}
		$record_certificat=referentiel_get_certificat_user($userid, $referentiel_referentiel_id);
		if ($record_certificat){
			// empreintes
			$liste_empreintes=referentiel_purge_dernier_separateur(referentiel_get_liste_empreintes_competence($referentiel_referentiel_id), '/');
			$s.=referentiel_affiche_competences_declarees('/',':',$record_certificat->competences_certificat, $record_certificat->competences_activite, $liste_empreintes);
            // MODIF JF 2012/10/10
            if (($record_certificat->verrou) && ($record_certificat->valide)){
                $s.='<span class="rouge">'.get_string('dossier_verrouille_ferme','referentiel').'</span>'."\n";
            } elseif ($record_certificat->verrou) {
                $s.='<span class="rouge">'.get_string('dossier_verrouille','referentiel').'</span>'."\n";
            } elseif ($record_certificat->valide) {
                $s.='<span class="rouge">'.get_string('dossier_non_verrouille_ferme','referentiel').'</span>'."\n";
            }
		}
	}
	return $s;
}


// ----------------------------------------------------
function referentiel_affiche_liste_codes_competence($separateur, $liste, $ref_referentiel){
// supprime separateur
global $t_item_code;
global $t_item_description_competence;
    if (empty($t_item_code) || empty($t_item_description_competence)){
        referentiel_initialise_descriptions_items_referentiel($ref_referentiel);
    }

	return referentiel_affiche_overlib_item($separateur, $liste);
}

// ----------------------------------------------------
function referentiel_selection_liste_codes_item_competence($separateur, $liste){
// input : liste de code de la forme 'CODE''SEPARATEUR' 
// retourne le selecteur
	global $t_item_description_competence;
	
	$nl='';
	$s1='<input type="checkbox" id="code_item_';
	$s2='" name="code_item[]" value="';
	$s3='" />';
	$s4='<label for="code_item_';
	$s5='">';
	$s6='</label> '."\n";	
	$tl=explode($separateur, $liste);
	if (!isset($t_item_description_competence) || (!$t_item_description_competence)){
		$ne=count($tl);
		$select='';
		for ($i=0; $i<$ne;$i++){
			if (trim($tl[$i])!=""){
				//$nl.=$s1.$i.$s2.$tl[$i].$s3.$s4.$i.$s5.$tl[$i].$s6;
				echo $s1.$i.$s2.$tl[$i].$s3.$s4.$i.$s5.$tl[$i].$s6;
			}
		}
	}
	else{
		$ne=count($tl);
		$select='';
		for ($i=0; $i<$ne;$i++){
			if (trim($tl[$i])!=""){
				// $nl.=$s1.$i.$s2.$tl[$i].$s3.$s4.$i.$s5.referentiel_affiche_overlib_un_item($separateur, $tl[$i]).$s6;
				echo $s1.$i.$s2.$tl[$i].$s3.$s4.$i.$s5.referentiel_affiche_overlib_un_item($separateur, $tl[$i]).$s6;
			}
		}
	}
	return $nl;
}

// ----------------------------------------------------
function referentiel_modifier_selection_liste_codes_item_competence($separateur, $liste_complete, $liste_saisie, $id_activite=0, $comportement=''){
// input : liste de code de la forme 'CODE''SEPARATEUR' 
// input : liste2 de code de la forme 'CODE''SEPARATEUR' codes declares
// retourne le selecteur
	// DEBUG
	// echo "$liste_saisie<br />\n";
global $t_item_description_competence;
	$nl='';

	if ($id_activite==0){
			$s1='<input type="checkbox" id="code_item_';
			$s2='" name="code_item[]" value="';
			$s3='"';
			$s4=' /><label for="code_item_';
			$s5='">';
			$s6='</label> '."\n";	
	}
	else{
			$s1='<input type="checkbox" id="code_item_'.$id_activite.'_';
			$s2='" name="code_item_'.$id_activite.'[]" value="';
			$s3='"';
			if (!empty($comportement)){
			$s4=' '.$comportement.' /><label for="code_item_'.$id_activite.'_';
      }
      else{
        $s4=' /><label for="code_item_'.$id_activite.'_';
      }
			$s5='">';
			$s6='</label> '."\n";	
	}
	
	$checked=' checked="checked"';
	$tl=explode($separateur, $liste_complete);
	$liste_saisie=strtr($liste_saisie, $separateur, ' ');
	$liste_saisie=trim(strtr($liste_saisie, '.', '_'));
	// echo "<br />DEBUG :: 201 :: $liste_saisie<br />\n";	
	$ne=count($tl);
	$select='';
	for ($i=0; $i<$ne;$i++){
		$code=trim($tl[$i]);
		
		$le_code=referentiel_affiche_overlib_un_item($separateur, $code);
		
		if ($code!=""){
			// $code_search='/'.strtr($code, '.', '_').'/';
			// echo "RECHERCHE '$code_search' dans '$liste_saisie'<br />\n";
			// echo "<br />DEBUG :: print_lib_activite :: 213 :: $code_search<br />\n";	
			// if (preg_match($code_search, $liste_saisie)){
			
			$code_search=strtr($code, '.', '_');
			// if (eregi($code_search, $liste_saisie)){
			if (stristr($liste_saisie, $code_search)){
				echo $s1.$i.$s2.$code.$s3.$checked.$s4.$i.$s5.$le_code.$s6;
			}
			else{
            	echo $s1.$i.$s2.$code.$s3.$s4.$i.$s5.$le_code.$s6;
			}
		}
	}

	return $nl;
}




// Affiche une ligne de la table quand il n'y a pas d'activite pour userid 
// *****************************************************************
// input @param a user id                                          *
// output string                                                     *
// *****************************************************************

function referentiel_print_aucune_activite_user($userid){
	$s='';
	if ($userid){
		$user_info=referentiel_get_user_info($userid);
		$date_modif_info=userdate(time());
	}
	else{
		$user_info="&nbsp;";
		$date_modif_info="&nbsp;";
	}
	
	$s.=get_string('zero_activite','referentiel',$date_modif_info).' '.$user_info."\n";
	
	return $s;
}


function referentiel_print_enqueue_activite(){
// Affiche une entete activite
	$s='</table>'."\n";
	return $s;
}

// ----------------------------------------------------
function referentiel_print_activite_id($activite_id, $referentiel_instance, $mode='listactivityall', $initiale=0, $userids='', $userid_filtre=0, $gusers=NULL, $sql_filtre_where='', $sql_filtre_order='', $data_filtre, $select_acc=0) {
global $DB;
global $CFG;
global $USER;
static $istutor=false;
static $isteacher=false;
static $isauthor=false;
static $iseditor=false;
static $isstudent=false;
static $referentiel_id = NULL;

// A COMPLETER
$data=NULL;

    // contexte
        $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
        $course = $DB->get_record('course', array('id' => $cm->course));
        if (empty($cm) or empty($course)){
            print_error('REFERENTIEL_ERROR 5 :: print_lib_task.php :: You cannot call this script in that way');
        }

        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
        $referentiel_id = $referentiel_instance->ref_referentiel;

        $records = array();
        $iseditor = has_capability('mod/referentiel:writereferentiel', $context);
	    $isteacher = has_capability('mod/referentiel:approve', $context)&& !$iseditor;
	    $istutor = has_capability('mod/referentiel:comment', $context) && !$iseditor  && !$isteacher;
	    $isauthor = has_capability('mod/referentiel:write', $context) && !$iseditor  && !$isteacher  && !$istutor;
        $isstudent = has_capability('mod/referentiel:selecttask', $context) && !$isauthor;

        /*
    	// DEBUG
	    if ($isteacher) echo "Teacher ";
    	if ($iseditor) echo "Editor ";
	    if ($istutor) echo "Tutor ";
        if ($isauthor) echo "Author ";
	    if ($isstudent) echo "Student ";
	    */


    if (!empty($referentiel_id)){
        $referentiel_referentiel=referentiel_get_referentiel_referentiel($referentiel_id);
        if (!$referentiel_referentiel){
            if ($iseditor){
                print_error('creer_referentiel','referentiel', "edit.php?d=$referentiel_instance->id&amp;mode=editreferentiel&amp;sesskey=".sesskey());
    		}
            else {
                print_error('creer_referentiel','referentiel', "../../course/view.php?id=$course->id&amp;sesskey=".sesskey());
    		}
	    }

        // MODIF JF 2012/05/22
        // Ajouter les boîtes de selection
		// boite pour selectionner les utilisateurs ?
	 	// preparer les variables globales pour Overlib
		referentiel_initialise_descriptions_items_referentiel($referentiel_referentiel->id);

        // Specifique car on a l'id de l'activité
        if ($activite_id){
            $record_a=referentiel_get_activite($activite_id);
            if ($record_a){
                $userid_filtre=$record_a->userid;
            }
        }

		// boite pour selectionner les utilisateurs ?
		if ($isteacher || $iseditor || $istutor){
			if (!empty($select_acc)){
			  // eleves accompagnes
                $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
            }
			else{
                // tous les users possibles (pour la boite de selection)
				// Get your userids the normal way
                $record_id_users  = referentiel_get_students_course($course->id,0,0);  //seulement les stagiaires
			}
            if ($gusers && $record_id_users){ // liste des utilisateurs du groupe courant
				// echo "<br />DEBUG :: print_lib_activite.php :: 740 :: GUSERS<br />\n";
				// print_object($gusers);
				// echo "<br />\n";
				// exit;
				$record_users  = array_intersect($gusers, array_keys($record_id_users));
				// echo "<br />DEBUG :: print_lib_activite.php :: 745 :: RECORD_USERS<br />\n";
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
			// Ajouter l'utilisateur courant pour qu'il voit ses activités
            $a_obj=new stdClass();
            $a_obj->userid=$USER->id;
            $record_id_users[]=$a_obj;

			echo referentiel_select_users_activite_accompagnes($userid_filtre, $select_acc, $mode);
            echo referentiel_select_users_activite_2($record_id_users, $userid_filtre, $select_acc, $mode, $initiale);
		}
		else{
            if (!empty($record_a) && ($USER->id == $record_a->userid)){
                $userid_filtre=$USER->id; // les étudiants ne peuvent voir que leur fiche
            }
            else{
                print_error('acces_interdit','referentiel', $CFG->wwwroot.'/mod/referentiel/view.php?id='.$cm->id.'&amp;non_redirection=1');
            }
		}

		// recuperer les utilisateurs filtres
			// $userid_filtre est l'id de l'utilisateur dont on affiche les activites
			// si $userid_filtre ==0 on retourne tous les utilisateurs du cours et du groupe
        if (!empty($userid_filtre)){
            $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
        }
		else{
            if (!empty($select_acc)){
                // eleves accompagnes
                $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
            }
            else{
                $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
			  // echo "<br />DEBUG :: print_lib_activite.php :: 1850 :: RECORD_USERS<br />\n";
			  // print_r($record_users  );
			  // echo "<br />\n";
            }
        }

		if ($record_id_users && $gusers){ // liste des utilisateurs du groupe courant
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
		else if ((($userid_filtre==$USER->id) || ($userid_filtre==0)) && ($isteacher || $iseditor|| $istutor)){
			// Ajouter l'utilisateur courant pour qu'il puisse voir ses activites
            $a_obj=new stdClass();
            $a_obj->userid=$USER->id;
            $record_id_users[]=$a_obj;
		}

		// echo "<br />DEBUG :: print_lib_activite.php :: 1870 :: RECORD_USERS<br />\n";
		// print_r($record_users  );
		// echo "<br />\n";

        // Afficher la boite de selection des utilisateurs
		if ($record_id_users){
			// Afficher
			if (isset($mode) && (($mode=='updateactivity') || ($mode=='listactivityall') || ($mode=='listactivitysingle'))){
				if ($mode=='updateactivity')
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, false, false);
                else
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, false, true);
			}
			else{
				echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, true);
			}

        }

        if (!empty($record_a)){
            echo referentiel_print_jauge_activite($record_a->userid, $referentiel_referentiel->id);
            referentiel_print_activite_detail($record_a);
            // Ajout JF 2012/11/29
            if (!$record_a->approved){
                echo '<div align="center">'.referentiel_ajout_document($record_a, $mode, $select_acc)."</div>\n";
            }
    	    referentiel_menu_activite_detail($context, $activite_id, $referentiel_instance->id, $record_a->approved, 0, 'listactivitysingle');
        }
    }
}



// Menu
// ----------------------------------------------------------
function referentiel_menu_activite_detail($context, $activite_id, $referentiel_instance_id, $approved, $select_acc=0, $mode='updateactivity'){
	global $CFG;
	global $OUTPUT;
			echo '<div align="center">';
        	// echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;activite_id='.$activite_id.'&amp;mode=listactivity&amp;old_mode='.$mode.'&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="pix/search.gif" alt="'.get_string('moins', 'referentiel').'" title="'.get_string('moins', 'referentiel').'" /></a>';
			echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=listactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="'.$OUTPUT->pix_url('nosearch','referentiel').'" alt="'.get_string('moins', 'referentiel').'" title="'.get_string('moins', 'referentiel').'" /></a>';
			if (has_capability('mod/referentiel:approve', $context)){
				echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>';
				echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>';
    	    }
			else if (referentiel_activite_isowner($activite_id)) {
            	if (!$approved){
					echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>';
	            }
				echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>';
    	    }
			// valider
    	    if (has_capability('mod/referentiel:approve', $context)){
				if (!$approved){
            		echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=approveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('nonvalide','referentiel').'" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>';
				}
	       		else{
    	        	echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=desapproveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('valide','referentiel').'" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>';
				}
			}
	        // commentaires
    	    if (has_capability('mod/referentiel:comment', $context)){
        		echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$referentiel_instance_id.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;mode=commentactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('feedback','referentiel').'" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>';
			}
			echo '</div>'."\n";
			
}


// ----------------------
function referentiel_select_users_activite_accompagnes($userid=0, $select_acc=0, $mode='listactivity'){
global $cm;
global $course;

$s="";
  $s.='<div align="center">'."\n"; 

	// accompagnement
	$s.="\n".'<form name="form" method="post" action="activite.php?id='.$cm->id.'&amp;action=select_acc">'."\n";
	$s.='<table class="selection">'."\n";
	$s.='<tr><td>';
	$s.=get_string('select_acc', 'referentiel');
  if (empty($select_acc)){
      $s.=' <input type="radio" name="select_acc" value="1" />'.get_string('yes')."\n";
		  $s.='<input type="radio" name="select_acc" value="0" checked="checked" />'.get_string('no')."\n";
	}
	else{
      $s.=' <input type="radio" name="select_acc" value="1" checked="checked" />'.get_string('yes')."\n";
		  $s.='<input type="radio" name="select_acc" value="0" />'.get_string('no')."\n";
  }
  $s.='</td><td><input type="submit" value="'.get_string('go').'" />'."\n";;
	$s.='
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />'."\n";
	$s.='</td>';
	$s.='</tr></table>'."\n";
	$s.='</form>'."\n";
  $s.='</div>'."\n";
			
	return $s;
}



// ----------------------
function referentiel_select_users_activite_2($record_users, $userid=0, $select_acc=0, $mode='listactivity', $initiales=''){
// SELECT INPUT  ALPHABETIQUE
global $CFG;
global $cm;
global $course;
$maxcol=MAXBOITESSELECTION;
$s="";
$t_users=array();
$t_users_id=array();
$t_users_firstname=array();
$t_users_lastname=array();

    if ($record_users){
		// $s.='<option value="0" selected="selected">'.get_string('choisir', 'referentiel').'</option>'."\n";
	    foreach ($record_users as $record_u) {   // liste d'id users
			//
			if (!empty($record_u->userid)){
    			$firstname= referentiel_get_user_prenom($record_u->userid);
                $lastname = referentiel_get_user_nom($record_u->userid);
                $initiale = mb_strtoupper(substr($lastname,0,1),'UTF-8');

			     $t_users[]= array('id' => $record_u->userid, 'lastname' => $lastname, 'firstname' => $firstname, 'initiale' => $initiale);
			     $t_users_id[]= $record_u->userid;

			     $t_users_lastname[] = $lastname;
			     $t_users_firstname[]= $firstname;
            }
		}

		array_multisort($t_users_lastname, SORT_ASC, $t_users_firstname, SORT_ASC, $t_users);
        $alpha  = explode(',', get_string('alphabet', 'referentiel'));
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
        $baseurl = $CFG->wwwroot.'/mod/referentiel/activite.php?id='.$cm->id.'&amp;action=selectuser&amp;initiale=';
        $baseurl1 ='&amp;userids=';
        $baseurl2 = '&amp;select_ac='.$select_acc.'&amp;mode='.$mode.'&amp;course='.$course->id.'&amp;sesskey='.sesskey();
 
        // selection alphabetique
        $s.='<div align="center">'."\n";
        $s.= '<a class="select" href="'.$baseurl.''.$baseurl1.''.$baseurl2.'">'.get_string('tous', 'referentiel').'</a> '."\n";
        foreach ($alpha as $letter){
            if (!empty($t_alphabetique[$letter])){
                $s.= '<a class="select" href="'.$baseurl.$letter.$baseurl1.$t_id_alphabetique[$letter].$baseurl2.'">'.$letter.'</a> '."\n";
            }
            else{
                $s.=''.$letter.' '."\n";
            }
        }
        $s.='</div><br />'."\n";

        $s.='<div align="center">'."\n";

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

		$s.='<table class="selection">'."\n";
        $s.='<tr>'."\n";
		for ($j=0; $j<$l; $j++){
            $s.='<td>'."\n";
			$s.="\n".'<form name="form" method="post" action="activite.php?id='.$cm->id.'&amp;action=selectuser">'."\n";

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
<!-- accompagnement -->
<input type="hidden" name="select_acc"        value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />'."\n";
			$s.='</form>'."\n";
			$s.='</td>'."\n";
        }

        if ($i<$n){
            $s.='<td>';
            $s.='<form name="form" method="post" action="activite.php?id='.$cm->id.'&amp;action=selectuser">'."\n";
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
<!-- accompagnement -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="mode"          value="'.$mode.'" />'."\n";
            $s.='</form>'."\n";
			$s.='</td>';
		}
        $s.='</tr></table>'."\n";
    $s.='</div>'."\n";
	}


	return $s;
}


// Saisie et validation

// Affiche une entete activite
// *****************************************************************
// *
// output string                                                    *
// *****************************************************************
function referentiel_modifie_entete_activite_complete_filtre($appli, $data, $oklistesimple=false, $menu_affiche=true){
// Affiche une entete activite complete
$s="";
$appli=$appli.'&amp;mode_select=selectetab';

	if ($oklistesimple){
		$width="10%";
	}
	else{
		$width="15%";
	}
	$s.='<table class="activite" width="100%">'."\n";
    $s.='<tr>'."\n";
	if ($menu_affiche){
        // MENU affiché
	   $s.='<th width="3%">'.get_string('menu','referentiel').'</th>'."\n";
    }	
    $s.='<th width="2%">'.get_string('id','referentiel').'</th>';
	$s.='<th width="'.$width.'">'.get_string('auteur','referentiel');
	$s.="\n".'<form action="'.$appli.'" method="get" id="selectetab_filtre_auteur" class="popupform">'."\n";
	$s.=' <select id="selectetab_filtre_auteur" name="filtre_auteur" size="1" 
onchange="self.location=document.getElementById(\'selectetab_filtre_auteur\').filtre_auteur.options[document.getElementById(\'selectetab_filtre_auteur\').filtre_auteur.selectedIndex].value;">'."\n";
	if (isset($data) && !empty($data)){
		if ($data->filtre_auteur=='1'){
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=-1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else if ($data->filtre_auteur=='-1'){
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=-1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else{
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_auteur=-1&amp;filtre_referent=0&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
	}
	else{
		$s.='	<option value="'.$appli.'&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_auteur=1&amp;filtre_referent=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('croissant','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_auteur=-1&amp;filtre_referent=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('decroissant','referentiel').'</option>'."\n";
	}
	$s.='</select>'."\n";
    $s.='</form>'."\n";
	$s.='</th>';
	
	$s.='<th width="5%">'.get_string('course').'</th>';
	$s.='<th width="'.$width.'">'.get_string('type','referentiel').'</th>';
	if ($oklistesimple){
		$s.='<th width="25%">'.get_string('liste_codes_competence','referentiel').'</th>';
	}
	$s.='<th width="'.$width.'">'.get_string('a_evaluer','referentiel');
	$s.="\n".'<form action="'.$appli.'" method="get" id="selectetab_filtre_referent" class="popupform">'."\n";
	$s.=' <select id="selectetab_filtre_referent" name="filtre_referent" size="1" 
onchange="self.location=document.getElementById(\'selectetab_filtre_referent\').filtre_referent.options[document.getElementById(\'selectetab_filtre_referent\').filtre_referent.selectedIndex].value;">'."\n";
	if (isset($data) && !empty($data)){
		if ($data->filtre_referent=='1'){
			$s.='	<option value="'.$appli.'&amp;filtre_referent=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('examine','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('non_examine','referentiel').'</option>'."\n";
		}
		else if ($data->filtre_referent=='-1'){
			$s.='	<option value="'.$appli.'&amp;filtre_referent=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('examine','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('non_examine','referentiel').'</option>'."\n";
		}
		else{
			$s.='	<option value="'.$appli.'&amp;filtre_referent=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('examine','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_referent=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation='.$data->filtre_validation.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('non_examine','referentiel').'</option>'."\n";
		}
	}
	else{
		$s.='	<option value="'.$appli.'&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_referent=1&amp;filtre_auteur=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('examine','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_referent=-1&amp;filtre_auteur=0&amp;filtre_validation=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('non_examine','referentiel').'</option>'."\n";
	}
	$s.='</select>'."\n";
    $s.='</form>'."\n";
	$s.='</th>';
	
	$s.='<th width="'.$width.'">'.get_string('filtre_validation','referentiel');
	$s.="\n".'<form action="'.$appli.'" method="get" id="selectetab_filtre_validation" class="popupform">'."\n";
	$s.=' <select id="selectetab_filtre_validation" name="filtre_validation" size="1" 
onchange="self.location=document.getElementById(\'selectetab_filtre_validation\').filtre_validation.options[document.getElementById(\'selectetab_filtre_validation\').filtre_validation.selectedIndex].value;">'."\n";
	if (isset($data) && !empty($data)){
		if ($data->filtre_validation=='1'){
			$s.='	<option value="'.$appli.'&amp;filtre_validation=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('approved','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('not_approved','referentiel').'</option>'."\n";
		}
		else if ($data->filtre_validation=='-1'){
			$s.='	<option value="'.$appli.'&amp;filtre_validation=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_validation=0&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('approved','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('not_approved','referentiel').'</option>'."\n";
		}
		else{
			$s.='	<option value="'.$appli.'&amp;filtre_validation=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('approved','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_validation=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'">'.get_string('not_approved','referentiel').'</option>'."\n";
		}
	}
	else{
		$s.='	<option value="'.$appli.'&amp;filtre_validation=0&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_validation=1&amp;filtre_auteur=0&amp;filtre_referent=0&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('approved','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_validation=-1&amp;filtre_auteur=0&amp;filtre_referent=O&amp;filtre_date_modif_student=0&amp;filtre_date_modif=0">'.get_string('not_approved','referentiel').'</option>'."\n";
	}
	
	$s.='</select>'."\n";
    $s.='</form>'."\n";
	$s.='</th>';
	
	$s.='<th width="'.$width.'">'.get_string('filtre_date_modif_student','referentiel');
	$s.="\n".'<form action="'.$appli.'" method="get" id="selectetab_filtre_date_modif_student" class="popupform">'."\n";		
	$s.=' <select id="selectetab_filtre_date_modif_student" name="filtre_date_modif_student" size="1" 
onchange="self.location=document.getElementById(\'selectetab_filtre_date_modif_student\').filtre_date_modif_student.options[document.getElementById(\'selectetab_filtre_date_modif_student\').filtre_date_modif_student.selectedIndex].value;">'."\n";
	if (isset($data) && !empty($data)){
		if ($data->filtre_date_modif_student=='1'){
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else if ($data->filtre_date_modif_student=='-1'){
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else{
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif='.$data->filtre_date_modif.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
	}
	else{
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=0&amp;filtre_auteur=0&amp;filtre_date_modif=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=1&amp;filtre_auteur=0&amp;filtre_date_modif=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0">'.get_string('croissant','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif_student=-1&amp;filtre_auteur=0&amp;filtre_date_modif=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0">'.get_string('decroissant','referentiel').'</option>'."\n";
	}
	$s.='</select>'."\n";
    $s.='</form>'."\n";
	$s.='</th>';	

	$s.='<th width="'.$width.'">'.get_string('filtre_date_modif','referentiel');
	$s.="\n".'<form action="'.$appli.'" method="get" id="selectetab_filtre_date_modif" class="popupform">'."\n";	
	$s.=' <select id="selectetab_filtre_date_modif" name="filtre_date_modif" size="1" 
onchange="self.location=document.getElementById(\'selectetab_filtre_date_modif\').filtre_date_modif.options[document.getElementById(\'selectetab_filtre_date_modif\').filtre_date_modif.selectedIndex].value;">'."\n";
	if (isset($data) && !empty($data)){
		if ($data->filtre_date_modif=='1'){
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else if ($data->filtre_date_modif=='-1'){
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'1">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
		else{
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=0&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('croissant','referentiel').'</option>'."\n";
			$s.='	<option value="'.$appli.'&amp;filtre_date_modif=-1&amp;filtre_auteur='.$data->filtre_auteur.'&amp;filtre_date_modif_student='.$data->filtre_date_modif_student.'&amp;filtre_referent='.$data->filtre_referent.'&amp;filtre_validation='.$data->filtre_validation.'">'.get_string('decroissant','referentiel').'</option>'."\n";
		}
	}
	else{
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif=0&amp;filtre_auteur=0&amp;filtre_date_modif_student=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0" selected="selected">'.get_string('choisir','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif=1&amp;filtre_auteur=0&amp;filtre_date_modif_student=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0">'.get_string('croissant','referentiel').'</option>'."\n";
		$s.='	<option value="'.$appli.'&amp;filtre_date_modif=-1&amp;filtre_auteur=0&amp;filtre_date_modif_student=0&amp;filtre_referent=0&amp;filtre_auteur=0&amp;filtre_validation=0">'.get_string('decroissant','referentiel').'</option>'."\n";
	}
	$s.='</select>'."\n";
    $s.='</form>'."\n";
	$s.='</th>';
    $s.='</tr>'."\n";

	/*
	$s.='<tr>';
	$s.='<th colspan="3" rowspan="2">'.get_string('type_activite','referentiel').'<br />'.get_string('description','referentiel').'</th>';
	$s.='<th colspan="3">'.get_string('liste_codes_competence','referentiel').'<br />'.get_string('commentaire','referentiel').'</th>';
	$s.='</tr>'."\n";
	$s.='<tr>';
	$s.='<th colspan="3" class="yellow" align="center">'.get_string('document','referentiel').'</th>';
	$s.='</tr>'."\n";
	*/
	$s.='</table>'."\n";
	$s.='<table class="activite" width="100%">'."\n";
	return $s;
}

function referentiel_modifie_enqueue_activite(){
// Affiche une enqueue activite
	$s='';
	$s.='</table>'."\n";
	return $s;
}


/**************************************************************************
 * takes a list of records, the current referentiel, an optionnal user id *
 * and mode to display                                                    *
 * input @param string  $mode                                             *
 *       @param object $referentiel_instance                              *
 *       @param int $userid_filtre                                        *
 *       @param array of objects $gusers of users get from current group  *
 *       @param string $sql_filtre_where, $sql_filtre_order               *
 * output null                                                            *
 **************************************************************************/

function referentiel_print_evalue_liste_activites($mode, $referentiel_instance, $initiale=0, $userids='', $userid_filtre=0, $gusers=NULL, $sql_filtre_where='', $sql_filtre_order='', $data_filtre, $select_acc=0) {
global $CFG;
global $DB;
global $USER;
static $istutor=false;
static $isteacher=false;
static $isauthor=false;
static $iseditor=false;
static $referentiel_id = NULL;

// A COMPLETER
    $data=NULL;
	// contexte
    $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
    $course = $DB->get_record("course", array("id" => "$cm->course"));

	if (empty($cm) or empty($course)){
        print_error('REFERENTIEL_ERROR 5 :: print_lib_activite.php :: You cannot call this script in that way');
	}
	
    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}

	
	$records = array();
	$referentiel_id = $referentiel_instance->ref_referentiel;
	$iseditor = has_capability('mod/referentiel:writereferentiel', $context);
	$isteacher = has_capability('mod/referentiel:approve', $context)&& !$iseditor;
	$istutor = has_capability('mod/referentiel:comment', $context) && !$iseditor  && !$isteacher;	
	$isauthor = has_capability('mod/referentiel:write', $context) && !$iseditor  && !$isteacher  && !$istutor;

    $record_id_users=array();
    
	if (isset($referentiel_id) && ($referentiel_id>0)){
		$referentiel_referentiel=referentiel_get_referentiel_referentiel($referentiel_id);
		if (!$referentiel_referentiel){
			if ($iseditor){
			                print_error(get_string('creer_referentiel','referentiel'), "$CFG->wwwroot/mod/referentiel/edit.php?d=$referentiel_instance->id&amp;mode=editreferentiel&amp;sesskey=".sesskey());
			}
			else {
			                print_error(get_string('creer_referentiel','referentiel'), "$CFG->wwwroot/course/view.php?id=$course->id&amp;sesskey=".sesskey());
			}
		}
	 	// preparer les variables globales pour Overlib
		// referentiel_initialise_data_referentiel($referentiel_referentiel->id);
		referentiel_initialise_descriptions_items_referentiel($referentiel_referentiel->id);

		// boite pour selectionner les utilisateurs ?
		if ($isteacher || $iseditor || $istutor){
			if (!empty($select_acc)){
			  // eleves accompagnes
                $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
            }
			else{
                // tous les users possibles (pour la boite de selection)
				// Get your userids the normal way
                $record_id_users  = referentiel_get_students_course($course->id,0,0);  //seulement les stagiaires
			}
            if ($gusers && $record_id_users){ // liste des utilisateurs du groupe courant
				// echo "<br />DEBUG :: print_lib_activite.php :: 740 :: GUSERS<br />\n";
				// print_object($gusers);
				// echo "<br />\n";
				// exit;
				$record_users  = array_intersect($gusers, array_keys($record_id_users));
				// echo "<br />DEBUG :: print_lib_activite.php :: 745 :: RECORD_USERS<br />\n";
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
			// Ajouter l'utilisateur courant pour qu'il voit ses activités
            $a_obj=new stdClass();
            $a_obj->userid=$USER->id;
            $record_id_users[]=$a_obj;
			echo referentiel_select_users_activite_accompagnes($userid_filtre, $select_acc, $mode);
            echo referentiel_select_users_activite_2($record_id_users, $userid_filtre, $select_acc, $mode, $initiale);
		}
		else{
			$userid_filtre=$USER->id; // les étudiants ne peuvent voir que leur fiche
		}
		// recuperer les utilisateurs filtres
		// $userid_filtre est l'id de l'utilisateur dont on affiche les activites
		// si $userid_filtre ==0 on retourne tous les utilisateurs du cours et du groupe
		
        if (!empty($userid_filtre)){
            $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
        }
		else{
            if (!empty($select_acc)){
                // eleves accompagnes
                $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
            }
            else{
                $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
            }
        }
      
		if ($record_id_users && $gusers){ // liste des utilisateurs du groupe courant
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
        }

		else if ((($userid_filtre==$USER->id) || ($userid_filtre==0)) && ($isteacher || $iseditor|| $istutor)){
			// Ajouter l'utilisateur courant pour qu'il puisse voir ses activites
                        $a_obj=new stdClass();
                        $a_obj->userid=$USER->id;
                        $record_id_users[]=$a_obj;
		}

		// echo "<br />DEBUG :: print_lib_activite.php :: 1870 :: RECORD_USERS<br />\n";
		// print_r($record_users  );
		// echo "<br />\n";
		
		if ($record_id_users){
			// Afficher 		
			if (isset($mode) && (($mode=='updateactivity') || ($mode=='listactivityall') || ($mode=='listactivitysingle'))){
				if ($mode=='updateactivity')
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, false, false);
                else
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, false, true);
			}
			else{
				echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=$cm->id&amp;select_acc=$select_acc&amp;course=$course->id&amp;userid=$userid_filtre&amp;mode=$mode&amp;sesskey=".sesskey(), $data_filtre, true);
			}
			
			// ordre d'affichage utilisateurs
			if (isset($data_filtre) && isset($data_filtre->filtre_auteur) && ($data_filtre->filtre_auteur=='-1')){ 
				$deb=(-count($record_id_users))+1;
				$fin=1;
			}
			else{
				$deb=0;
				$fin=count($record_id_users);
			}
			
			// Parcours des utilisateurs
			for ($j=$deb; $j<$fin; $j++){
				$i=abs($j);
				// recupere les enregistrements
				// MODIF JF 23/10/2009
				if (isset($userid_filtre) && ($userid_filtre==$USER->id)){
					$actif=true; 
				}
				else if (isset($mode) && ($mode=='listactivityall')){
					$actif=false;
				}
				else{
					$actif=false;
					// 	$records=referentiel_get_all_activites_user_course($referentiel_instance->ref_referentiel, $record_id->userid, $course->id);
				}

				// MODIF JF 2009/11/28
				// Si des activites existent affichage de la liste des competences declarees
                if (!empty($record_id_users[$i]->userid)){
                    if (referentiel_user_activites($referentiel_instance->ref_referentiel, $record_id_users[$i]->userid)){
                        if (isset($mode) && (($mode=='updateactivity') || ($mode=='listactivityall') || ($mode=='listactivitysingle'))){
                            if ($mode=='updateactivity')
                                echo '<tr><td colspan="9" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
                            else
                                echo '<tr><td colspan="10" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
					    }
					    else{
						    echo '<tr><td colspan="10" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
					    }
                    }
				    else{
					    if (isset($mode) && ($mode=='listactivity')){
						    echo '<tr><td class="zero" colspan="10" align="center">'.referentiel_print_aucune_activite_user($record_id_users[$i]->userid).'</td></tr>'."\n";
					   }
				    }

				
				    // filtrage des activites demandees
				    $records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id_users[$i]->userid, $sql_filtre_where, $sql_filtre_order);
				    if ($records){
					   foreach ($records as $record) {   // afficher l'activite
						// Afficher 	
						if (isset($mode) && ($mode=='updateactivity')){
							echo referentiel_modifie_activite_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif, $select_acc);
						}
						else if (isset($mode) && ($mode=='listactivityall')){
                            // Affichage detaille
							echo referentiel_print_activite_3_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif, $select_acc);
						}
						elseif (isset($mode) && ($mode=='listactivitysingle')){
							echo referentiel_print_activite_3_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif, $select_acc);
						}
						else{
							echo referentiel_print_activite_2($record, $context, $actif, $select_acc);
						}
					}
				}
    		  }
			}
			
			if (isset($mode) && ($mode=='updateactivity')){
				// echo referentiel_modifie_activite_2_complete($record, $context, $actif);
				echo referentiel_modifie_enqueue_activite();
			}
			else{
				echo referentiel_print_enqueue_activite();
			}
		}
	}
	//echo '<br /><br />'."\n";
	return true;
}


// ----------------------------------------------------
function referentiel_print_activite_2($record_a, $context, $actif=true, $select_acc=0, $mode='listactivity'){
// $actif = true : le menu est active, sinon il ne l'est pas
global $CFG;
global $OUTPUT;
global $COURSE;
	$s="";
	if ($record_a){
		$activite_id=$record_a->id;
		$type_activite = stripslashes($record_a->type_activite);
		$description_activite = stripslashes($record_a->description_activite);
		$competences_activite = $record_a->competences_activite;
		$commentaire_activite = stripslashes($record_a->commentaire_activite);
		$ref_instance = $record_a->ref_instance;
		$ref_referentiel = $record_a->ref_referentiel;
		$ref_course = $record_a->ref_course;
		$userid = $record_a->userid;
		$teacherid = $record_a->teacherid;
		$date_creation = $record_a->date_creation;
		$date_modif = $record_a->date_modif;
		$date_modif_student = $record_a->date_modif_student;
		$approved = $record_a->approved;
		$ref_task = $record_a->ref_task;
		
		// MODIF JF 20091108
		// afficher le menu si on l'activité est affichee dans son propre cours de création 
		$menu_actif = $actif || ($ref_course == $COURSE->id);
		
		$user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);
		
		// dates
		if ($date_creation!=0){
			$date_creation_info=userdate($date_creation);
		}
		else{
			$date_creation_info='';
		}

		if ($date_modif!=0){
			$date_modif_info=userdate($date_modif);
		}
		else{
			$date_modif_info='';
		}

		if ($date_modif_student==0){
			$date_modif_student=$date_creation;
		}
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}

        $prioritaire=referentiel_activite_prioritaire($record_a);
        
		// MODIF JF 209/10/23
		$url_course=referentiel_get_course_link($ref_course);
		// MODIF JF 2013/01/26
		$url_instance=referentiel_get_instance_link($ref_instance);

		echo '<tr>';
		
		// menu
		echo '<td align="center">'."\n";

		if ($menu_actif){ 
			// echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivityall&amp;sesskey='.sesskey().'#activite"><img src="pix/search.gif" alt="'.get_string('plus', 'referentiel').'" title="'.get_string('plus', 'referentiel').'" /></a>'."\n";
			echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivitysingle&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="'.$OUTPUT->pix_url('search','referentiel').'" alt="'.get_string('plus', 'referentiel').'" title="'.get_string('plus', 'referentiel').'" /></a>'."\n";
			$has_capability=has_capability('mod/referentiel:approve', $context);
			$is_owner=referentiel_activite_isowner($activite_id);
			
			if ($has_capability	or $is_owner){
				if ($has_capability || ($is_owner && !$approved)) {
	        		echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
				}
				if ($has_capability || ($is_owner && !$approved)) {
			    	echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
    			}
			}
			// valider
		    if ($has_capability){
				if (!$approved){
					echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=approveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('nonvalide','referentiel').'" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>'."\n";
				}
				else{
    				echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=desapproveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('valide','referentiel').'" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>'."\n";
				}
			}
			// commentaires
    		if (has_capability('mod/referentiel:comment', $context)){
    			echo '&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=commentactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('feedback','referentiel').'" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>'."\n";
			}
		}
		else{
			echo '&nbsp; '.get_string('activite_exterieure', 'referentiel');
		}
		echo '</td>'."\n";
		
        if (!empty($prioritaire)){
            echo '<td class="prioritaire">';
        }
        else if (isset($approved) && ($approved)){
			echo '<td class="valide">';
		}
		else{
			echo '<td class="invalide">';
		}

		echo  $activite_id;
		echo '</td><td align="center">';
		echo $user_info;
        // MODIF JF 2012/05/06
        echo referentiel_liste_groupes_user($ref_course, $userid);
		echo '</td><td align="center">';
		echo $url_course.'<br />'.$url_instance;
		echo '</td><td align="center">';
		echo $type_activite;
		// Modif JF 06/10/2010
		if ($ref_task){
            // consignes associées à une tâche
            $titre_task=referentiel_get_theme_task($ref_task);
            $info_task=referentiel_get_content_task($ref_task);
            if ($info_task!=''){
                // lien vers la tâche
                echo '<br />'.referentiel_affiche_overlib_texte($titre_task, $info_task);
            }
            // documents associés à une tâche
            echo referentiel_print_liste_documents_task($ref_task);
        }

		
		if (isset($approved) && ($approved)){
			echo '</td><td class="valide">';
		}
		else{
			echo '</td><td class="invalide">';
		}

		// avec overlib
		echo referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel);
		
		echo '</td><td align="center">';
		echo $teacher_info;
		echo '</td><td align="center">';
		if (isset($approved) && ($approved)){
			echo get_string('approved','referentiel');
		}
		else{
			echo get_string('not_approved','referentiel');	
		}
		echo '</td>';

		if (!empty($prioritaire)){
    		echo '<td class="prioritaire" align="center">';
        }
        else{
            echo '<td align="center">';
        }
		echo '<span class="small">'.$date_modif_student_info.'</span>';
		echo '</td>'."\n";
		echo '<td align="center">';
		echo '<span class="small">'.$date_modif_info.'</span>';
		echo '</td>';
		echo '</tr>'."\n";		
	}
	return $s;
}


// Affiche une activite et les documents associés
// *****************************************************************
// input @param a $record_a   of activite                            *
// output null                                                     *
// *****************************************************************

function referentiel_print_activite_detail($record_a){
    $s='';
    $s0='';
    $s1='';
    $s2='';
    $nblignes=4; // hauteur du tableau
    $nbressource=0;
    
	if ($record_a){
		$activite_id=$record_a->id;
		$type_activite = stripslashes($record_a->type_activite);
		$description_activite = stripslashes($record_a->description_activite);
		$competences_activite = $record_a->competences_activite;
		$commentaire_activite = stripslashes($record_a->commentaire_activite);
		$ref_instance = $record_a->ref_instance;
		$ref_referentiel = $record_a->ref_referentiel;
		$ref_course = $record_a->ref_course;
		$userid = $record_a->userid;
		$teacherid = $record_a->teacherid;
		$date_creation = $record_a->date_creation;
		$date_modif = $record_a->date_modif;
		$date_modif_student = $record_a->date_modif_student;
		$approved = $record_a->approved;
		
		$user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);
		
		// dates
		$date_creation_info=userdate($date_creation);
		$date_modif_info=userdate($date_modif);
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}
		$ref_task = $record_a->ref_task;

        // preparation pour overlay
        if (empty($t_item_code) || empty($t_item_description_competence)){
            referentiel_initialise_descriptions_items_referentiel($ref_referentiel);
        }
        
        $prioritaire=referentiel_activite_prioritaire($record_a);

        $s0.='
<a name="activite_'.$activite_id.'"></a>
<table class="activite" width="100%">
<tr valign="top">';
        if (!empty($prioritaire)){
            $s0.= '<td class="prioritaire" ';
        }
        else if (isset($approved) && ($approved)){
			$s0.= '<td class="valide" ';
		}
		else{
			$s0.= '<td class="invalide" ';
		}

        $s0.=' rowspan="';
        
        $s1.='">
	<b>'.get_string('id_activite','referentiel', $activite_id).'  </b>
    </td>
    <td>
	<b>'.get_string('type_activite','referentiel').'</b><br />'.$type_activite."\n";

		if ($ref_task){
            // consignes associées à une tâche
            $titre_task=referentiel_get_theme_task($ref_task);
            $info_task=referentiel_get_content_task($ref_task);
            if ($info_task!=''){
                // lien vers la tâche
                $s1.='<br />'.referentiel_affiche_overlib_texte($titre_task, $info_task)."\n";
            }
            // documents associés à une tâche
            $s1.=referentiel_print_liste_documents_task($ref_task)."\n";
        }

        $s1.='</td>
    <td>
     <b>'.get_string('auteur','referentiel').'  </b><br />'.$user_info;
        // MODIF JF 2012/05/06
        $s1.= referentiel_liste_groupes_user($ref_course, $userid);
        $s1.='
    </td>
	<td>
	<b>'.get_string('date_creation','referentiel').'</b><br />
<span class="small">'.$date_creation_info.'</span>'.'
    </td>
';
// A COMPLETER PRIORITE
        $s1.='<td>
     <b>'.get_string('date_modif_student','referentiel').'</b><br />
<span class="small">'.$date_modif_student_info.'</span>'.'
    </td>
    <td>
     <b>'.get_string('referent','referentiel').'</b><br />'.$teacher_info.'
    </td>
    <td>
     <b>'.get_string('validation','referentiel').'</b><br />
';
		if (isset($approved) && ($approved)){
			$s1.=get_string('approved','referentiel');
		}
		else{
			$s1.=get_string('not_approved','referentiel');
		}
        $s1.='
    </td>
    <td>
     <b>'.get_string('date_modif','referentiel').'</b><br />
<span class="small">'.$date_modif_info.'</span>'.'
    </td>
</tr>
<tr valign="top">
';

		if (isset($approved) && ($approved)){
			$s1.='<td class="valide" colspan="7">'."\n";
		}
		else{
			$s1.='<td class="invalide" colspan="7">'."\n";
		}
		$s1.='<b>'.get_string('liste_codes_competence','referentiel').'</b>'."\n";
		$s1.=referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel)."\n";
        $s1.='
    </td>
</tr>

<tr valign="top">
    <td align="left" colspan="7">
	<b>'.get_string('description','referentiel').'</b><br />
        '.nl2br($description_activite).'
    </td>
</tr>
<tr valign="top">
    <td align="left" colspan="7">
	<b>'.get_string('commentaire','referentiel').'</b>
    <br />'.nl2br($commentaire_activite).'
    </td>
</tr>
';

        // charger les documents associes à l'activite courante
		if (isset($activite_id) && ($activite_id>0)){
			$ref_activite=$activite_id; // plus pratique
			// AFFICHER LA LISTE DES DOCUMENTS
			$compteur_document=0;
			$records_document = referentiel_get_documents($ref_activite);
	    	if ($records_document){
    			// afficher
				// DEBUG
				// echo "<br/>DEBUG <br />\n";
				// print_r($records_document);
                $nbressource=count($records_document);
                $s2.='<!-- DOCUMENTS -->
<tr valign="top"><td class="jaune" colspan="7">';
                if ($nbressource>1){
            		$s2.=get_string('ressources_associees','referentiel',$nbressource);
                }
                else{
            		$s2.=get_string('ressource_associee','referentiel',$nbressource);
                }
                $s2.="\n";

				foreach ($records_document as $record_d){
					$compteur_document++;
        			$document_id=$record_d->id;
					$type_document = stripslashes($record_d->type_document);
					$description_document = stripslashes($record_d->description_document);
					$url_document = $record_d->url_document;
					$ref_activite = $record_d->ref_activite;
					if (isset($record_d->cible_document) && ($record_d->cible_document==1)){
						$cible_document='_blank'; // fenêtre cible
					}
					else{
						$cible_document='';
					}
					if (isset($record_d->etiquette_document)){
						$etiquette_document=$record_d->etiquette_document; // fenêtre cible
					}
					else{
						$etiquette_document='';
					}
					
                    $s.='<br /><b>'.get_string('num','referentiel').'<i>'.$document_id.'</i></b>
&nbsp; &nbsp; &nbsp;
<b>'.get_string('type','referentiel').'</b> : '.$type_document.'
&nbsp; &nbsp; &nbsp;
<b>'.get_string('url','referentiel').'</b>  :
';
                    $s.=referentiel_affiche_url($url_document, $etiquette_document, $cible_document);
                    $s.='&nbsp; &nbsp; &nbsp; <b>'.get_string('description','referentiel').'</b> : '.nl2br($description_document).'
';
				}
			}
		}
        if ($nbressource){
            $nblignes=$nblignes+$nbressource+1;
        }
        echo $s0.$nblignes.$s1;

		if ($s2){
            echo $s2;
        }
		if ($s){
            echo $s.'</td></tr>'."\n";
        }

        echo '</table>
';

	}
}


/**************************************************************************
 * takes a list of records, the current referentiel, an optionnal user id *
 * and mode to display                                                    *
 * input @param string  $mode                                             *
 *       @param object $referentiel_instance                              *
 *       @param int $userid_filtre                                        *
 *       @param array of objects $gusers of users get from current group  *
 *       @param string $sql_filtre_where, $sql_filtre_order               *
 * output null                                                            *
 **************************************************************************/
function referentiel_print_evalue_global_liste_activites($mode, $referentiel_instance, $initiale=0, $userids='', $userid_filtre=0, $gusers=NULL, $sql_filtre_where='', $sql_filtre_order='', $data_filtre, $select_acc=0) {

// idem  que referentiel_print_evalue_liste_activite() 
// mais  specialise modification
// form globale

global $CFG;
global $OUTPUT;
global $DB;
global $USER;
static $istutor=false;
static $isteacher=false;
static $isauthor=false;
static $iseditor=false;
static $referentiel_id = NULL;

    // A COMPLETER
    $data=NULL;
	// contexte
    $cm = get_coursemodule_from_instance('referentiel', $referentiel_instance->id);
    $course = $DB->get_record("course", array("id" => "$cm->course"));
	if (empty($cm) or empty($course)){
        print_error('REFERENTIEL_ERROR 5 :: print_lib_activite.php :: You cannot call this script in that way');
	}
	
    // Valable pour Moodle 2.1 et Moodle 2.2
    //if ($CFG->version < 2011120100) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //} else {
        // $context = context_module::instance($cm);
    //}

	
	$records = array();
	$referentiel_id = $referentiel_instance->ref_referentiel;
	$iseditor = has_capability('mod/referentiel:writereferentiel', $context);
	$isteacher = has_capability('mod/referentiel:approve', $context)&& !$iseditor;
	$istutor = has_capability('mod/referentiel:comment', $context) && !$iseditor  && !$isteacher;	
	$isauthor = has_capability('mod/referentiel:write', $context) && !$iseditor  && !$isteacher  && !$istutor;
	/*
	// DEBUG
	if ($isteacher) echo "Teacher ";
	if ($iseditor) echo "Editor ";
	if ($istutor) echo "Tutor ";
	if ($isauthor) echo "Author ";
	*/
	
	
	if (isset($referentiel_id) && ($referentiel_id>0)){
		$referentiel_referentiel=referentiel_get_referentiel_referentiel($referentiel_id);
		if (!$referentiel_referentiel){
			if ($iseditor){
			                print_error(get_string('creer_referentiel','referentiel'), "$CFG->wwwroot/mod/referentiel/edit.php?d=$referentiel_instance->id&amp;mode=editreferentiel&amp;sesskey=".sesskey());
			}
			else {
			                print_error(get_string('creer_referentiel','referentiel'), ".$CFG->wwwroot/course/view.php?id=$course->id&amp;sesskey=".sesskey());
			}
		}
	 	// preparer les variables globales pour Overlib
		// referentiel_initialise_data_referentiel($referentiel_referentiel->id);
		referentiel_initialise_descriptions_items_referentiel($referentiel_referentiel->id);

		// boite pour selectionner les utilisateurs ?
		if ($isteacher || $iseditor || $istutor){
			if (!empty($select_acc)){
                // eleves accompagnes
                $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
            }
			else{
                // tous les users possibles (pour la boite de selection)
				// Get your userids the normal way
                $record_id_users  = referentiel_get_students_course($course->id,0,0);  //seulement les stagiaires
			}
            if ($gusers && $record_id_users){ // liste des utilisateurs du groupe courant
				// echo "<br />DEBUG :: print_lib_activite.php :: 740 :: GUSERS<br />\n";
				// print_object($gusers);
				// echo "<br />\n";
				// exit;
				$record_users  = array_intersect($gusers, array_keys($record_id_users));
				// echo "<br />DEBUG :: print_lib_activite.php :: 745 :: RECORD_USERS<br />\n";
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
			// Ajouter l'utilisateur courant pour qu'il voit ses activités

                        $a_obj=new stdClass();
                        $a_obj->userid=$USER->id;
                        $record_id_users[]=$a_obj;

            echo referentiel_select_users_activite_accompagnes($userid_filtre, $select_acc, $mode);
            echo referentiel_select_users_activite_2($record_id_users, $userid_filtre, $select_acc, $mode, $initiale);
		}
		else{
			$userid_filtre=$USER->id; // les étudiants ne peuvent voir que leur fiche
		}
		// recuperer les utilisateurs filtres
				
        if (!empty($select_acc) && ($userid_filtre == 0)){
            // eleves accompagnes
            $record_id_users  = referentiel_get_accompagnements_teacher($referentiel_instance->id, $course->id, $USER->id);
        }
        else{
            // retourne les etudiants du cours ou userid_filtre si != 0
            $record_id_users = referentiel_get_students_course($course->id, $userid_filtre, 0);
        }
		
		// afficher le groupe courant
		if ($record_id_users && $gusers){ // liste des utilisateurs du groupe courant
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

		else if ((($userid_filtre==$USER->id) || ($userid_filtre==0)) && ($isteacher || $iseditor|| $istutor)){
			// Ajouter l'utilisateur courant pour qu'il puisse voir ses propres activites
                        $a_obj=new stdClass();
                        $a_obj->userid=$USER->id;
                        $record_id_users[]=$a_obj;
		}

		// echo "<br />DEBUG :: print_lib_activite.php :: 1870 :: RECORD_USERS<br />\n";
		// print_r($record_users  );
		// echo "<br />\n";
		// afficher les activites
		if ($record_id_users){
			// Afficher 		
			if (isset($mode) && (($mode=='updateactivity') || ($mode=='listactivityall') || ($mode=='listactivitysingle'))){
                if ($mode=='updateactivity'){
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=".$cm->id."&amp;course=".$course->id."&amp;userid=".$userid_filtre."&amp;select_acc=".$select_acc."&amp;mode=".$mode."&amp;sesskey=".sesskey(), $data_filtre, false, false);
                }
                else{
                    echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=".$cm->id."&amp;course=".$course->id."&amp;userid=".$userid_filtre."&amp;select_acc=".$select_acc."&amp;mode=".$mode."&amp;sesskey=".sesskey(), $data_filtre, false);
                }
			}
			else{
				echo referentiel_modifie_entete_activite_complete_filtre("activite.php?id=".$cm->id."&amp;course=".$course->id."&amp;userid=".$userid_filtre."&amp;select_acc=".$select_acc."&amp;mode=".$mode."&amp;sesskey=".sesskey(), $data_filtre, true);
			}
			
			// ordre d'affichage utilisateurs
			if (isset($data_filtre) && isset($data_filtre->filtre_auteur) && ($data_filtre->filtre_auteur=='-1')){ 
				$deb=(-count($record_id_users))+1;
				$fin=1;
			}
			else{
				$deb=0;
				$fin=count($record_id_users);
			}

			// formulaire global
			echo "\n\n".'<form name="form" id="form" action="activite.php?id='.$cm->id.'&amp;course='.$course->id.'&amp;mode='.$mode.'&amp;filtre_auteur='.$data_filtre->filtre_auteur.'&amp;filtre_validation='.$data_filtre->filtre_validation.'&amp;filtre_referent='.$data_filtre->filtre_referent.'&amp;filtre_date_modif='.$data_filtre->filtre_date_modif.'&amp;filtre_date_modif_student='.$data_filtre->filtre_date_modif_student.'&amp;select_acc='.$select_acc.'&amp;sesskey='.sesskey().'" method="post">'."\n";
    	
            echo  '<tr valign="top">
<td class="ardoise" colspan="9">
 <img class="selectallarrow" src="'.$OUTPUT->pix_url('arrow_ltr_bas','referentiel').'" width="38" height="22" alt="Pour la sélection :" />
 <i>'.get_string('cocher_enregistrer', 'referentiel').'</i>
<input type="submit" value="'.get_string("savechanges").'" />
<input type="reset" value="'.get_string("corriger", "referentiel").'" />
<input type="submit" name="cancel" value="'.get_string("quit", "referentiel").'" />
</td></tr>'."\n";


			// Parcours des utilisateurs
			
			
			for ($j=$deb; $j<$fin; $j++){
				$i=abs($j);
				// recupere les enregistrements
				// MODIF JF 23/10/2009
				if (isset($userid_filtre) && ($userid_filtre==$USER->id)){
					$actif=true; 
				}
				else if (isset($mode) && ($mode=='listactivityall')){
					$actif=false;
				}
				else{
					$actif=false;
					// 	$records=referentiel_get_all_activites_user_course($referentiel_instance->ref_referentiel, $record_id->userid, $course->id);
				}

				// MODIF JF 2009/11/28
				// Si des activites existent affichage de la liste des competences declarees
				if (referentiel_user_activites($referentiel_instance->ref_referentiel, $record_id_users[$i]->userid)){
					if (isset($mode) && (($mode=='updateactivity') || ($mode=='listactivityall') || ($mode=='listactivitysingle'))){
                        if ($mode=='updateactivity'){
                            echo '<tr valign="top"><td colspan="8" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
                        }
                        else{
                            echo '<tr valign="top"><td colspan="9" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
                        }
					}
					else{
						echo '<tr valign="top"><td colspan="10" align="center">'.get_string('competences_declarees','referentiel', '<b>'.referentiel_get_user_info($record_id_users[$i]->userid).'</b>')."\n".referentiel_print_jauge_activite($record_id_users[$i]->userid, $referentiel_id).'</td></tr>'."\n";
					}
				}
				else{
					if (isset($mode) && ($mode=='listactivity')){
						echo '<tr valign="top"><td class="zero" colspan="10" align="center">'.referentiel_print_aucune_activite_user($record_id_users[$i]->userid).'</td></tr>'."\n";
					}
				}
				
				
				// filtrage des activites demandees
				$records=referentiel_get_all_activites_user($referentiel_instance->ref_referentiel, $record_id_users[$i]->userid, $sql_filtre_where, $sql_filtre_order);
				if ($records){

  					foreach ($records as $record) {   // afficher l'activite
	   					// Afficher 	
		  				if (isset($mode) && ($mode=='updateactivity')){
			   				echo referentiel_modifie_global_activite_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif);		   				
				  		}
					   	else if (isset($mode) && ($mode=='listactivityall')){
						  	echo referentiel_print_activite_3_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif, $select_acc);
						  }
						  elseif (isset($mode) && ($mode=='listactivitysingle')){
							 echo referentiel_print_activite_3_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif, $select_acc);
						  }
						  else{
							 echo referentiel_print_activite_2($record, $context, $actif, $select_acc);
						  }
					}

				}
    	}
    	
    	echo  '<tr valign="top">
<td class="ardoise" colspan="9">
 <img class="selectallarrow" src="'.$OUTPUT->pix_url('arrow_ltr','referentiel').'"
    width="38" height="22" alt="Pour la sélection :" />
<i>'.get_string('cocher_enregistrer', 'referentiel').'</i>      
<input type="hidden" name="action" value="modifier_activite_global" />
<!-- accompagnement -->
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="modulename"    value="referentiel" />
<input type="hidden" name="mode"          value="'.$mode.'" />
<input type="submit" value="'.get_string("savechanges").'" />
<input type="reset" value="'.get_string("corriger", "referentiel").'" />
<input type="submit" name="cancel" value="'.get_string("quit", "referentiel").'" />
</td></tr>
</form>'."\n";



			// liste des utilisateur achevee
			
			
			if (isset($mode) && ($mode=='updateactivity')){
				// echo referentiel_modifie_activite_2_complete($record, $context, $actif);
				echo referentiel_modifie_enqueue_activite();
			}
			else{
				echo referentiel_print_enqueue_activite();
			}
		}
	}
	//echo '<br /><br />'."\n";
	return true;
}



// ----------------------------------------------------
function referentiel_modifie_activite_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif=true){
// $actif = true : le menu est active, sinon il ne l'est pas
//	Saisie et validation
// $data_filtre : parametres de filtrage
// $mode : mode d'affichage
// $cm : course_module
// $course : enregistrement cours
// referentiel_instance : enregistrement instance
// record : enregistrement activite
// $context : contexte roles et capacites
// $actif : affichage menu
global $USER;
global $CFG;
global $OUTPUT;
global $COURSE;
	$s='';
	$s_menu='';
	$s_document='';
	$s_out='';
	
	// Charger les activites
	// filtres	
	$isteacher = has_capability('mod/referentiel:approve', $context);
	$isauthor = has_capability('mod/referentiel:write', $context) && !$isteacher;
	$iseditor = has_capability('mod/referentiel:writereferentiel', $context);	

	if ($record){
		$activite_id=$record->id;
		$type_activite = stripslashes($record->type_activite);
		$description_activite = stripslashes(strip_tags($record->description_activite));
		$competences_activite = stripslashes(strip_tags($record->competences_activite));
		$commentaire_activite = stripslashes(strip_tags($record->commentaire_activite));
		$ref_instance = $record->ref_instance;

		$ref_referentiel = $record->ref_referentiel;
		// liste des codes pur ce référentiel
		$liste_codes_competence=referentiel_get_liste_codes_competence($ref_referentiel);	

		$ref_course = $record->ref_course;

		$userid = $record->userid;
		$teacherid = $record->teacherid;
		if ($teacherid==0){
			if ($isteacher || $iseditor){ 
				$teacherid=$USER->id;
			}
		} 

		$date_creation = $record->date_creation;
		$date_modif = $record->date_modif;
		$approved = $record->approved;
		$ref_task = $record->ref_task;
		if ($ref_task>0){ // remplacer par la liste definie dans la tache
			$liste_codes_competences_tache=referentiel_get_liste_codes_competence_tache($ref_task);
			// DEBUG
			// echo "<br/>DEBUG ::<br />\n";
			// echo $liste_codes_competences_tache;				
		} 
		else{
			$liste_codes_competences_tache=$liste_codes_competence;
		}
		// DEBUG
		// echo "<br/>DEBUG ::<br />\n";
		// print_object($record);
		
		$user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);
		// dates
		$date_creation_info=userdate($date_creation);
		
		if ($date_modif!=0){
			$date_modif_info=userdate($date_modif);
		}
		else{
			$date_modif_info='';
		}

		// MODIF JF 2009/10/27
		$date_modif_student = $record->date_modif_student;
		if ($date_modif_student==0){
			$date_modif_student=$date_creation;
		}
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}
		
		
		// MODIF JF 2009/10/21						
		$old_liste_competences=stripslashes($record->competences_activite);		
		
		// MODIF JF 2009/10/23
		$url_course=referentiel_get_course_link($ref_course);
		// MODIF JF 2013/01/26
		$url_instance=referentiel_get_instance_link($ref_instance);

		// MODIF JF 2009/11/08
		// afficher le menu si l'activité est affiche dans son propre cours de création 
		$menu_actif = $actif || ($ref_course == $COURSE->id);

		if ($menu_actif){ 
			// $s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivityall&amp;sesskey='.sesskey().'#activite"><img src="pix/search.gif" alt="'.get_string('plus', 'referentiel').'" title="'.get_string('plus', 'referentiel').'" /></a>'."\n";
			$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivity&amp;sesskey='.sesskey().'#activite"><img src="'.$OUTPUT->pix_url('nosearch','referentiel').'" alt="'.get_string('moins', 'referentiel').'" title="'.get_string('moins', 'referentiel').'" /></a>'."\n";
			$has_capability=has_capability('mod/referentiel:approve', $context);
			$is_owner=referentiel_activite_isowner($activite_id);
			
			if ($has_capability	or $is_owner){
				if ($has_capability || ($is_owner && !$approved)) {
	        		$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=updateactivity&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
				}
            if ($has_capability || ($is_owner && !$approved)) {
			    	$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=deleteactivity&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
    			}
			}
			// valider
		    if ($has_capability){
				if (!$approved){
					$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=approveactivity&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('nonvalide','referentiel').'" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>'."\n";
				}
				else{
    				$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=desapproveactivity&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('valide','referentiel').'" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>'."\n";
				}
			}
			// commentaires
    		if (has_capability('mod/referentiel:comment', $context)){
    			$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=commentactivity&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('feedback','referentiel').'" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>'."\n";
			}
		}
		else{
			$s_menu.='&nbsp; '.get_string('activite_exterieure', 'referentiel');
		}

		// DOCUMENTS
		// charger les documents associes à l'activite courante
		$compteur_document=0;
		$s_document='';
		if (isset($activite_id) && ($activite_id>0)){
			$ref_activite=$activite_id; // plus pratique
			// AFFICHER LA LISTE DES DOCUMENTS
			$records_document = referentiel_get_documents($ref_activite);
	    	if ($records_document){
    			// afficher
				// DEBUG
				// echo "<br/>DEBUG <br />\n";
				// print_r($records_document);
				foreach ($records_document as $record_d){
					$compteur_document++;
        			$document_id=$record_d->id;
					$type_document = stripslashes($record_d->type_document);
					$description_document = stripslashes($record_d->description_document);
					$url_document = $record_d->url_document;
					$ref_activite = $record_d->ref_activite;
					if (isset($record_d->cible_document) && ($record_d->cible_document==1)){
						$cible_document='_blank'; // fenêtre cible
					}
					else{
						$cible_document='';
					}
					if (isset($record_d->etiquette_document)){
						$etiquette_document=$record_d->etiquette_document; // fenêtre cible
					}
					else{
						$etiquette_document='';
					}
					$s_document.=get_string('document', 'referentiel').' &nbsp; &nbsp; <i>'.$document_id.'</i> &nbsp; &nbsp; '.$type_document.' &nbsp; &nbsp; ';
					$s_document.=nl2br($description_document).' &nbsp; &nbsp; ';
					$s_document.=referentiel_affiche_url($url_document, $etiquette_document, $cible_document).'<br />'."\n";
				}
			}
		}
		
		// AFFICHAGE
		if ($ref_course == $COURSE->id){
			echo "\n".'<form action="activite.php?id='.$cm->id.'&amp;course='.$course->id.'&amp;mode='.$mode.'&amp;filtre_auteur='.$data_filtre->filtre_auteur.'&amp;filtre_validation='.$data_filtre->filtre_validation.'&amp;filtre_referent='.$data_filtre->filtre_referent.'&amp;filtre_date_modif='.$data_filtre->filtre_date_modif.'&amp;filtre_date_modif_student='.$data_filtre->filtre_date_modif_student.'&amp;sesskey='.sesskey().'" method="post">'."\n";
		}
		echo '<tr valign="top">';
        if (!empty($prioritaire)){
            echo '<td class="prioritaire" rowspan="3">';
        }
        else if (isset($approved) && ($approved)){
			echo '<td class="valide" rowspan="3">';
		}
		else{
			echo '<td class="invalide" rowspan="3">';
		}
		echo  $activite_id;
		echo '</td><td align="center">';
		echo $user_info;
        // MODIF JF 2012/05/06
        echo referentiel_liste_groupes_user($ref_course, $userid);
		echo '</td><td align="center">';
		echo $url_course.'<br />'.$url_instance;
		echo '</td><td align="center">';
		if ($ref_course == $COURSE->id){
			echo '<input type="text" name="type_activite" size="40" maxlength="80" value="'.$type_activite.'" />'."\n";
		}
		else{
			echo $type_activite;
		}
		echo '</td><td align="center">';
		echo $teacher_info;
		echo '</td><td align="center">';
		
		if (($ref_course == $COURSE->id) && (has_capability('mod/referentiel:approve', $context))){
			echo '<b>'.get_string('validation','referentiel').'</b> : ';
			if (isset($approved) && ($approved)){
				echo  '<input type="radio" name="approved"  id="approved" value="1" checked="checked" />'.get_string('yes').' &nbsp; <input type="radio" name="approved"  id="approved" value="0" />'.get_string('no').' &nbsp; &nbsp; '."\n";
			}
			else{
				echo '<input type="radio" name="approved"  id="approved" value="1" />'.get_string('yes').' &nbsp; <input type="radio" name="approved"  id="approved" value="0" checked="checked" />'.get_string('no').' &nbsp; &nbsp; '."\n";				
			}	
		}
		else{
			if (isset($approved) && ($approved)){
				echo get_string('approved','referentiel');
			}
			else{
				echo get_string('not_approved','referentiel');
			}	
			if ($ref_course == $COURSE->id){
				echo  '<input type="hidden" name="approved" value="'.$approved.'" />'."\n";			
			}
		}
		
		echo '</td>';
		echo '<td align="center">';
		echo '<span class="small">'.$date_modif_student_info.'</span>';
		echo '</td>';
		echo '<td align="center">';
		echo '<span class="small">'.$date_modif_info.'</span>';
		echo '</td>'."\n";
		// menu
		// echo '<td align="center" rowspan="3">'."\n";
		// echo $s_menu;
		// echo '</td>';
		echo '</tr>'."\n";
		echo '<tr valign="top">';
		if (isset($approved) && ($approved)){
			echo '<td  colspan="4" class="valide">';
		}
		else{
			echo '<td colspan="4" class="invalide">';
		}
		if ($ref_course == $COURSE->id){
			if (($ref_task!=0) && ($USER->id==$userid)) { // competences imposees selectionnables
                referentiel_modifier_selection_liste_codes_item_competence('/', $liste_codes_competences_tache, $competences_activite);
				echo '<input type="hidden" name="competences_activite" value="'.$competences_activite.'" />'."\n";
			}
			else{ // toutes les competences selectionnables
				referentiel_modifier_selection_liste_codes_item_competence('/', $liste_codes_competence, $competences_activite);
			}
		}
		else{
			// INUTILE referentiel_initialise_descriptions_items_referentiel($ref_referentiel);
			echo referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel);
		}
		if (($ref_course == $COURSE->id) && (has_capability('mod/referentiel:comment', $context))){
			echo '<textarea cols="80" rows="7" name="description_activite">'.$description_activite.'</textarea>'."\n";
		}
		else {
			echo nl2br($description_activite);
		}

		echo '</td>';
		if (isset($approved) && ($approved)){
			echo '<td class="valide"  colspan="3">';
		}
		else{
			echo '<td class="invalide" colspan="3">';
		}
		
		if ($ref_course == $COURSE->id){
			echo '<b>'.get_string('commentaire','referentiel').'</b><br />'."\n";			
			echo '<textarea cols="40" rows="7" name="commentaire_activite">'.$commentaire_activite.'</textarea>'."\n";
		}
		else{
			echo nl2br($commentaire_activite);
			if ($ref_course == $COURSE->id) {
				echo '<input type="hidden" name="commentaire_activite" value="'.$commentaire_activite.'" />'."\n";
			}
		}
		
		echo '</td>';

		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<td class="yellow" colspan="7" align="center">'."\n";
		if ($s_document!=''){
			echo $s_document;
		}
		else{
			echo '&nbsp;';
		}
		echo '</td></tr>'."\n";
		
		if ($ref_course == $COURSE->id){
			echo  '<tr valign="top"><td colspan="9" align="center">
<input type="hidden" name="date_creation" value="'.$date_creation.'" />
<input type="hidden" name="date_modif" value="'.$date_modif.'" />
<input type="hidden" name="date_modif_student" value="'.$date_modif_student.'" />
<input type="hidden" name="old_liste_competences" value="'.$old_liste_competences.'" />
<input type="hidden" name="userid" value="'.$userid.'" />
<input type="hidden" name="teacherid" value="'.$teacherid.'" />
<input type="hidden" name="activite_id" value="'.$activite_id.'" />
<input type="hidden" name="ref_referentiel" value="'.$ref_referentiel.'" />
<input type="hidden" name="ref_course" value="'.$ref_course.'" />
<input type="hidden" name="ref_instance" value="'.$ref_instance.'" />
<input type="hidden" name="action" value="modifier_activite" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$course->id.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="modulename"    value="referentiel" />
<input type="hidden" name="instance"      value="'.$referentiel_instance->id.'" />
<input type="hidden" name="mode"          value="'.$mode.'" />
<input type="submit" value="'.get_string("savechanges").'" />
<input type="reset" value="'.get_string("corriger", "referentiel").'" />
<!-- input type="submit" name="cancel" value="'.get_string("quit", "referentiel").'" / -->
</td></tr>
</form>'."\n";
		}
	}
	return $s;
}



// ----------------------------------------------------
function referentiel_modifie_global_activite_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif=true){
//	Saisie et validation globale
// idem que referentiel_modifie_globale_activite_complete() sauf que le formulaire est globale
// $actif = true : le menu est active, sinon il ne l'est pas
// $data_filtre : parametres de filtrage
// $mode : mode d'affichage
// $cm : course_module
// $course : enregistrement cours
// referentiel_instance : enregistrement instance
// record : enregistrement activite
// $context : contexte roles et capacites
// $actif : affichage menu
global $USER;
global $CFG;
global $OUTPUT;
global $COURSE;
global $t_item_code;
global $t_item_description_competence;

	$s='';
	$s_menu='';
	$s_document='';
	$s_out='';
	
	// Charger les activites
	// filtres	
	$isteacher = has_capability('mod/referentiel:approve', $context);
	$isauthor = has_capability('mod/referentiel:write', $context) && !$isteacher;
	$iseditor = has_capability('mod/referentiel:writereferentiel', $context);	

	if ($record){
		$activite_id=$record->id;
		$type_activite = stripslashes($record->type_activite);
		$description_activite = stripslashes(strip_tags($record->description_activite));
		$competences_activite = stripslashes(strip_tags($record->competences_activite));
		$commentaire_activite = stripslashes(strip_tags($record->commentaire_activite));
		$ref_instance = $record->ref_instance;

		$ref_referentiel = $record->ref_referentiel;
		// liste des codes pur ce référentiel
		$liste_codes_competence=referentiel_get_liste_codes_competence($ref_referentiel);	

		$ref_course = $record->ref_course;

		$userid = $record->userid;
		$teacherid = $record->teacherid;
		if ($teacherid==0){
			if ($isteacher || $iseditor){ 
				$teacherid=$USER->id;
			}
		} 

		$date_creation = $record->date_creation;
		$date_modif = $record->date_modif;
		$approved = $record->approved;
		$ref_task = $record->ref_task;
		if ($ref_task>0){ // remplacer par la liste definie dans la tache
			$liste_codes_competences_tache=referentiel_get_liste_codes_competence_tache($ref_task);
			// DEBUG
			// echo "<br/>DEBUG ::<br />\n";
			// echo $liste_codes_competences_tache;				
		} 
		else{
			$liste_codes_competences_tache=$liste_codes_competence;
		}
		// DEBUG
		// echo "<br/>DEBUG ::<br />\n";
		// print_object($record);
		
		$user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);
		// dates
		$date_creation_info=userdate($date_creation);
		
		if ($date_modif!=0){
			$date_modif_info=userdate($date_modif);
		}
		else{
			$date_modif_info='';
		}

		// MODIF JF 2009/10/27
		$date_modif_student = $record->date_modif_student;
		if ($date_modif_student==0){
			$date_modif_student=$date_creation;
		}
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}
		
		$prioritaire=referentiel_activite_prioritaire($record);
		
		// MODIF JF 2009/10/21						
		$old_liste_competences=stripslashes($record->competences_activite);		
		
		// MODIF JF 2009/10/23
		$url_course=referentiel_get_course_link($ref_course);
		// MODIF JF 2013/01/26
		$url_instance=referentiel_get_instance_link($ref_instance);

		// MODIF JF 2009/11/08
		// afficher le menu si l'activité est affiche dans son propre cours de création 
		$menu_actif = $actif || ($ref_course == $COURSE->id);

		if ($menu_actif){ 
   //             $s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="pix/nosearch.gif" alt="'.get_string('moins', 'referentiel').'" title="'.get_string('moins', 'referentiel').'" /></a>';
			$has_capability=has_capability('mod/referentiel:approve', $context);
			$is_owner=referentiel_activite_isowner($activite_id);
			
			if ($has_capability	or $is_owner){
				if ($has_capability || ($is_owner && !$approved)) {
	        		$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
				}
                if ($has_capability || ($is_owner && !$approved)) {
			    	$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
    			}
			}
			// valider
/*
		    if ($has_capability){
				if (!$approved){
					$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=approveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="pix/nonvalide.gif" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>'."\n";
				}
				else{
    				$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=desapproveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="pix/valide.gif" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>'."\n";
				}
			}
			// commentaires
    		if (has_capability('mod/referentiel:comment', $context)){
    			$s_menu.='&nbsp; <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=commentactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="pix/feedback.gif" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>'."\n";
			}
*/
		}
		else{
			$s_menu.='&nbsp; '.get_string('activite_exterieure', 'referentiel');
		}

		// DOCUMENTS
		// charger les documents associes à l'activite courante
		$compteur_document=0;
		$s_document='';
		if (isset($activite_id) && ($activite_id>0)){
			$ref_activite=$activite_id; // plus pratique
			// AFFICHER LA LISTE DES DOCUMENTS
			$records_document = referentiel_get_documents($ref_activite);
	    	if ($records_document){
    			// afficher
				// DEBUG
				// echo "<br/>DEBUG <br />\n";
				// print_r($records_document);
				foreach ($records_document as $record_d){
					$compteur_document++;
        			$document_id=$record_d->id;
					$type_document = stripslashes($record_d->type_document);
					$description_document = stripslashes($record_d->description_document);
					$url_document = $record_d->url_document;
					$ref_activite = $record_d->ref_activite;
					if (isset($record_d->cible_document) && ($record_d->cible_document==1)){
						$cible_document='_blank'; // fenêtre cible
					}
					else{
						$cible_document='';
					}
					if (isset($record_d->etiquette_document)){
						$etiquette_document=$record_d->etiquette_document; // fenêtre cible
					}
					else{
						$etiquette_document='';
					}
					$s_document.=get_string('document', 'referentiel').' &nbsp; &nbsp; <i>'.$document_id.'</i> &nbsp; &nbsp; '.$type_document.' &nbsp; &nbsp; ';
					$s_document.=nl2br($description_document).' &nbsp; &nbsp; ';
					$s_document.=referentiel_affiche_url($url_document, $etiquette_document, $cible_document).'<br />'."\n";
				}
			}
		}
		
		// AFFICHAGE
		/*
    if ($ref_course == $COURSE->id){
			echo "\n".'<form action="activite.php?id='.$cm->id.'&amp;course='.$course->id.'&amp;mode='.$mode.'&amp;filtre_auteur='.$data_filtre->filtre_auteur.'&amp;filtre_validation='.$data_filtre->filtre_validation.'&amp;filtre_referent='.$data_filtre->filtre_referent.'&amp;filtre_date_modif='.$data_filtre->filtre_date_modif.'&amp;filtre_date_modif_student='.$data_filtre->filtre_date_modif_student.'&amp;sesskey='.sesskey().'" method="post">'."\n";
		}
		*/
    echo '<tr valign="top">';
        if (!empty($prioritaire)){
            echo '<td class="prioritaire" rowspan="4">';
        }
        else if (isset($approved) && ($approved)){
			echo '<td class="valide" rowspan="4">';
		}
		else{
			echo '<td class="invalide" rowspan="4">';
		}
		
    // selection de l'activite
		/*
		if ($ref_course == $COURSE->id){
		  if (isset($approved) && ($approved)){
        echo  '<input type="checkbox" name="tactivite_id[]" id="tactivite_id_'.$activite_id.'" value="'.$activite_id.'" />';
		  }
		  else{
        echo  '<input type="checkbox" name="tactivite_id[]" id="tactivite_id_'.$activite_id.'" value="'.$activite_id.'" checked="checked" />';      
      }
    }
    */
    if ($ref_course == $COURSE->id){
      echo  '<input type="checkbox" name="tactivite_id[]" id="tactivite_id_'.$activite_id.'" value="'.$activite_id.'" />';      
    }
    echo  $activite_id;
		// menu
		echo '<br />'."\n";
		echo $s_menu;

		echo '</td>'."\n".'<td align="center">';
		echo $user_info;
        // MODIF JF 2012/05/06
        echo referentiel_liste_groupes_user($ref_course, $userid);
		echo '</td>'."\n".'<td align="center">';
		echo $url_course.'<br />'.$url_instance;
		echo '</td>'."\n".'<td align="center">';
		if ($ref_course == $COURSE->id){
			echo '<input type="text" name="type_activite_'.$activite_id.'" size="40" maxlength="80" value="'.$type_activite.'" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')"  />'."\n";
		}
		else{
			echo $type_activite;
		}
		echo '</td>'."\n".'<td align="center">';
		echo $teacher_info;
		echo '</td>'."\n".'<td align="center">';
		
		if (($ref_course == $COURSE->id) && (has_capability('mod/referentiel:approve', $context))){
			echo '<b>'.get_string('validation','referentiel').'</b> : ';
			if (isset($approved) && ($approved)){
				echo  '<input type="radio" name="approved_'.$activite_id.'"  id="approved" value="1" checked="checked" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('yes').' &nbsp; <input type="radio" name="approved_'.$activite_id.'" id="approved" value="0"  onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('no').' &nbsp; &nbsp; '."\n";
			}
			else{
				echo '<input type="radio" name="approved_'.$activite_id.'"  id="approved" value="1" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('yes').' &nbsp; <input type="radio" name="approved_'.$activite_id.'"  id="approved" value="0" checked="checked"  onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('no').' &nbsp; &nbsp; '."\n";				
			}	
		}
		else{
			if (isset($approved) && ($approved)){
				echo get_string('approved','referentiel');
			}
			else{
				echo get_string('not_approved','referentiel');
			}	
			if ($ref_course == $COURSE->id){
				echo  '<input type="hidden" name="approved_'.$activite_id.'" value="'.$approved.'" />'."\n";			
			}
		}
		
		echo '</td>';

		if (!empty($prioritaire)){
    		echo '<td class="prioritaire" align="center">';
        }
        else{
            echo '<td align="center">';
        }

		echo '<span class="small">'.$date_modif_student_info.'</span>';
		echo '</td>';
		echo '<td align="center">';
		echo '<span class="small">'.$date_modif_info.'</span>';
		echo '</td>'."\n";
		// menu
		// echo '<td align="center" rowspan="3">'."\n";
		// echo $s_menu;
		// echo '</td>';
		echo '</tr>'."\n";
		echo '<tr valign="top">';
		if (isset($approved) && ($approved)){
			echo '<td  colspan="7" class="valide">';
		}
		else{
			echo '<td colspan="7" class="invalide">';
		}
		if ($ref_course == $COURSE->id){
			if (($ref_task!=0) && ($USER->id==$userid)) { // modif competences imposees
				referentiel_modifier_selection_liste_codes_item_competence('/', $liste_codes_competences_tache, $competences_activite, $activite_id, 'onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" ');
				echo '<input type="hidden" name="competences_activite" value="'.$competences_activite.'" />'."\n";
			}
			else{ // modif toutes competences
				referentiel_modifier_selection_liste_codes_item_competence('/', $liste_codes_competence, $competences_activite, $activite_id, 'onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" ' );
			}
		}
		else{
			// INUTILE referentiel_initialise_descriptions_items_referentiel($ref_referentiel);
			echo referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel);
		}
		echo '</td></tr>'."\n";
		echo '<tr valign="top">';
		if (isset($approved) && ($approved)){
			echo '<td  colspan="4" class="valide">';
		}
		else{
			echo '<td colspan="4" class="invalide">';
		}

		if (($ref_course == $COURSE->id) && (has_capability('mod/referentiel:comment', $context))){
			echo '<br /><textarea cols="80" rows="7" name="description_activite_'.$activite_id.'" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\') ">'.$description_activite.'</textarea>'."\n";
		}
		else {
			echo '<br /><i>'.nl2br($description_activite).'</i>'."\n";
		}

		echo '</td>';

		if (isset($approved) && ($approved)){
			echo '<td class="valide"  colspan="3">';
		}
		else{
			echo '<td class="invalide" colspan="3">';
		}

        // echo '<td class="ardoise" colspan="3">';
		if ($ref_course == $COURSE->id){
			echo '<b>'.get_string('commentaire','referentiel').'</b><br />'."\n";			
			echo '<textarea cols="80" rows="7" name="commentaire_activite_'.$activite_id.'"  onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" >'.$commentaire_activite.'</textarea> <br />'."\n";
		}
		else{
			echo '<b>'.get_string('commentaire','referentiel').'</b><br /><i>'.nl2br($commentaire_activite).'</i>'."\n";
			if ($ref_course == $COURSE->id) {
				echo '<input type="hidden" name="commentaire_activite_'.$activite_id.'" value="'.$commentaire_activite.'" />'."\n";
			}
		}
		// MODIF 10/2/2010
		
		echo '<br />'.get_string('notification_activite','referentiel').'<input type="radio" name="mailnow_'.$activite_id.'" value="1" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('yes').' &nbsp; <input type="radio" name="mailnow_'.$activite_id.'" value="0" checked="checked" onchange="return validerCheckBox(\'tactivite_id_'.$activite_id.'\')" />'.get_string('no').' &nbsp; &nbsp; '."\n";

		echo '</td>';

		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<td class="yellow" colspan="7" align="center">'."\n";
		if ($s_document!=''){
			echo $s_document;
		}
		else{
			echo '&nbsp;';
		}
		echo '</td></tr>'."\n";
		if ($ref_course == $COURSE->id){
			echo  '
<input type="hidden" name="date_creation_'.$activite_id.'" value="'.$date_creation.'" />
<input type="hidden" name="date_modif_'.$activite_id.'" value="'.$date_modif.'" />
<input type="hidden" name="date_modif_student_'.$activite_id.'" value="'.$date_modif_student.'" />
<input type="hidden" name="old_liste_competences_'.$activite_id.'" value="'.$old_liste_competences.'" />
<input type="hidden" name="userid_'.$activite_id.'" value="'.$userid.'" />
<input type="hidden" name="teacherid_'.$activite_id.'" value="'.$teacherid.'" />
<input type="hidden" name="activite_id_'.$activite_id.'" value="'.$activite_id.'" />
<input type="hidden" name="ref_referentiel_'.$activite_id.'" value="'.$ref_referentiel.'" />
<input type="hidden" name="ref_course_'.$activite_id.'" value="'.$ref_course.'" />
<input type="hidden" name="ref_instance_'.$activite_id.'" value="'.$ref_instance.'" />'."\n\n";
		}

	}
	return $s;
}




// ----------------------------------------------------
function referentiel_print_activite_3_complete($data_filtre,$mode, $cm, $course, $referentiel_instance, $record, $context, $actif=true, $select_acc=0){
// $actif = true : le menu est active, sinon il ne l'est pas
// Saisie et validation
// $data_filtre : parametres de filtrage
// $mode : mode d'affichage
// $cm : course_module
// $course : enregistrement cours
// referentiel_instance : enregistrement instance
// record : enregistrement activite
// $context : contexte roles et capacites
// $actif : affichage menu
global $USER;
global $CFG;
global $OUTPUT;
global $COURSE;
	$s='';
	$s_menu='';
	$s_document='';
	$s_out='';
	
	// Charger les activites
	// filtres	
	$isteacher = has_capability('mod/referentiel:approve', $context);
	$isauthor = has_capability('mod/referentiel:write', $context) && !$isteacher;
	$iseditor = has_capability('mod/referentiel:writereferentiel', $context);	

	if ($record){
		$activite_id=$record->id;
		$type_activite = stripslashes($record->type_activite);
		$description_activite = stripslashes(strip_tags($record->description_activite));
		$competences_activite = stripslashes(strip_tags($record->competences_activite));
		$commentaire_activite = stripslashes(strip_tags($record->commentaire_activite));
		$ref_instance = $record->ref_instance;

		$ref_referentiel = $record->ref_referentiel;
		// liste des codes pur ce référentiel
		$liste_codes_competence=referentiel_get_liste_codes_competence($ref_referentiel);	

		$ref_course = $record->ref_course;

		$userid = $record->userid;
		$teacherid = $record->teacherid;
		if ($teacherid==0){
			if ($isteacher || $iseditor){ 
				$teacherid=$USER->id;
			}
		} 

		$date_creation = $record->date_creation;
		$date_modif = $record->date_modif;
		$approved = $record->approved;
		$ref_task = $record->ref_task;
		if ($ref_task>0){ // remplacer par la liste definie dans la tache
			$liste_codes_competences_tache=referentiel_get_liste_codes_competence_tache($ref_task);
			// DEBUG
			// echo "<br/>DEBUG ::<br />\n";
			// echo $liste_codes_competences_tache;				
		} 
		else{
			$liste_codes_competences_tache=$liste_codes_competence;
		}
		// DEBUG
		// echo "<br/>DEBUG ::<br />\n";
		// print_object($record);
		
		$user_info=referentiel_get_user_info($userid);
		$teacher_info=referentiel_get_user_info($teacherid);
		// dates
		$date_creation_info=userdate($date_creation);
		
		if ($date_modif!=0){
			$date_modif_info=userdate($date_modif);
		}
		else{
			$date_modif_info='';
		}

		// MODIF JF 2009/10/27
		$date_modif_student = $record->date_modif_student;
		if ($date_modif_student==0){
			$date_modif_student=$date_creation;
		}
		if ($date_modif_student!=0){
			$date_modif_student_info=userdate($date_modif_student);
		}
		else{
			$date_modif_student_info='';
		}
		
		$prioritaire=referentiel_activite_prioritaire($record);
		
		// MODIF JF 2009/10/21						
		$old_liste_competences=stripslashes($record->competences_activite);		
		
		// MODIF JF 2009/10/23
		$url_course=referentiel_get_course_link($ref_course);
		// MODIF JF 2013/01/26
		$url_instance=referentiel_get_instance_link($ref_instance);

		// MODIF JF 2009/11/08
		// afficher le menu si l'activité est affiche dans son propre cours de création 
		$menu_actif = $actif || ($ref_course == $COURSE->id);

		if ($menu_actif){ 
			$has_capability=has_capability('mod/referentiel:approve', $context);
			$is_owner=referentiel_activite_isowner($activite_id);
			$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=listactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'#activite"><img src="'.$OUTPUT->pix_url('nosearch','referentiel').'" alt="'.get_string('moins', 'referentiel').'" title="'.get_string('moins', 'referentiel').'" /></a>'."\n";
			if ($has_capability	or $is_owner){
				if ($has_capability || ($is_owner && !$approved)) {
	        		$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=updateactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('edit','referentiel').'" alt="'.get_string('edit').'" title="'.get_string('edit').'" /></a>'."\n";
				}
				if ($has_capability || ($is_owner && !$approved)) {
			    	$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=deleteactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('delete','referentiel').'" alt="'.get_string('delete').'" title="'.get_string('delete').'" /></a>'."\n";
    			}
			}
			// valider
		    if ($has_capability){
				if (!$approved){
					$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=approveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('nonvalide','referentiel').'" alt="'.get_string('approve', 'referentiel').'" title="'.get_string('approve', 'referentiel').'" /></a>'."\n";
				}
				else{
    				$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=desapproveactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('valide','referentiel').'" alt="'.get_string('desapprove', 'referentiel').'" title="'.get_string('desapprove', 'referentiel').'" /></a>'."\n";
				}
			}
			// commentaires
    		if (has_capability('mod/referentiel:comment', $context)){
    			$s_menu.=' <a href="'.$CFG->wwwroot.'/mod/referentiel/activite.php?d='.$ref_instance.'&amp;select_acc='.$select_acc.'&amp;activite_id='.$activite_id.'&amp;userid='.$userid.'&amp;mode=commentactivity&amp;old_mode='.$mode.'&amp;sesskey='.sesskey().'"><img src="'.$OUTPUT->pix_url('feedback','referentiel').'" alt="'.get_string('comment', 'referentiel').'" title="'.get_string('comment', 'referentiel').'" /></a>'."\n";
			}
		}
		else{
			$s_menu.='&nbsp; '.get_string('activite_exterieure', 'referentiel');
		}

		// DOCUMENTS
		// charger les documents associes à l'activite courante
		$compteur_document=0;
		$s_document='';
		if (isset($activite_id) && ($activite_id>0)){
			$ref_activite=$activite_id; // plus pratique
			// AFFICHER LA LISTE DES DOCUMENTS
			$records_document = referentiel_get_documents($ref_activite);
	    	if ($records_document){
    			// afficher
				// DEBUG
				// echo "<br/>DEBUG <br />\n";
				// print_r($records_document);
				foreach ($records_document as $record_d){
					$compteur_document++;
        			$document_id=$record_d->id;
					$type_document = stripslashes($record_d->type_document);
					$description_document = stripslashes($record_d->description_document);
					$url_document = $record_d->url_document;
					$ref_activite = $record_d->ref_activite;
					if (isset($record_d->cible_document) && ($record_d->cible_document==1)){
						$cible_document='_blank'; // fenêtre cible
					}
					else{
						$cible_document='';
					}
					if (isset($record_d->etiquette_document)){
						$etiquette_document=$record_d->etiquette_document; // fenêtre cible
					}
					else{
						$etiquette_document='';
					}
					$s_document.=get_string('document', 'referentiel').' &nbsp; &nbsp; <i>'.$document_id.'</i> &nbsp; &nbsp; '.$type_document.' &nbsp; &nbsp; ';
					$s_document.=nl2br($description_document).' &nbsp; &nbsp; ';
					$s_document.=referentiel_affiche_url($url_document, $etiquette_document, $cible_document)."<br /> \n";
				}
			}
		}
		
		// Bouton ajout document
        if ($menu_actif){   // verifier si l'activité est validee
            if (empty($approved)){
                $s_document.=referentiel_ajout_document($record, $mode, $select_acc)."\n";
            }
        }

		// AFFICHAGE
		echo '<tr valign="top">';
		// menu
		
		// Modif JF 2012/10/05
		echo '<td align="center" rowspan="3" width="3%">'."\n";
		echo $s_menu;
		echo '</td>';
			
        if (!empty($prioritaire)){
            echo '<td class="prioritaire" rowspan="3">';
        }
        else if (isset($approved) && ($approved)){
			echo '<td class="valide" rowspan="3">';
		}
		else{
			echo '<td class="invalide" rowspan="3">';
		}

		echo  $activite_id;
		echo '</td><td align="center">';
		echo $user_info;
        // MODIF JF 2012/05/06
        echo referentiel_liste_groupes_user($ref_course, $userid);
		echo '</td><td align="center">';
		echo $url_course.'<br />'.$url_instance;
		echo '</td><td align="center">';
		echo $type_activite;
		// Modif JF 06/10/2010
		if ($ref_task){
            // consignes associées à une tâche
            $titre_task=referentiel_get_theme_task($ref_task);
            $info_task=referentiel_get_content_task($ref_task);
            if ($info_task!=''){
                // lien vers la tâche
                echo '<br />'.referentiel_affiche_overlib_texte($titre_task, $info_task);
            }
            // documents associés à une tâche
            echo referentiel_print_liste_documents_task($ref_task);
        }

		echo '</td><td align="center">';
		echo $teacher_info;
		echo '</td><td align="center">';
		
			if (isset($approved) && ($approved)){
				echo get_string('approved','referentiel');
			}
			else{
				echo get_string('not_approved','referentiel');
			}	
		
		
		echo '</td>';
		if (!empty($prioritaire)){
    		echo '<td class="prioritaire" align="center">';
        }
        else{
            echo '<td align="center">';
        }

    	echo '<span class="small">'.$date_modif_student_info.'</span>';
		echo '</td>';
		echo '<td align="center">';
		echo '<span class="small">'.$date_modif_info.'</span>';
		echo '</td>'."\n";
		// menu
		/* 
		// Modif JF 2012/10/05
		echo '<td align="center" rowspan="3">'."\n";
		echo $s_menu;
		echo '</td>';
		*/
		echo '</tr><tr valign="top">'."\n";
		
		if (isset($approved) && ($approved)){
			echo '<td  colspan="5" class="valide">';
		}
		else{
			echo '<td colspan="5" class="invalide">';
		}
		echo referentiel_affiche_liste_codes_competence('/',$competences_activite, $ref_referentiel);
		echo '<br />'."\n";
		echo nl2br($description_activite);
		echo '</td>';

		echo '<td class="ardoise" colspan="2">';
		if ($commentaire_activite!=''){
			echo nl2br($commentaire_activite);
		}
		else{
			// echo get_string('nocommentaire','referentiel');
			echo '&nbsp;';
		}	
		echo '</td>';
		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<td class="yellow" colspan="7" align="center">'."\n";
		if ($s_document!=''){
			echo $s_document;
		}
		else{
			echo '&nbsp;';
		}
		echo '</td></tr>'."\n";
	}
	return $s;
}

//
/**
 * $record : activity record
 *
 */
function referentiel_ajout_document($record, $mode, $select_acc=0){
    $s='';
    if ($record) {
        // Bouton saisie d'une nouveau document
        // Cause d'erreurs pour l'utilisateur
        $s.='
<form name="form" method="post" action="upload_moodle2.php?d='.$record->ref_instance.'">
<input type="hidden" name="select_acc" value="'.$select_acc.'" />
<input type="hidden" name="ref_activite" value="'.$record->id.'" />
<input type="hidden" name="activite_id" value="'.$record->id.'" />
<input type="hidden" name="ref_referentiel" value="'.$record->ref_referentiel.'" />
<input type="hidden" name="ref_course" value="'.$record->ref_course.'" />
<input type="hidden" name="ref_instance" value="'.$record->ref_instance.'" />
<input type="hidden" name="action" value="creer_document" />
<!-- These hidden variables are always the same -->
<input type="hidden" name="course"        value="'.$record->ref_course.'" />
<input type="hidden" name="sesskey"     value="'.sesskey().'" />
<input type="hidden" name="modulename"    value="referentiel" />
<input type="hidden" name="instance"      value="'.$record->ref_instance.'" />
<input type="hidden" name="mode"          value="updateactivity" />
<input type="submit" value="'.get_string('document_ajout', 'referentiel').'" />
</form>';
    }
    return $s;
}

// ###################### AFFICHER LA LISTE DES DOCUMENTS  ####################

function referentiel_get_liens_documents($activite_id){
// Cadre d'affichage des liens vers les documents
    $links_documents='';
    $s='';
	// Recuperer les documents associes à l'activite
	$records_document = referentiel_get_documents($activite_id);
	if ($records_document){
        // afficher
		// DEBUG
		// echo "<br/>DEBUG<br />\n";
		// print_r($records_document);
        $nbressource=count($records_document);
        $s='<p><b>'.get_string('document_associe','referentiel').'</b></p>'."\n";
        $s.='<p><i>';
        if ($nbressource>1){
            $s.=get_string('ressources_associees','referentiel',$nbressource);
        }
        else{
            $s.=get_string('ressource_associee','referentiel',$nbressource);
        }
        $s.='</i>';

        $s.= '</p>'."\n";

		$compteur_document=0;
		foreach ($records_document as $record_d){
            if ($compteur_document%2==0)
                $bgcolor="#afefee";
            else
                $bgcolor="#faefee";
			$compteur_document++;
        	$document_id=$record_d->id;
			$type_document = stripslashes($record_d->type_document);
			$description_document = stripslashes($record_d->description_document);
			$url_document = stripslashes($record_d->url_document);
			$ref_activite = $record_d->ref_activite;
			$cible_document = $record_d->cible_document; // fenêtre cible
			$etiquette_document = $record_d->etiquette_document; // etiquette
            $link=referentiel_affiche_url($url_document, $etiquette_document, $cible_document);
			$links_documents.='<li>'.$link.'</li>'."\n";
        }
        if ($links_documents){
            $s.= '<ul>'.$links_documents.'</ul>'."\n";
        }
    }
    return $s;
}

?>