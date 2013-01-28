<?php // $Id: print_pdf.php,v 2.0.0.0 2009/12/14 11:32:00 jf Exp $

/**
 * file print_pdf.php
 * print pdf certificate
 */

     
// traitement des chaines de caracteres
require_once('textlib.php');

// PDF    
define('FPDF_FONTPATH', $CFG->libdir .'/fpdf/font/');
require_once($CFG->libdir .'/fpdf/fpdf.php');
// echo $CFG->libdir .'/fpdf/fpdf.php';

// PDF    
// define('FPDF_FONTPATH', 'fpdf/font/');
// require_once('fpdf/fpdf.php');
// echo $CFG->libdir .'/fpdf/fpdf.php';


class PDF extends FPDF
{
// Une colonnes
var $col=0;

function SetCol($col)
{
    //Move position to a column
    $this->col=$col;
    $x=15+$col*95;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
// passage a la colonne / page suivante
/*   
   if($this->col<1)
    {
        //Go to next column
   $this->SetCol($this->col+1);
   $this->SetY(34);
   return false;
   }
   else
   {
*/  
        //Go back to first column and issue page break
        $this->SetCol(0);
        return true;
/*
  }
*/	
}


//En-tête
function Header()
{
// RAS

    // Police Arial 9
    $this->SetFont('Arial','',9);
    // Décalage à droite
    $this->Cell(80);
    // Texte
    $this->Cell(120,0,'Certificat',0,0,'L');

}

//Pied de page
function Footer()
{

    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    //Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

}

// affiche element
function affiche_element($X, $Y, $largeur, $texte, $cadre=0, $alignement="L", $remplissage=0) {
        $this->SetXY($X,$Y);
        $this->MultiCell($largeur,6,$texte,$cadre,$alignement,$remplissage);
    } // fin affiche element

}


	
	/**
     * generates <text></text> tags, processing raw text therein 
     * @param int ilev the current indent level
     * @param boolean short stick it on one line
     * @return string printted text
     */



function pdf_write_etablissement( $record ) {
    // initial string;
	global $pdf;
		if ($record){
			$id = trim( $record->id );
			$num_etablissement = trim( $record->num_etablissement);
			$nom_etablissement = trim( $record->nom_etablissement);
			$adresse_etablissement = trim( $record->adresse_etablissement);
			$logo=$record->logo_etablissement;
			$pdf->SetFont('Arial','',10); 
			$texte=recode_utf8_vers_latin1(get_string('num_etablissement','referentiel').' : '.$num_etablissement);
			$pdf->Write(6,$texte);
			$pdf->Ln(6);
			$pdf->SetFont('Arial','B',12); 
			$texte=recode_utf8_vers_latin1(get_string('nom_etablissement','referentiel').' : '.$nom_etablissement);
			$pdf->Write(6,$texte);
			$pdf->Ln(6);
			$texte=recode_utf8_vers_latin1(get_string('adresse_etablissement','referentiel').' : '.$adresse_etablissement);
			$pdf->SetFont('Arial','',10); 
			$pdf->Write(6,$texte);
			$pdf->Ln(10);
    }

 }
	
function pdf_write_etudiant( $record, $param ) {
	global $pdf;
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
				if ($param->certificat_sel_etudiant_etablissement){
					pdf_write_etablissement( $record_etablissement );
				}
			}
			if ($param->certificat_sel_etudiant_numero){
                $pdf->SetFont('Arial','',10);
                $texte=recode_utf8_vers_latin1(get_string('num_etudiant','referentiel')." : ".$snum);
				$pdf->Write(6,$texte);
				$pdf->Ln(6);
			}
			
			if ($param->certificat_sel_etudiant_nom_prenom){
				$pdf->SetFont('Arial','',12); 
				$pdf->Write(6,recode_utf8_vers_latin1(referentiel_get_user_info($record->userid)));
				$pdf->SetFont('Arial','',10); 
				$pdf->Ln(6);
			}
			if ($param->certificat_sel_etudiant_ddn || $param->certificat_sel_etudiant_lieu_naissance){
				$texte='';
				if ($param->certificat_sel_etudiant_ddn){
					$texte.=recode_utf8_vers_latin1(get_string('ddn_etudiant','referentiel')." ".$ddn_etudiant." ");
				}
				if ($param->certificat_sel_etudiant_lieu_naissance){
					$texte.=recode_utf8_vers_latin1(get_string('lieu_naissance','referentiel')." : ".$lieu_naissance.", ".get_string('departement_naissance','referentiel')." : ".$departement_naissance);
				}
				$pdf->Write(6,$texte);
				$pdf->Ln(6);
            }
			if ($param->certificat_sel_etudiant_adresse){
				$texte=recode_utf8_vers_latin1(get_string('adresse_etudiant','referentiel'). " : ".$adresse_etudiant);
				$pdf->Write(6, $texte);
				$pdf->Ln(6);
			}
    }
}


// -------------------
function pdf_referentiel_affiche_certificat_consolide($ref_referentiel, $separateur1, $separateur2, $liste_code, $font1=10, $font2=9, $font3=8, $params=NULL){
// ce certificat comporte des pourcentages par domaine et competence
// decalque de referentiel_affiche_certificat_consolide() de lib.php
global $pdf;

global $OK_REFERENTIEL_DATA;
global $t_domaine;
global $t_domaine_coeff;
		
// COMPETENCES
global $t_competence;
global $t_competence_coeff;
		
// ITEMS
global $t_item_code;
global $t_item_coeff; // coefficient poids determeine par le modele de calcul (soit poids soit poids / empreinte)
global $t_item_domaine; // index du domaine associé à un item 
global $t_item_competence; // index de la competence associée à un item 
global $t_item_poids; // poids
global $t_item_empreinte;
global $t_nb_item_domaine;
global $t_nb_item_competence;

	// nom des domaines, compétences, items
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
      $pdf->SetFont('Arial','B',$font1);
      $pdf->Write(6,recode_utf8_vers_latin1(get_string('domaine','referentiel')));
      $pdf->Ln(6);
      $nd=count($t_domaine_coeff);
			$espaced=80 / $nd;
      // $s.= '<table width="100%" cellspacing="0" cellpadding="2"><tr valign="top" >'."\n";
			for ($i=0; $i<$nd; $i++){
				if ($t_domaine_coeff[$i]){
					// $s.='<td  align="center" colspan="'.$t_nb_item_domaine[$i].'"><b>'.$t_domaine[$i].'</b> ('.referentiel_pourcentage($t_certif_domaine_poids[$i], $t_domaine_coeff[$i]).'%)</td>';
          $pdf->SetFont('Arial','',$font2);
          for ($j=0; $j < $espaced; $j++){
            $pdf->Write(6," ");
          }
          $pdf->SetFont('Arial','B',$font2);
          $pdf->Write(6,$t_domaine[$i]);
          $pdf->SetFont('Arial','',$font3);
          $pdf->Write(6," (".referentiel_pourcentage($t_certif_domaine_poids[$i], $t_domaine_coeff[$i])."%) ");
        }
				else{
					// $s.='<td  align="center" colspan="'.$t_nb_item_domaine[$i].'"><b>'.$t_domaine[$i].'</b> (0%)</td>';
				  $pdf->SetFont('Arial','',$font2);
           for ($j=0; $j < $espaced; $j++){
            $pdf->Write(6,"   ");
          }
          $pdf->SetFont('Arial','B',$font2);
          $pdf->Write(6, $t_domaine[$i]);
          $pdf->SetFont('Arial','',$font3);
          $pdf->Write(6," (0%) ");
        }
			}
			//$s.='</tr>'."\n";
      $pdf->Ln(6);
      $pdf->SetFont('Arial','B',$font1);
      $pdf->Write(6,recode_utf8_vers_latin1(get_string('competence','referentiel')));
      $pdf->Ln(6);
      
      $nc=count($t_competence);
			$espacec= 80 / $nc;

			// $s.=  '<tr valign="top"  >'."\n";
			for ($i=0; $i<$nc; $i++){
				if ($t_competence_coeff[$i]){
					// $s.='<td align="center" colspan="'.$t_nb_item_competence[$i].'"><b>'.$t_competence[$i].'</b> ('.referentiel_pourcentage($t_certif_competence_poids[$i], $t_competence_coeff[$i]).'%)</td>'."\n";
				  $pdf->SetFont('Arial','',$font2);
          for ($j=0; $j < $espacec; $j++){
            $pdf->Write(6," ");
          }
          $pdf->SetFont('Arial','B',$font2);
          $pdf->Write(6, $t_competence[$i]);
          $pdf->SetFont('Arial','',$font3);
          $pdf->Write(6," (".referentiel_pourcentage($t_certif_competence_poids[$i], $t_competence_coeff[$i])."%) ");									
				}
				else{
					// $s.='<td align="center" colspan="'.$t_nb_item_competence[$i].'"><b>'.$t_competence[$i].'</b> (0%)</td>'."\n";
				  $pdf->SetFont('Arial','',$font2);
          for ($j=0; $j < $espacec; $j++){
            $pdf->Write(6," ");
          }
          $pdf->SetFont('Arial','B',$font2);
          $pdf->Write(6, $t_competence[$i]);
          $pdf->SetFont('Arial','',$font3);
          $pdf->Write(6," (0%) ");					
				}
			}
			// $s.='</tr>'."\n";
			$pdf->Ln(6);
						
			// ITEMS
      $pdf->SetFont('Arial','B',$font1);
      $pdf->Write(6,recode_utf8_vers_latin1(get_string('item','referentiel')));
      $pdf->Ln(6);
			
			// $s.= '<tr valign="top" >'."\n";
			for ($i=0; $i<count($t_item_code); $i++){
				if ($t_item_empreinte[$i]){
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]) {
						// $s.='<td'.$bgcolor.'><span  class="valide">'.$t_item_code[$i].'</span></td>'."\n";
				    $pdf->SetFont('Arial','B',$font2);
            $pdf->Write(6,$t_item_code[$i]." ");
					}	
					else {
						// $s.='<td'.$bgcolor.'><span class="invalide">'.$t_item_code[$i].'</span></td>'."\n";
				    $pdf->SetFont('Arial','',$font2);
            $pdf->Write(6,$t_item_code[$i]." ");
          }
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]){
						// $s.='<td'.$bgcolor.'><span class="valide">100%</span></td>'."\n";
				    $pdf->SetFont('Arial','B',$font3);
            $pdf->Write(6,"(100%) ");				
					}
					else{
						// $s.='<td'.$bgcolor.'><span class="invalide">'.referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i]).'%</span></td>'."\n";
				    $pdf->SetFont('Arial','',$font3);
            $pdf->Write(6,"(".referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i])."%) ");				
					}  
				}
				else{
					// $s.='<td class="nondefini"><span class="nondefini"><i>'.$t_item_code[$i].'</i></span></td>'."\n";
				    $pdf->SetFont('Arial','I',$font2);
            $pdf->Write(6,$t_item_code[$i]." ");		
				}
			}
			// $s.='</tr><tr valign="top" >'."\n";
			$pdf->Ln(6);
			/*
      // <td  width="5%">'.get_string('coeff','referentiel').'</td>'."\n";
			for ($i=0; $i<count($t_item_coeff); $i++){
				if ($t_item_empreinte[$i]){
					if ($t_certif_item_valeur[$i]>=$t_item_empreinte[$i]){
						// $s.='<td'.$bgcolor.'><span class="valide">100%</span></td>'."\n";
				    $pdf->SetFont('Arial','B',$font1);
            $pdf->Write(6,"   100% ");				
					}
					else{
						// $s.='<td'.$bgcolor.'><span class="invalide">'.referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i]).'%</span></td>'."\n";
				    $pdf->SetFont('Arial','',$font1);
            $pdf->Write(6,"    ".referentiel_pourcentage($t_certif_item_valeur[$i], $t_item_empreinte[$i])." ");				
					}
				}
				else {
					// $s.='<td class="nondefini"><span class="nondefini">&nbsp;</span></td>'."\n";
				}
			}
			// $s.='</tr></table>'."\n";
		
			*/
				$pdf->Ln(6);
		}
	}
	}
}

// ----------------------------------------------------
function pdf_referentiel_affiche_detail_competences($separateur1, $separateur2, $liste, $liste_empreintes, $liste_poids, $font1=10, $font2=9){
// decalque de referentiel_affiche_detail_competences() de print_lib_certificat.php
global $pdf;

	$t_empreinte=explode($separateur1, $liste_empreintes);
	$t_poids=explode('|', $liste_poids);	
	// DEBUG
	// echo "<br />DEBUG : print_lib_certificat.php :: 105<br />LISTE EMPREINTES : $liste_empreintes<br />\n";
	// print_r($t_empreinte);
	// DEBUG
	// echo "<br />DEBUG : print_lib_certificat.php :: 108<br />LISTE POIDS : $liste_poids<br />\n";
	// print_r($t_poids);
	// exit;

	$tc=array();
	$liste=referentiel_purge_dernier_separateur($liste, $separateur1);
		if (!empty($liste) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste);
			$i=0;
			while ($i<count($tc)){
				if ($tc[$i]!=''){
					$tcc=explode($separateur2, $tc[$i]);					
					if (isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
            $pdf->SetFont('Arial','B',$font1);
          }
					else{
            $pdf->SetFont('Arial','I',$font1);
					}
          $pdf->Write(6,$tcc[0]." : ");
          $pdf->SetFont('Arial','',$font2);
          $pdf->Write(6," ".recode_utf8_vers_latin1(str_replace('#',"\n".get_string('p_item','referentiel').":",$t_poids[$i])." ".get_string('approved','referentiel').":".$tcc[1]." ".get_string('e_item','referentiel').":".$t_empreinte[$i]." "));						
					$pdf->Ln(6);
				}
				$i++;
			} 
		}
	  $pdf->Ln(6);
}



// ----------------------------------------------------
function pdf_liste_competences_certificat($referentiel_id, $separateur1, $separateur2, $liste, $liste_empreintes, $all=0, $font1=10, $font2=9){
global $pdf;
global $copyright;
global $registere;
global $puce;
// Affiche les codes competences en tenant compte de l'empreinte
	$t_empreinte=explode($separateur1, $liste_empreintes);
	
		$tc=array();
		$liste=referentiel_purge_dernier_separateur($liste, $separateur1);
		if (!empty($liste) && ($separateur1!="") && ($separateur2!="")){
			$tc = explode ($separateur1, $liste);
			// DEBUG 
			// echo "<br />CODE <br />\n";
			// print_r($tc);
			$i=0;
			while ($i<count($tc)){
				// CODE1:N1
				// DEBUG 
				// echo "<br />".$tc[$i]." <br />\n";
				// exit;
				$tcc=explode($separateur2, $tc[$i]);
				// echo "<br />CODE : ".$tc[$i]." <br />\n";
				// echo "<br />REFERENTIEL ID : ".$referentiel_id." <br />\n";
				// print_r($tcc);
				
				// exit;
				if ($referentiel_id){
					$descriptif_item=recode_utf8_vers_latin1(referentiel_get_description_item($tcc[0], $referentiel_id));
				}
				else{
					$descriptif_item='';
				}
				if (isset($tcc[1]) && ($tcc[1]>=$t_empreinte[$i])){
					$pdf->SetFont('Arial','B',$font1); 
					$pdf->Write(6, "    $puce ".$tcc[0]);
					$pdf->SetFont('Arial','',$font2);
					$pdf->Write(6," : $descriptif_item");
					$pdf->Ln(6);
				}
				else if ($all){
					$pdf->SetFont('Arial','I',$font1); 
					$pdf->Write(6, "     $puce ".$tcc[0]);
					$pdf->SetFont('Arial','',$font2);
					$pdf->Write(6," : $descriptif_item");
					$pdf->Ln(6);
				}
				$i++;
			} 
		}
}

    /**
     * Turns item into an xml segment
     * @param item object
     * @return string xml segment
     */

function pdf_write_item( $item ) {
    global $pdf;
    if ($item){
      $code = $item->code_item;
      $description_item = $item->description_item;
      $ref_referentiel = $item->ref_referentiel;
      $ref_competence = $item->ref_competence;
			$type_item = $item->type_item;
			$poids_item = $item->poids_item;
			$empreinte_item = $item->empreinte_item;
			$num_item = $item->num_item;
      $pdf->SetFont('Arial','B',9); 
   	  $pdf->Write(6, recode_utf8_vers_latin1(trim(stripslashes($code))));
 	   	$pdf->Ln(6);
 	   	$pdf->SetFont('Arial','I',9);
   	  $pdf->Write(6, recode_utf8_vers_latin1(trim(stripslashes($description_item))));
   	  $pdf->Ln(6);
   	  $pdf->SetFont('Arial','',9);
      $pdf->Write(6, recode_utf8_vers_latin1(trim(get_string('t_item','referentiel')." : ".$type_item.", ".get_string('p_item','referentiel')." : ".$poids_item.", ".get_string('e_item','referentiel')." : ".$empreinte_item)));
      $pdf->Ln(6);
    } 
}
    
	 /**
     * Turns competence into an xml segment
     * @param competence object
     * @return string xml segment
     */

function pdf_write_competence( $competence ) {
    global $pdf;
 		  if ($competence){
        $code = $competence->code_competence;
        $description_competence = $competence->description_competence;
        $ref_domaine = $competence->ref_domaine;
        $num_competence = $competence->num_competence;
			  $nb_item_competences = $competence->nb_item_competences;
        $pdf->SetFont('Arial','B',10); 
	   	  $pdf->Write(6,recode_utf8_vers_latin1(trim(get_string('competence','referentiel')." : ".stripslashes($code))));
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',10); 
        $pdf->Write(6, recode_utf8_vers_latin1(trim(stripslashes($description_competence))));
	 	   	$pdf->Ln(6);
			
			  // ITEM
			  $records_items = referentiel_get_item_competences($competence->id);
        if ($records_items){				  
    	    $pdf->SetFont('Arial','B',10); 
	        $pdf->Write(6,recode_utf8_vers_latin1(trim(get_string('items','referentiel'))));
          $pdf->Ln(6);

				  foreach ($records_items as $record_i){
						pdf_write_item( $record_i );
				  }
				  $pdf->Ln(6);
			   }
        }
}


	 /**
     * Turns domaine into an xml segment
     * @param domaine object
     * @return string xml segment
     */

function pdf_write_domaine( $domaine ) {
    global $pdf;
    
		if ($domaine){
      $code = $domaine->code_domaine;
      $description_domaine = $domaine->description_domaine;
      $ref_referentiel = $domaine->ref_referentiel;
			$num_domaine = $domaine->num_domaine;
			$nb_competences = $domaine->nb_competences;
 			$pdf->SetFont('Arial','B',10); 
   	  $pdf->Write(6,recode_utf8_vers_latin1(trim(get_string('domaine','referentiel')." : ".stripslashes($code))));
      $pdf->Ln(6);
      $pdf->SetFont('Arial','',10); 
   	  $pdf->Write(6, recode_utf8_vers_latin1(trim(stripslashes($description_domaine))));
 	   	$pdf->Ln(6);
			
			// LISTE DES COMPETENCES DE CE DOMAINE
			$records_competences = referentiel_get_competences($domaine->id);
			if ($records_competences){
				foreach ($records_competences as $record_c){
          pdf_write_competence( $record_c );
				}
			}
    }
}


	 /**
     * Turns referentiel instance into an xml segment
     * @param referentiel instanceobject
     * @return string xml segment
     */

function pdf_write_referentiel( $referentiel_instance, $referentiel_referentiel, $param ) {
    global $CFG;
		global $pdf;
		global $image_logo;
		$ok_saut_page=false;
		
		if (($referentiel_instance) && ($referentiel_referentiel)) {
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

			$pdf->AddPage();
			$pdf->SetAutoPageBreak(1, 27.0);     
			$pdf->SetCol(0);
			$pdf->SetDrawColor(128, 128, 128);    
			$pdf->SetLineWidth(0.4);     
			// logo
			$posy=$pdf->GetY();    
			
			if (isset($image_logo) && ($image_logo!="")){
				$pdf->Image($image_logo,150,$posy,40);
			}
			// $posy=$pdf->GetY()+60;    
      $pdf->SetLeftMargin(15);
      // $pdf->SetX(20);
			
			$pdf->SetFont('Arial','B',14); 
		  $pdf->Write(6,get_string('certification','referentiel'));
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',12); 
		  $pdf->Write(6, $name.'('.$code.')');
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',10);
			$pdf->Write(6, $description);
			$pdf->Ln(6);
      if ($param->certificat_sel_referentiel){				
				// DOMAINES
				// LISTE DES DOMAINES
				$compteur_domaine=0;
				$records_domaine = referentiel_get_domaines($referentiel_referentiel->id);
		    if ($records_domaine){
					foreach ($records_domaine as $record_d){
						pdf_write_domaine($record_d );
					}
				}
        $ok_saut_page=true;		
			} 

			if ($param->certificat_sel_referentiel_instance){
				$pdf->SetFont('Arial','B',10); 
				// $pdf->Write(6,"id : $id ");
				// $pdf->Ln(6);
				$pdf->Write(6,recode_utf8_vers_latin1(get_string('instance','referentiel')." : ".$name_instance));
				$pdf->Ln(6);
				$pdf->SetFont('Arial','',10);
				$pdf->Write(6,recode_utf8_vers_latin1($description_instance));   
				$pdf->Ln(6);
        $pdf->Write(6,recode_utf8_vers_latin1($label_domaine.", ".$label_competence.", ".$label_item));
			
    	        /*
				$pdf->Write(6,"Cours : $course");
				$pdf->Ln(6);
	            $pdf->Write(6,"Référentiel :  $ref_referentiel");
				$pdf->Ln(6);
        	    $pdf->Write(6,"Visible : $visible");
				$pdf->Ln(6);
				*/
				$ok_saut_page=true;
			}
			
			$pdf->Ln(6);
			if ($ok_saut_page==true){ // forcer le saut de page
			  $pdf->Ln(290);
      }
		}
}
	
	 /**
     * Turns referentiel instance into an xml segment
     * @param referentiel instanceobject
     * @return string xml segment
     */
function pdf_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param) {
    	global $CFG;
		global $pdf;
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
					
					$pdf->SetLeftMargin(15);
					
					pdf_write_referentiel($referentiel_instance, $referentiel_referentiel, $param);
					
					pdf_write_etudiant( $record_etudiant, $param);
					
					$pdf->SetFont('Arial','',12);
					if ($param->certificat_sel_decision_jury){
						if (($date_decision!="") && ($decision_jury!="")){
							$pdf->Write(6,$decision_jury);
						}
						$pdf->Ln(6);
					}
					
					// $pdf->SetFont('Arial','B',10); 
					// $pdf->Write(6,"ID : ");
					// $pdf->SetFont('Arial','',10);
					// $pdf->Write(6,"$id");
					// $pdf->Ln(6);
					
					$pdf->SetFont('Arial','B',12); 
                    $pdf->Write(6,recode_utf8_vers_latin1(get_string('competences','referentiel')).": ");
					$pdf->Ln(6);
					if ($param->certificat_sel_activite_competences){
						$pdf->SetFont('Arial','B',9); 
                        $pdf->Write(6,recode_utf8_vers_latin1(get_string('competences_activite','referentiel')).": ");
						$pdf->Ln(6); 
                        pdf_liste_competences_certificat($ref_referentiel, '/',':', $competences_activite, $liste_empreintes, 0, 9, 8);
						$pdf->Ln(6);
					}
					if ($param->certificat_sel_certificat_competences){
						$pdf->SetFont('Arial','B',10); 
                        $pdf->Write(6,recode_utf8_vers_latin1(get_string('competences_certificat','referentiel')).": ");
						$pdf->Ln(6);
                        pdf_liste_competences_certificat($ref_referentiel, '/',':', $competences_certificat, $liste_empreintes,0,10,9);
						$pdf->Ln(6);
					}
					if (($param->certificat_sel_certificat_competences) 
                        && ($param->certificat_sel_certificat_detail)){
						pdf_referentiel_affiche_detail_competences('/',':',$competences_certificat, $liste_empreintes, $liste_poids);
                    }
					if ($param->certificat_sel_certificat_pourcent){
                        // $pdf->SetFont('Arial','B',10);
                        // $pdf->Write(6,recode_utf8_vers_latin1(get_string('pourcentage','referentiel'))." :");
                        // $pdf->Ln(6);
                        pdf_referentiel_affiche_certificat_consolide($ref_referentiel, '/',':', $competences_certificat, 10,9,8);
					}
					
					if ($param->certificat_sel_commentaire){
						$pdf->SetFont('Arial','B',10);
                        $pdf->Write(6,recode_utf8_vers_latin1(get_string('commentaire','referentiel')).": ");
						$pdf->SetFont('Arial','',10);
                        $pdf->Write(6,"$commentaire_certificat ");
						$pdf->Ln(6);
						$pdf->SetFont('Arial','B',10);
                        $pdf->Write(6,recode_utf8_vers_latin1(get_string('synthese_certificat','referentiel')).": ");
						$pdf->SetFont('Arial','',10);
                        $pdf->Write(6,"$synthese_certificat ");
						$pdf->Ln(6);
					}
					if ($param->certificat_sel_decision_jury){
						$pdf->SetFont('Arial','B',10);
                        $pdf->Write(6, recode_utf8_vers_latin1(get_string('decision','referentiel'))." : ");
						$pdf->SetFont('Arial','',10);
                        $pdf->Write(6,"$decision_jury");
						$pdf->Ln(6);
					}
					if ($param->certificat_sel_certificat_referents){
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(6,recode_utf8_vers_latin1(get_string('enseignant','referentiel'))." : ");
						$pdf->SetFont('Arial','',10);
						$pdf->Write(6,$nom_prenom_teacher);
						$pdf->Ln(6);
					}
					/*
					$pdf->Write(6," Référentiel : $ref_referentiel");
					$pdf->Ln(6);
		            $pdf->Write(6," Verrou : $verrou, Valide : $valide, Evaluation : $evaluation");
					$pdf->Ln(6);
					*/
					$pdf->Ln(20);
					$pdf->Write(6, get_string('date_signature','referentiel', date("d/m/Y")));
				}
			}
		}
}
	

function pdf_write_certification($referentiel_instance, $referentiel_referentiel, $userid=0, $param, $records_certificats) {
    	global $CFG;
		global $pdf;
		
		if ($referentiel_instance && $referentiel_referentiel) {
			// CERTIFICATS
			if (isset($referentiel_instance->ref_referentiel) && ($referentiel_instance->ref_referentiel>0)){
				// les empreintes
				$liste_empreintes = referentiel_purge_dernier_separateur(referentiel_get_liste_empreintes_competence($referentiel_instance->ref_referentiel), '/');
                $liste_poids=referentiel_purge_dernier_separateur(referentiel_get_liste_poids($referentiel_instance->ref_referentiel), '|');
				
                if ($userid>0){
					$record = referentiel_get_certificat_user($userid, $referentiel_instance->ref_referentiel);
					pdf_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param);
				}
				else {
					if (!$records_certificats){
                        $records_certificats = referentiel_get_certificats($referentiel_instance->ref_referentiel);
					}
                    if ($records_certificats){
						foreach ($records_certificats as $record){
							pdf_write_certificat( $record, $referentiel_instance, $referentiel_referentiel, $liste_empreintes, $liste_poids, $param);
						}
					}
				}
				// print_r($records_certificats);
		    	// exit;
			}
		}
}
 

?>