<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

require_once ('tcpdf_59/tcpdf.php');

if (! class_exists ( 'Pv_tcpdf' )) {
	/**
	 * Von TCPDF geerbte Klasse.
	 * übernimmt die Funktionalität von TCPDF und
	 * überschreibt gewisse Methoden, wo das Resultat nicht unseren Wünschen
	 * entspricht.
	 */
	class Pv_tcpdf extends TCPDF {
		public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = "UTF-8") {
			parent::__construct ( $orientation, $unit, $format, $unicode, $encoding );
		}
		
		/**
		 * This method is used to render the page header.
		 * It is automatically called by AddPage() and overrides the parent's method.
		 */
		public function Header() {
		}
		
		/**
		 * This method is used to render the page footer.
		 *
		 * It is automatically called by AddPage() and overrides the parent's method.
		 * Only difference to the parent is the color of the line.
		 */
		public function Footer() {
			$cur_y = $this->GetY ();
			$ormargins = $this->getOriginalMargins ();
			$this->SetTextColor ( 0, 0, 0 );
			// set style for cell border
			$line_width = 0.75 / $this->getScaleFactor ();
			$this->SetLineStyle ( array (
					"width" => $line_width,
					"cap" => "butt",
					"join" => "miter",
					"dash" => 0,
					"color" => array (
							0,
							0,
							200 
					) 
			) );
			// print document barcode
			$barcode = $this->getBarcode ();
			if (! empty ( $barcode )) {
				$this->Ln ();
				$barcode_width = round ( ($this->getPageWidth () - $ormargins ['left'] - $ormargins ['right']) / 3 );
				$this->write1DBarcode ( $barcode, "C128B", $this->GetX (), $cur_y + $line_width, $barcode_width, (($this->getFooterMargin () / 3) - $line_width), 0.3, '', '' );
			}
			$pagenumtxt = $this->l ['w_page'] . " " . $this->PageNo () . ' / ' . $this->getAliasNbPages ();
			$this->SetY ( $cur_y );
			// Print page number
			if ($this->getRTL ()) {
				$this->SetX ( $ormargins ['right'] );
				$this->Cell ( 0, 0, $pagenumtxt, 'T', 0, 'L' );
			} else {
				$this->SetX ( $ormargins ['left'] );
				$this->Cell ( 0, 0, $pagenumtxt, 'T', 0, 'R' );
			}
		}
		
		/**
		 * Colored table aus Beispiel
		 * 
		 * @param array $header
		 *        	Spaltennamen
		 * @param array $data
		 *        	pro Zeile mit je einem Element pro Zelle
		 * @param array $colgroup
		 *        	Spalten
		 */
		public function ColoredTable($header, $data, $colgroup) {
			// Colors, line width and bold font
			$this->SetFillColor ( 150, 150, 150 );
			$this->SetTextColor ( 255 );
			$this->SetDrawColor ( 0, 0, 0 );
			$this->SetLineWidth ( 0.2 );
			$this->SetFont ( '', 'B' );
			
			// Header
			$num_headers = count ( $header );
			for($i = 0; $i < $num_headers; ++ $i) {
				$this->Cell ( $colgroup [$i], 7, $header [$i], 1, 0, 'C', 1 );
			}
			$this->Ln ();
			// Color and font restoration
			$this->SetFillColor ( 224, 224, 224 );
			$this->SetTextColor ( 0 );
			$this->SetFont ( '' );
			// Data
			$fill = 0;
			foreach ( $data as $row ) {
				for($i = 0; $i < $num_headers; $i++) {
					$this->Cell ( $colgroup [$i], 10, $row [$i], 'LR', 0, 'L', $fill );
				}
				$this->Ln ();
				$fill = ! $fill;
			}
			$this->Cell ( array_sum ( $colgroup ), 0, '', 'T' );
		}
	} // END OF Pv_tcpdf CLASS
}

