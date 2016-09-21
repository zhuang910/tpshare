<?php
/**
 * TCPDF 模型
 */
namespace BuiAdmin\Model;
vendor('tcpdf.tcpdf');

class ArticlePdfModel extends \TCPDF {
	
	private $contract_data;
	
	public function __construct($orientation="P", $unit="mm", $format="A4") {
		parent::__construct( $orientation, $unit, $format, true, 'UTF-8', false );
		
		$this->SetMargins( 31.7, 25.4, 31.7, true );
		$this->SetAutoPageBreak( true, 25.4 );
		
		$this->SetCreator( PDF_CREATOR );
		$this->SetAuthor( '享得共享' );
		$this->SetTitle( '享得文章' );
		$this->SetSubject( '' );
		$this->SetKeywords( '享得共享 ，文章' );
		
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
	}
	
	/* (non-PHPdoc)
	 * @see TCPDF::Header()
	 */
	public function Header() {

		$image_file= __ROOT__.'Public/Home/images/share_logo1.png';
		$this->Image($image_file, 31.7, 10, 30, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		//$this->SetLineStyle( array( 'width' =>0.1, 'color' => array( TCPDF_COLORS::$webcolor['black'] ) ) );
		$this->Line( 31.7, 26, $this->getPageWidth() - 31.7, 26 );
	}
	
	/* (non-PHPdoc)
	 * @see TCPDF::Footer()
	 */
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont('helvetica', 'B', 8);
		$this->Cell(0, 10, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
	
	public function createPdf($data){
		
		$this->contract_data = $data;
		
		$lh = 10;
		
		$this->AddPage();
		$this->Ln($lh);
		
		$this->SetFont('msyh', '', 16);
		$txt = $data['article_info']['title'];
		
		$this->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
		$this->Ln($lh);
		

		//文章内容
		$this->SetFont('stsongstdlight', '', 10.5);
		$txt = $data['article_info']['content'];
		$this->writeHTML($txt,true, false, true, false, '');
		$this->Ln($lh);

		$this->SetFont('stsongstdlight', 'B', 10.5);
		$this->Write(0,'来自 享得共享网：http://eshare.6655.la');
                
                $this->lastPage();

	}

}
