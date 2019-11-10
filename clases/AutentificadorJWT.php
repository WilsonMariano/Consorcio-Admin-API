<?php

use Firebase\JWT\JWT;

class AutentificadorJWT
{
    private static $claveSecreta = 'ClaveSuperSecreta@';
    private static $tipoEncriptacion = ['HS256'];
    private static $aud = null;
    
    public static function CrearToken($usuario)
    {
        $ahora = time();
        $payload = array(
        	'iat'=>$ahora,
            'exp' => $ahora + 86400,
            'aud' => self::Aud(),
            'data' => 
				    [
				    	"id" => $usuario->id,
				    	"email" => $usuario->email
                    ],
            'app'=> "CONSORCIO-ADMIN"
        );
     
        return JWT::encode($payload, self::$claveSecreta);
    }
    
    public static function VerificarToken($token)
    {
        //TOKEN VACIO
        try{
            self::validarTokenVacio($token);
        }
        catch(Exception $ex)
        {
            throw new Exception("El token esta vacio.");
        }
        
        //TOKEN VENCIDO
        try {
            $decodificado = JWT::decode($token, self::$claveSecreta, self::$tipoEncriptacion);
        } 
        catch (Exception $e) 
        {
           throw new Exception("Clave fuera de tiempo");
        }
        
        //TOKEN DESDE OTRA IP
        try
        {
            self::validarTokenAud($decodificado); 
        }
        catch(Exception $ex)
        {
            throw new Exception("No es el usuario valido");
        }
    }

    private static function validarTokenVacio($token)
    {
        if(empty($token)|| $token=="")
            throw new Exception();
    }

    private static function validarTokenAud($decodificado)
    {
        if($decodificado->aud !== self::Aud())
            throw new Exception();
    }
    
   
    public static function ObtenerPayLoad($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        )->data;
    }

    private static function Aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}