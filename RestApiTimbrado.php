<?php
class CurlRequest
{
    public function sendPost($data)
    {
        //url contra la que atacamos
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wcfpruebas.facturoporti.com.mx/Timbrado/Servicios.svc/ApiTimbrarCFDI");
        //a true, obtendremos una respuesta de la url, en otro caso,
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Se define el tipo de metodo de envio de datos
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //enviamos el array data
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);
        if(!$response) {
            return false;
        }else{
            return $response;
        }
    }
}

$json = json_decode(file_get_contents('http://software.facturoporti.com.mx/TaaS/Json/Factura.json'), true);

$rest = new CurlRequest();
$resultado = $rest ->sendPost($json);
$respuesta = json_decode($resultado, true);
echo ("Codigo: " . $respuesta["Estatus"]["Codigo"] . "\n");   
echo ("Descripcion: " . $respuesta["Estatus"]["Descripcion"]. "\n"); 
echo ("Fecha: " . $respuesta["Estatus"]["Fecha"] . "\n"); 
echo ("InformacionTecnica: " . $respuesta["Estatus"]["InformacionTecnica"] . "\n");  
// Se envía el CFDI con todos sus atributos
$timbre = base64_decode($respuesta["CFDITimbrado"]["Respuesta"]["TimbreXML"]);
echo '<script language="javascript">';
echo "alert('". $timbre . "')";
echo '</script>';
?>
