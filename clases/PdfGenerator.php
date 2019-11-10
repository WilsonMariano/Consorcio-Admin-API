<?php

class PdfGenerator {

    public static function generateRecibo($response) {

        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [190, 236],
            'orientation' => 'L'
        ]);

        
        $html = "
        <h3 class='text-right'> Recibo N° 123 </h3>
        <h2 class='text-center'> Mutual de Empleados de Comercio de Alte. Brown </h2>
        <h3 class='text-center'> CUIT: 30-54949319-6 </h3>
        <h3 class='text-center'> Av. Monteverde 2470 - Burzaco - EXPENSAS </h3>
        <p class='text-center'>_____________________________________________________________________________________________________________________</p>
        <h4 class='text-right'> 07/11/2019 </h4>
        <p><b> Recibimos del Sr. / Sra. (328) </b> MONTEIRO, LUIS ALBERTO</p>
        <p><b> La suma de pesos </b> NOVECIENTOS CINCUENTA ($950.00)</p>
        <p><b> en concepto de </b> pago de las expensas del Consorcio Manzana 142 edificio 1 piso 0 UF 1</p>
        <p><b> Periodo abonado: </b> EX 9/2019</p>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p class='text-right'>______________</p>
        <p class='text-right'>Firma y sello</p>
        ";

        $stylesheet = file_get_contents(__DIR__ . '/../styles/pdfStyle.css');

        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
		
		return $response->write( $mpdf->Output('recibo.pdf', 'D') );
    
    }

}   