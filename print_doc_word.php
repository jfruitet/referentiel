<?php // $Id: print_doc_word.php,v 1.0.0.0 2009/12/14 11:32:00 jf Exp $

/**
 * file print_doc_word.php
 * print doc MSWord certificate reftrofitted msword stuff
 */

     
// traitement des chaines de caracteres
require_once('textlib.php');

// MSWORD maison
require_once('msword.class.php');



/**
     * generates <text></text> tags, processing raw text therein 
     * @param int ilev the current indent level
     * @param boolean short stick it on one line
     * @return string printted text
     */
function msword_write_etablissement( $record ) {
    // initial string;
		global $mswd;
		if ($record){
			$id = trim( $record->id );
			$num_etablissement = trim( $record->num_etablissement);
			$nom_etablissement = recode_utf8_vers_latin1(trim( $record->nom_etablissement));
			$adresse_etablissement = recode_utf8_vers_latin1(trim( $record->adresse_etablissement));
			$logo=$record->logo_etablissement;
			
			// $mswd->SetFont('Arial','I',10);
			// $texte=get_string('etablissement','referentiel').' <b>'.$nom_etablissement.'</b><br />'.get_string('num_etablissement','referentiel').' : <i>'.$num_etablissement.'</i> <br />'.$adresse_etablissement;
			$texte='<b>'.$nom_etablissement.'</b><br />'.get_string('num_etablissement','referentiel').' : <i>'.$num_etablissement.'</i> <br />'.$adresse_etablissement;
			$texte=recode_utf8_vers_latin1($texte);
			$mswd->WriteParagraphe(0,$texte);
			return true;
         }
	return false;
}
	
function msword_write_etudiant( $record ) {
global $mswd;
		if ($record){
			// DEBUG
			// echo "";
			// print_r($record);
	    	// add header
			//
			$id = trim( $record->id );
			$userid = trim( $record->userid );
            $ref_etablissement = trim( $record->ref_etablissement);
			$num_etudiant = trim( $record->num_etudiant);
			$ddn_etudiant = trim( $record->ddn_etudiant);
			$lieu_naissance = trim( $record->lieu_naissance);
			$departement_naissance = trim( $record->departement_naissance);
			$adresse_etudiant = trim( $record->adresse_etudiant);			

                $login = trim(referentiel_get_user_login($record->userid));

                if ($num_etudiant==$login){
                    $snum=$num_etudiant;
                }
                elseif ($num_etudiant==''){
                    $snum=$login;
                }
                else{
                    $snum=$num_etudiant." (".$login.")";
                }

			// Etablissement
			$record_etablissement=referentiel_get_etablissement($record->ref_etablissement);
	        if ($record_etablissement){
				msword_write_etablissement( $record_etablissement );
			}
			
			$mswd->SetFont('Arial','',10); 
			// DEBUG 
			
			$texte='<b>'.referentiel_get_user_info($record->userid).'</b><br />'.get_string('num_etudiant','referentiel').' : <i>'.$snum.'</i><br />'.'<br />'.get_string('ddn_etudiant','referentiel').' : '.$ddn_etudiant.'<br />'.get_string('lieu_naissance','referentiel').' : '.$lieu_naissance.'<br />'.get_string('departement_naissance','referentiel').' : '.$departement_naissance;
            $texte.='<br />'.get_string('adresse_etudiant','referentiel'). ' : '.$adresse_etudiant;
			$texte=recode_utf8_vers_latin1($texte);
			$mswd->WriteParagraphe(0,$texte);
			return true;
    }
return false;
}

        /**
     * Turns item into an xml segment
     * @param item object
     * @return string xml segment
     */

function msword_write_item( $item ) {
global $mswd;
    if ($item){
      $code = $item->code_item;
      $description_item = $item->description_item;
      $ref_referentiel = $item->ref_referentiel;
      $ref_competence = $item->ref_competence;
			$type_item = $item->type_item;
			$poids_item = $item->poids_item;
			$empreinte_item = $item->empreinte_item;
			$num_item = $item->num_item;
      $mswd->SetFont('Arial','B',9); 
   	  $mswd->Write(0, recode_utf8_vers_latin1(trim(stripslashes($code))));
 	   	$mswd->Ln(1);
 	   	$mswd->SetFont('Arial','I',9);
   	  $mswd->Write(0, recode_utf8_vers_latin1(trim(stripslashes($description_item))));
   	  $mswd->Ln(1);
   	  $mswd->SetFont('Arial','',9);
      $mswd->Write(0, recode_utf8_vers_latin1(trim(get_string('t_item','referentiel')." : ".$type_item.", ".get_string('p_item','referentiel')." : ".$poids_item.", ".get_string('e_item','referentiel')." : ".$empreinte_item)));
      $mswd->Ln(1);
    } 
}
    
	 /**
     * Turns competence into an xml segment
     * @param competence object
     * @return string xml segment
     */

function msword_write_competence( $competence ) {
global $mswd;
 		  if ($competence){
        $code = $competence->code_competence;
        $description_competence = $competence->description_competence;
        $ref_domaine = $competence->ref_domaine;
        $num_competence = $competence->num_competence;
			  $nb_item_competences = $competence->nb_item_competences;
        $mswd->SetFont('Arial','B',10); 
	   	  $mswd->Write(0,recode_utf8_vers_latin1(trim(get_string('competence','referentiel')." : ".stripslashes($code))));
        $mswd->Ln(1);
        $mswd->SetFont('Arial','',10); 
        $mswd->Write(0, recode_utf8_vers_latin1(trim(stripslashes($description_competence))));
	 	   	$mswd->Ln(1);
			  $mswd->Ln(1);
			  // ITEM
			  $records_items = referentiel_get_item_competences($competence->id);
        if ($records_items){				  
    	    $mswd->SetFont('Arial','B',10); 
	        $mswd->Write(0,recode_utf8_vers_latin1(trim(get_string('items','referentiel'))));
          $mswd->Ln(1);

				  foreach ($records_items as $record_i){
						msword_write_item( $record_i );
				  }
				  $mswd->Ln(1);
			   }
        }
}


	 /**
     * Turns domaine into an xml segment
     * @param domaine object
     * @return string xml segment
     */

function msword_write_domaine( $domaine ) {
global $mswd;
    
		if ($domaine){
      $code = $domaine->code_domaine;
      $description_domaine = $domaine->description_domaine;
      $ref_referentiel = $domaine->ref_referentiel;
			$num_domaine = $domaine->num_domaine;
			$nb_competences = $domaine->nb_competences;
 			$mswd->SetFont('Arial','B',10); 
   	  $mswd->Write(0,recode_utf8_vers_latin1(trim(get_string('domaine','referentiel')." : ".stripslashes($code))));
      $mswd->Ln(1);
      $mswd->SetFont('Arial','',10); 
   	  $mswd->Write(0, recode_utf8_vers_latin1(trim(stripslashes($description_domaine))));
 	   	$mswd->Ln(1);
			$mswd->Ln(1);
			// LISTE DES COMPETENCES DE CE DOMAINE
			$records_competences = referentiel_get_competences($domaine->id);
			if ($records_competences){
				foreach ($records_competences as $record_c){
          msword_write_competence( $record_c );
				}
			}
    }
}


	 /**
     * Turns referentiel instance into an xml segment
     * @param referentiel instanceobject
     * @return string xml segment
     */

function msword_write_referentiel( $referentiel_instance, $referentiel_referentiel, $param){
global $CFG;
global $mswd;
global $image_logo;
		
		$ok_saut_page=false;		
		if ($referentiel_instance && $referentiel_referentiel) {
      $name = recode_utf8_vers_latin1(trim($referentiel_referentiel->name));
      $code = recode_utf8_vers_latin1(trim($referentiel_referentiel->code_referentiel));
			$description = recode_utf8_vers_latin1(trim($referentiel_referentiel->description_referentiel));
			
			$id = $referentiel_instance->id;
      $name_instance = recode_utf8_vers_latin1(trim($referentiel_instance->name));
      $description_instance = recode_utf8_vers_latin1(trim($referentiel_instance->description_instance));
      $label_domaine = recode_utf8_vers_latin1(trim($referentiel_instance->label_domaine));
      $label_competence = recode_utf8_vers_latin1(trim($referentiel_instance->label_competence));
      $label_item = recode_utf8_vers_latin1(trim($referentiel_instance->label_item));
      $date_instance = $referentiel_instance->date_instance;
      $course = $referentiel_instance->course;
      $ref_referentiel = $referentiel_instance->ref_referentiel;
			$visible = $referentiel_instance->visible;
      $id = $referentiel_instance->id;
			
			// $mswd->SetDrawColor(128, 128, 128);    
			// $mswd->SetLineWidth(0.4);     
			// logo
			// $posy=$mswd->GetY();    
			
			//if (isset($image_logo) && ($image_logo!="")){
			//	$mswd->Image($image_logo,150,$posy,40);
			// }
			// $posy=$mswd->GetY()+60;    
           	
			$mswd->SetLeftMargin(15);
            // $mswd->SetX(20);
			
      
			$mswd->SetFont('Arial','B',14); 
		  $mswd->WriteParagraphe(0,get_string('certification','referentiel'));
			// $mswd->Ln(1);
			$mswd->SetFont('Arial','',12); 
		  $mswd->WriteParagraphe(0, $name.'('.$code.')');
			// $mswd->Ln(6);
			$mswd->SetFont('Arial','',10);
			$mswd->WriteParagraphe(0, $description);
			//$mswd->Ln(6);
      if ($param->certificat_sel_referentiel){				
				// DOMAINES
				// LISTE DES DOMAINES
				$compteur_domaine=0;
				$records_domaine = referentiel_get_domaines($referentiel_referentiel->id);
		    if ($records_domaine){
					foreach ($records_domaine as $record_d){
						msword_write_domaine($record_d );
					}
				}	
			  $ok_saut_page=true;        			
			} 

			if ($param->certificat_sel_referentiel_instance){
        $mswd->SetFont('Arial','B',10); 
			  $mswd->SetFont('Arial','B',14); 
			  $texte= recode_utf8_vers_latin1(get_string('certification','referentiel').' <i>'.$referentiel_instance->name.'</i>');
	    	$mswd->WriteParagraphe(0,$texte);
			
			  $mswd->SetFont('Arial','',12); 
			  $texte= "$name : $description_instance";
			  // $texte.= "$label_domaine, $label_competence,  $label_item";
			  $mswd->WriteParagraphe(0,$texte);
			
      /*
			$mswd->Ln(2);
			$mswd->Write(0,"Cours : $course");
			$mswd->Ln();
            $mswd->Write(0,"R�f�rentiel :  $ref_referentiel");
			$mswd->Ln();
            $mswd->Write(0,"Visible : $visible");
			$mswd->Ln();
			*/
			  $ok_saut_page=true;
      }
			if ($ok_saut_page==true){ // forcer le saut de page   
        $mswd->AddPage();
			}
      return true;
    }
		return false;
}


// -------------------
function msword_referentiel_affiche_certificat_consolide($ref_referentiel, $separateur1, $separateur2, $liste_code, $font1=10, $font2=9, $font3=8, $params=NULL){
// ce certificat comporte des pourcentages par domaine et competence
// decalque de referentiel_affiche_certificat_consolide() de lib.php
global $mswd;

global $OK_REFERENTIEL_DATA;
global $t_domaine;
global $t_domaine_coeff;
		
// COMPETENCES
global $t_competence;
global $t_competence_coeff;
		
// ITEMS
global $t_item_code;
global $t_item_coeff; // coefficient poids determeine par le modele de calcul (soit poids soit poids / empreinte)
global $t_item_domaine; // index du domaine associ� � un item 
global $t_item_competence; // index de la competence associ�e � un item 
global $t_item_poids; // poids
global $t_item_empreinte;
global $t_nb_item_domaine;
global $t_nb_item_competence;

	// nom des domaines, comp�tences, items
	$label_d="";
	$label_c="";
	$label_i="";
	if (isset($params) && !empty($params)){
		if (isset($params->label_domaine)){
					$label_d=$params->label_domaine;
		}
		if (isset($params->label_competence)){
					$label_c=$params->label_competence;
		}
		if (isset($params->label_item)){
					$label_i=$params->label_item;
		}
	}
	$t_certif_item_valeur=array();	// table des nombres d'items valides 
	$t_certif_item_coeff=array(); // somme des poids du domaine
	$t_certif_competence_poids=array(); // somme des poids de la competence
	$t_certif_domaine_poids=array(); // poids certifies
	for ($i=0; $i<count($t_item_code); $i++){
		$t_certif_item_valeur[$i]=0.0;
		$t_certif_item_coeff[$i]=0.0;
	}
	for ($i=0; $i<count($t_competence); $i++){
		$t_certif_competence_poids[$i]=0.0;
	}
	for ($i=0; $i<count($t_domaine); $i++){
		$t_certif_domaine_poids[$i]=0.0;
	}
	// affichage
	$s='';
		
	// donnees globales du referentiel
	if ($ref_referentiel){
		
		if (!isset($OK_REFERENTIEL_DATA) || ($OK_REFERENTIEL_DATA==false) ){
			$OK_REFERENTIEL_DATA=referentiel_initialise_data_referentiel($ref_referentiel);
		}

		if (isset($OK_REFERENTIEL_DATA) && ($OK_REFERENTIEL_DATA==true)){		
		// recuperer les items valides
		$tc=array();
		$liste_code=referentiel_purge_dernier_separateur($liste_code, $separateur1);

		if (!empty($liste_code) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste_code);
			for ($i=0; $i<count($t_item_domaine); $i++){
				$t_certif_domaine_poids[$i]=0.0;
			}
			for ($i=0; $i<count($t_item_competence); $i++){
				$t_certif_competence_poids[$i]=0.0;
			}

			$i=0;
			while ($i<count($tc)){
				$t_cc=explode($separateur2, $tc[$i]); // tableau des items valides
				if (isset($t_cc[1])){
					if (isset($t_item_poids[$i]) && isset($t_item_empreinte[$i])){
						if (($t_item_poids[$i]>0) && ($t_item_empreinte[$i]>0)){
							// echo "<br />".min($t_cc[1],$t_item_empreinte[$i]);
							$t_certif_item_valeur[$i]=min($t_cc[1],$t_item_empreinte[$i]);
							// calculer le taux
							$coeff=(float)$t_certif_item_valeur[$i] * (float)$t_item_coeff[$i];
							// stocker la valeur pour l'item
							$t_certif_item_coeff[$i]=$coeff;
							// stocker le taux pour la competence
							$t_certif_domaine_poids[$t_item_domaine[$i]]+=$coeff;
							// stocker le taux pour le domaine
							$t_certif_competence_poids[$t_item_competence[$i]]+=$coeff;
						}
						else{
							// echo "<br />".min($t_cc[1],$t_item_empreinte[$i]);
							$t_certif_item_valeur[$i]=0.0;
							$t_certif_item_coeff[$i]=0.0;
							// $t_certif_domaine_poids[$t_item_domaine[$i]]+=0.0;
							// $t_certif_competence_poids[$t_item_competence[$i]]+=0.0;
						}
					}
				}
				
				$i++;
			}
			
			// Affichage
			// DOMAINES
      $mswd->SetFont('Arial','B',$font1);
      $mswd->Write(1,recode_utf8_vers_latin1(get_string('domaine','referentiel')));
      $mswd->Ln(1);
      $nd=count($t_domaine_coeff);
			$espaced=40 / $nd;
      // $s.= '<table width="100%" cellspacing="0" cellpadding="2"><tr valign="top" >'."\n";
			for ($i=0; $i<$nd; $i++){
				if ($t_domaine_coeff[$i]){
					// $s.='<td  align="center" colspan="'.$t_nb_item_domaine[$i].'"><b>'.$t_domaine[$i].'</b> ('.referentiel_pourcentage($t_certif_domaine_poids[$i], $t_domaine_coeff[$i]).'%)</td>';
          $mswd->SetFont('Arial','',$font2);
          for ($j=0; $j < $espaced; $j++){
            $mswd->Write(1," ");
          }
          $mswd->SetFont('Arial','B',$font2);
          $mswd->Write(1,$t_domaine[$i]);
          $mswd->SetFont('Arial','',$font3);
          $mswd->Write(1," (".referentiel_pourcentage($t_certif_domaine_poids[$i], $t_domaine_coeff[$i])."%) ");
        }
				else{
					// $s.='<td  align="center" colspan="'.$t_nb_item_domaine[$i].'"><b>'.$t_domaine[$i].'</b> (0%)</td>';
				  $mswd->SetFont('Arial','',$font2);
           for ($j=0; $j < $espaced; $j++){
            $mswd->Write(1,"   ");
          }
          $mswd->SetFont('Arial','B',$font2);
          $mswd->Write(1, $t_domaine[$i]);
          $mswd->SetFont('Arial','',$font3);
          $mswd->Write(1," (0%) ");
        }
			}
			//$s.='</tr>'."\n";
      $mswd->Ln(1);
      $mswd->SetFont('Arial','B',$font1);
      $mswd->Write(1,recode_utf8_vers_latin1(get_string('competence','referentiel')));
      $mswd->Ln(1);
      
      $nc=count($t_competence);
			$espacec= 40 / $nc;

			// $s.=  '<tr valign="top"  >'."\n";
			for ($i=0; $i<$nc; $i++){
				if ($t_competence_coeff[$i]){
					// $s.='<td align="center" colspan="'.$t_nb_item_competence[$i].'"><b>'.$t_competence[$i].'</b> ('.referentiel_pourcentage($t_certif_competence_poids[$i], $t_competence_coeff[$i]).'%)</td>'."\n";
				  $mswd->SetFont('Arial','',$font2);
          for ($j=0; $j < $espacec; $j++){
            $mswd->Write(1," ");
          }
          $mswd->SetFont('Arial','B',$font2);
          $mswd->Write(1, $t_competence[$i]);
          $mswd->SetFont('Arial','',$font3);
          $mswd->Write(1," (".referentiel_pourcentage($t_certif_competence_poids[$i], $t_competence_coeff[$i])."%) ");									
				}
				else{
					// $s.='<td align="center" colspan="'.$t_nb_item_competence[$i].'"><b>'.$t_competence[$i].'</b> (0%)</td>'."\n";
				  $mswd->SetFont('Arial','',$font2);
          for ($j=0; $j < $espacec; $j++){
            $mswd->Write(1," ");
          }
          $mswd->SetFont('Arial','B',$font2);
          $mswd->Write(1, $t_competence[$i]);
          $mswd->SetFont('Arial','',$font3);
          $mswd->Write(1," (0%) ");					
				}
			}
			// $s.='</tr>'."\n";
			$mswd->Ln(1);
						
			// ITEMS
      $mswd->SetFont('Arial','B',$font1);
      $mswd->Write(1,recode_utf8_vers_latin1(get_string('item','referentiel')));
      $mswd->Ln(1);
			
			// $s.= '<tr valign="top" >'."\n";
			for ($i=0; $i<count($t_item_code); $i++){
				if ($t_item_empreinte[$i]){
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]) {
						// $s.='<td'.$bgcolor.'><span  class="valide">'.$t_item_code[$i].'</span></td>'."\n";
				    $mswd->SetFont('Arial','B',$font2);
            $mswd->Write(1,$t_item_code[$i]." ");
					}	
					else {
						// $s.='<td'.$bgcolor.'><span class="invalide">'.$t_item_code[$i].'</span></td>'."\n";
				    $mswd->SetFont('Arial','',$font2);
            $mswd->Write(1,$t_item_code[$i]." ");
          }
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]){
						// $s.='<td'.$bgcolor.'><span class="valide">100%</span></td>'."\n";
				    $mswd->SetFont('Arial','B',$font3);
            $mswd->Write(1,"(100%) ");				
					}
					else{
						// $s.='<td'.$bgcolor.'><span class="invalide">'.referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i]).'%</span></td>'."\n";
				    $mswd->SetFont('Arial','',$font3);
            $mswd->Write(1,"(".referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i])."%) ");				
					}  
				}
				else{
					// $s.='<td class="nondefini"><span class="nondefini"><i>'.$t_item_code[$i].'</i></span></td>'."\n";
				    $mswd->SetFont('Arial','I',$font2);
            $mswd->Write(1,$t_item_code[$i]." ");		
				}
			}
			// $s.='</tr><tr valign="top" >'."\n";
			$mswd->Ln(1);
			/*
      // <td  width="5%">'.get_string('coeff','referentiel').'</td>'."\n";
			for ($i=0; $i<count($t_item_coeff); $i++){
				if ($t_item_empreinte[$i]){
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]){
						// $s.='<td'.$bgcolor.'><span class="valide">100%</span></td>'."\n";
				    $mswd->SetFont('Arial','B',$font1);
            $mswd->Write(1,"   100% ");				
					}
					else{
						// $s.='<td'.$bgcolor.'><span class="invalide">'.referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i]).'%</span></td>'."\n";
				    $mswd->SetFont('Arial','',$font1);
            $mswd->Write(1,"    ".referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i])." ");				
					}
				}
				else {
					// $s.='<td class="nondefini"><span class="nondefini">&nbsp;</span></td>'."\n";
				}
			}
			// $s.='</tr></table>'."\n";
		
			*/
				$mswd->Ln(1);
		}
	}
	}
	return $s;
}

// ----------------------------------------------------
function msword_referentiel_affiche_detail_competences($separateur1, $separateur2, $liste, $liste_empreintes, $liste_poids, $font1=10, $font2=9){
// decalque de referentiel_affiche_detail_competences() de print_lib_certificat.php
global $mswd;

	$t_empreinte=explode($separateur1, $liste_empreintes);
	$t_poids=explode('|', $liste_poids);	

	$tc=array();
	$liste=referentiel_purge_dernier_separateur($liste, $separateur1);
		if (!empty($liste) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste);
			$i=0;
			while ($i<count($tc)){
				if ($tc[$i]!=''){
					$tcc=explode($separateur2, $tc[$i]);					
					if (isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
            $mswd->SetFont('Arial','B',$font1);
          }
					else{
            $mswd->SetFont('Arial','I',$font1);
					}
          $mswd->Write(1,$tcc[0]." : ");
          $mswd->SetFont('Arial','',$font2);
          $mswd->Write(1," ".recode_utf8_vers_latin1(str_replace('#',"<br />".get_string('p_item','referentiel').":",$t_poids[$i])." ".get_string('approved','referentiel').":".$tcc[1]." ".get_string('e_item','referentiel').":".$t_empreinte[$i]." "));						
					$mswd->Ln(1);
				}
				$i++;
			} 
		}
	  $mswd->Ln(1);
}



// ----------------------------------------------------
function msword_liste_competences_certificat($referentiel_id, $separateur1, $separateur2, $liste, $liste_empreintes, $all=0, $font1=10, $font2=9){
global $mswd;
global $copyright;
global $registere;
global $puce;
// Affiche les codes competences en tenant compte de l'empreinte
	$t_empreinte=explode($separateur1, $liste_empreintes);
	
		$tc=array();
		$liste=referentiel_purge_dernier_separateur($liste, $separateur1);
		if (!empty($liste) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste);
			$i=0;
			while ($i<count($tc)){
				$tcc=explode($separateur2, $tc[$i]);
				if ($referentiel_id){
					$descriptif_item=recode_utf8_vers_latin1(referentiel_get_description_item($tcc[0], $referentiel_id));
				}
				else{
					$descriptif_item='';
				}
				if (isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
					$mswd->SetFont('Arial','B',$font1); 
					$mswd->Write(1, "    $puce ".$tcc[0]);
					$mswd->SetFont('Arial','',$font2);
					$mswd->Write(1," : $descriptif_item");
					$mswd->Ln(1);
				}
				else if ($all){
					$mswd->SetFont('Arial','I',$font1); 
					$mswd->Write(1, "     $puce ".$tcc[0]);
					$mswd->SetFont('Arial','',$font2);
					$mswd->Write(1," : $descriptif_item");
					$mswd->Ln(1);
				}
				$i++;
			} 
		}
}
	


	 /**
     * Turns referentiel instance into an xml segment
     * @param referentiel instanceobject
     * @return string xml segment
     */
	function msword_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param) {
    	global $CFG;
		global $mswd;
    	// add comment and div tags
		
		if ($record){
			// DEBUG
			// echo "DEBUG LIGNE 1021";
			// print_r($referentiel_instance);
			$id = trim( $record->id );
            $commentaire_certificat = recode_utf8_vers_latin1(trim($record->commentaire_certificat));
            $synthese_certificat = recode_utf8_vers_latin1(trim($record->synthese_certificat));
			$competences_activite =  recode_utf8_vers_latin1(trim($record->competences_activite)) ;
            $competences_certificat =  recode_utf8_vers_latin1(trim($record->competences_certificat)) ;
            $decision_jury = recode_utf8_vers_latin1(trim($record->decision_jury));
			if ($record->date_decision){
	      $date_decision = userdate(trim($record->date_decision));
			}
			else{
				$date_decision ="";
			}
      $userid = trim( $record->userid);
      $teacherid = trim( $record->teacherid);
			if ($teacherid!=0){
				$nom_prenom_teacher=recode_utf8_vers_latin1(referentiel_get_user_info($teacherid));
			}
			else{
				$nom_prenom_teacher="";
			}
      
      $ref_referentiel = trim( $record->ref_referentiel);
			// $ref_referentiel=$referentiel_id;
			$verrou = trim( $record->verrou );
			$valide = trim( $record->valide );
			$evaluation = trim( $record->evaluation );
			
			
			// USER
			if (isset($record->userid) && ($record->userid>0)){
				$record_etudiant = referentiel_get_etudiant_user($record->userid);
		    	if ($record_etudiant){
					
					$mswd->SetLeftMargin(15);
					
					msword_write_referentiel($referentiel_instance, $referentiel_referentiel, $param);
					
					msword_write_etudiant( $record_etudiant, $param);
					
					$mswd->SetFont('Arial','',12);
					if ($param->certificat_sel_decision_jury){
						if (($date_decision!="") && ($decision_jury!="")){
							$mswd->Write(1,$decision_jury);
						}
						$mswd->Ln(1);
					}
					
					// $mswd->SetFont('Arial','B',10); 
					// $mswd->Write(1,"ID : ");
					// $mswd->SetFont('Arial','',10);
					// $mswd->Write(1,"$id");
					// $mswd->Ln(1);
					
					$mswd->SetFont('Arial','B',12); 
        	$mswd->Write(1,recode_utf8_vers_latin1(get_string('competences','referentiel')).": ");
					$mswd->Ln(1);
					if ($param->certificat_sel_activite_competences){
						$mswd->SetFont('Arial','B',9); 
	        	$mswd->Write(1,recode_utf8_vers_latin1(get_string('competences_activite','referentiel')).": ");
						$mswd->Ln(1); 
    	    	msword_liste_competences_certificat($ref_referentiel, '/',':', $competences_activite, $liste_empreintes, 0, 9, 8);
						$mswd->Ln(1);
					}
					if ($param->certificat_sel_certificat_competences){
						$mswd->SetFont('Arial','B',10); 
	        	$mswd->Write(1,recode_utf8_vers_latin1(get_string('competences_certificat','referentiel')).": ");
						$mswd->Ln(1);
    	    	msword_liste_competences_certificat($ref_referentiel, '/',':', $competences_certificat, $liste_empreintes,0,10,9);
						$mswd->Ln(1);
					}
					if (($param->certificat_sel_certificat_competences) 
            && ($param->certificat_sel_certificat_detail)){
						msword_referentiel_affiche_detail_competences('/',':',$competences_certificat, $liste_empreintes, $liste_poids);
          }					
					if ($param->certificat_sel_certificat_pourcent){
            // $mswd->SetFont('Arial','B',10);
    	    	// $mswd->Write(1,recode_utf8_vers_latin1(get_string('pourcentage','referentiel'))." :");
					  // $mswd->Ln(1);
					  msword_referentiel_affiche_certificat_consolide($ref_referentiel, '/',':', $competences_certificat, 10,9,8);
					}
					
					if ($param->certificat_sel_commentaire){
						$mswd->SetFont('Arial','B',10);
    	    	$mswd->Write(1,recode_utf8_vers_latin1(get_string('commentaire','referentiel')).": ");
						$mswd->SetFont('Arial','',10);
    	    	$mswd->Write(1,"$commentaire_certificat ");			
						$mswd->Ln(1);
						$mswd->SetFont('Arial','B',10);
    	    	$mswd->Write(1,recode_utf8_vers_latin1(get_string('synthese_certificat','referentiel')).": ");
						$mswd->SetFont('Arial','',10);
    	    	$mswd->Write(1,"$synthese_certificat ");
						$mswd->Ln(1);
					}
					if ($param->certificat_sel_decision_jury){
						$mswd->SetFont('Arial','B',10);
			      $mswd->Write(1, recode_utf8_vers_latin1(get_string('decision','referentiel'))." : ");
						$mswd->SetFont('Arial','',10);
		    	  $mswd->Write(1,"$decision_jury");
						$mswd->Ln(1);
					}
					if ($param->certificat_sel_certificat_referents){
						$mswd->SetFont('Arial','B',10);
						$mswd->Write(1,recode_utf8_vers_latin1(get_string('enseignant','referentiel'))." : ");
						$mswd->SetFont('Arial','',10);
						$mswd->Write(1,$nom_prenom_teacher);
						$mswd->Ln(1);
					}
					/*
					$mswd->Write(1," R�f�rentiel : $ref_referentiel");
					$mswd->Ln(1);
		            $mswd->Write(1," Verrou : $verrou, Valide : $valide, Evaluation : $evaluation");
					$mswd->Ln(1);
					*/
					$mswd->Ln(20);
					$mswd->Writeparagraphe(1, get_string('date_signature','referentiel', date("d/m/Y")));
					$mswd->AddPage();
				}
			}
		}
		return "";
    }
	

    function msword_write_certification($referentiel_instance, $referentiel_referentiel,  $userid=0, $param, $records_certificats) {
    	global $CFG;
		global $mswd;
		
		if ($referentiel_instance && $referentiel_referentiel) {
			// CERTIFICATS
			if (isset($referentiel_instance->ref_referentiel) && ($referentiel_instance->ref_referentiel>0)){
				// les empreintes
				$liste_empreintes = referentiel_purge_dernier_separateur(referentiel_get_liste_empreintes_competence($referentiel_instance->ref_referentiel), '/');
    		$liste_poids=referentiel_purge_dernier_separateur(referentiel_get_liste_poids($referentiel_instance->ref_referentiel), '|');
				
        if ($userid>0){
					$record = referentiel_get_certificat_user($userid, $referentiel_instance->ref_referentiel);
					msword_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param);
				}
				else {
					if (!$records_certificats){
            $records_certificats = referentiel_get_certificats($referentiel_instance->ref_referentiel);
					}
          if ($records_certificats){
						foreach ($records_certificats as $record){
							msword_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param);
						}
					}
				}
				// print_r($records_certificats);
		    	// exit;
			}
		}
		return "";
	}

?>