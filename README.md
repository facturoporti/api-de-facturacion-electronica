
<div align="center">

![banner](Reducido.png)

# FacturoPorTi PHP v.2.3.7

![php badge](subtitulo-badge.png)

</div>

Este es el ejemplo de uso para consumir el servicio REST API en PHP que permite generar el CFDI, PDF y envío por correo usando un JSON [FacturoPorTi API](http://wcfpruebas.facturoporti.com.mx/Timbrado/Servicios.svc/ApiCFDI).

## Requerimientos

PHP 7 en adelante, se recomienda usar la última versión de PHP.

## Instalación

Obten la última versión de FacturoPorTi PHP en:

    git clone https://github.com/facturoporti/factura-electronica-php

Para iniciar debes de descargar y agregar a tu proyecto alguno de los siguientes archivos que contiene el ejemplo en JSON integran todo lo necesario para generar un CFDI solamente debe de actualizarse la **fecha de creacion** que viene en el JSON:

    http://software.facturoporti.com.mx/TaaS/Factura.json
    http://software.facturoporti.com.mx/TaaS/ComplementoPagos.json
    http://software.facturoporti.com.mx/TaaS/CartaPorte.json  
    http://software.facturoporti.com.mx/TaaS/NotaCargo.json
    http://software.facturoporti.com.mx/TaaS/NotaCredito.json
    http://software.facturoporti.com.mx/TaaS/ReciboHonorarios.json
    http://software.facturoporti.com.mx/TaaS/ReciboArrendamiento.json
    http://software.facturoporti.com.mx/TaaS/ReciboDonativo.json

## Uso

```php
class CurlRequest
{
    public function sendPost($data)
    {
        //url contra la que atacamos
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://wcfpruebas.facturoporti.com.mx/Timbrado/Servicios.svc/ApiCFDI");
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

// Este ejemplo predeterminado es para generar una factura, se debe de actualizar la fecha que trae por defecto en Fecha de Creacion a una actual respetando el formato
$json = json_decode(file_get_contents("http://software.facturoporti.com.mx/TaaS/Factura.json"), true);

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

```

## Documentación 

Si deseas agregar o eliminar información descarga el diccionario de datos que contiene todos los atributos y su descripción de los valores que permite http://software.facturoporti.com.mx/TaaS/Diccionario\Rest-Api-V-2.3.7.xlsx

## Probar Generación de CFDI's

Abre la pagina en PHP y automáticamente se generará el CFDI utilizando el Rest API arroj, al término obtendras un Json de respuesta: 

```php
{
  "Estatus": {
  "Codigo": "000",
  "Descripcion": "Timbrado del CFDI realizado con éxito",
  "DetieneEjecucionProveedor": false,
  "Fecha": "2019-05-22T16:10:42",
  "InformacionTecnica": "Ok"
  },
  "CFDITimbrado": {
    "Respuesta": {
    "CFDIXML": "XML en base 64",
    "CadenaOriginal": "||1.1|B225EEB3-EF95-4AA8-A3E2-EA79B88DF01E|2019-05-22T16:10:42|DAL050601L35|E73GPv8I7Kdvq+iFLhQF+24NTwm6Rw39zZgBHuMfFrYtiJSYwd322TdqHmrqo26T9kYYHE0V49Xx2g4Y4UIH199InCDIMiNL8xxm6it33jax9EZXDgk/TwPedlzy3sqBBVvcaPrGA3RhIvmkoNHrt56SsEiAAqRlehb3ihNtMmgP9CvDDZICORkxyN8R/+OYF37187ye5alugIRNtZYT/rJ9M9H83Kz44Xc4tOpgVdi8I9t/xKs6MF1mlUNIPoPLVb4CqzK3gRQGX2W2D7dAffTq6I5WRMmHrSNBSRvk/1o8DbMQxUzPBSuuWl7EGEVLKbnjhLSwqkW2iwIqKKFfsQ==|20001000000300022323||",
    "CadenaOriginalCFD": "||3.3|AB|1|2019-05-21T23:59:15|99|30001000000300023708|100.00|MXN|1|100.00|I|PUE|06470|AAA010101AAA|Empresa Patito|601|SSF1103037F1|Scafandra Software Factory SA de CV|P01|84111506|1.00|E48|Servicio|Recibo de donativo de una ambulancia|100.00|100.00|1.1|123456789|2019-05-14|Este comprobante ampara un donativo, el cual será destinado por la donataria a los fines propios de su objeto social. En el caso de que los bienes donados hayan sido deducidos previamente para los efectos del impuesto sobre la renta, este donativo no es deducible. La reproducción no autorizada de este comprobante constituye un delito en los términos de las disposiciones fiscales. Autorización publicada por la Secretaría de Hacienda y Crédito Público Número||",
    "EmailEnviado": "false",
    "Fecha": "2019-05-22T16:10:42",
    "IdVersionTimbrado": "1.1",
    "NoCertificado": "20001000000300022323",
    "PDF": "PDF en base 64",
    "RfcProvCertif": "DAL050601L35",
    "SelloCFD": "E73GPv8I7Kdvq+iFLhQF+24NTwm6Rw39zZgBHuMfFrYtiJSYwd322TdqHmrqo26T9kYYHE0V49Xx2g4Y4UIH199InCDIMiNL8xxm6it33jax9EZXDgk/TwPedlzy3sqBBVvcaPrGA3RhIvmkoNHrt56SsEiAAqRlehb3ihNtMmgP9CvDDZICORkxyN8R/+OYF37187ye5alugIRNtZYT/rJ9M9H83Kz44Xc4tOpgVdi8I9t/xKs6MF1mlUNIPoPLVb4CqzK3gRQGX2W2D7dAffTq6I5WRMmHrSNBSRvk/1o8DbMQxUzPBSuuWl7EGEVLKbnjhLSwqkW2iwIqKKFfsQ==",
    "SelloSAT": "Yc4TszQ/y5L56XY128rnOWZaScqQ4d6iew48goun0P5lToGAKv7ApEm6myKMj4/XNF4vbHZrriebUU+BJPbqjO+b6+K3MuOX1wgKfPGkj67+pz89reME/O17BZP5nk0+9iixGi7PkEIJ37QKEtfg6AM5LHZnigMHZtWnaFJCqz//eSO/OjB1LQFP9lzbhgBJXk6YLrKkIRPjHpW1X1bVDYHpIWbWAjUPYR1kOnxMaERDqejLiaZ9ahqHKSzxX6Ecdmnzo/R1UCghrEzy9mDoAihp1LdQtgiHkN3z+APjEAelRNnjl1ar9xKjn6hX+un3s7WHOrOHJKEIRwRWE4lV9g==",
    "TimbreXML": "Timbre en base 64",
    "UUID": "B225EEB3-EF95-4AA8-A3E2-EA79B88DF01E"
    }
  }
}
```

Versión de PHP usada

```
PHP 7.3.5 
```

## Contribuir

1. Fork el repositorio 

2. Clona el repositorio

    git clone git@github.com:yourUserName/factura-electronica-php.git


3. Crea una rama 
```
    git checkout desarrollo
    git pull al original desarrollo
    # Podrás escoger el nombre de tu rama
    git checkout -b <feature/my_branch>
```
4. Haz los cambios necesarios y commit para carga los
```
    git add .
    git commit -m "mis cambios"
```
5. Envía los cambios a GitHub
```
    git push origin <feature/my_branch>
```

***-

## License

Desarrollado en México por [FacturoPorTi](https://www.FacturoPorTi.com). Available with [MIT License](LICENSE).
