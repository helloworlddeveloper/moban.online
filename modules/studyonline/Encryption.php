<?php

class Encryption
{
	public function __construct()
	{
	}
	public function safe_b64encode( $string )
	{
		$data = base64_encode( $string );
		$data = str_replace( array(
			'+',
			'/',
			'=' ), array(
			'-',
			'_',
			'' ), $data );
		return $data;
	}

	public function safe_b64decode( $string )
	{
		$data = str_replace( array( '-', '_' ), array( '+', '/' ), $string );
		$mod4 = strlen( $data ) % 4;
		if( $mod4 )
		{
			$data .= substr( '====', $mod4 );
		}
		return base64_decode( $data );
	}

	public function encryptData( $value )
	{
	    global $module_file;
		if( ! $value )
		{
			return false;
		}

        $fp=fopen( NV_ROOTDIR . "/modules/" . $module_file . "/mysitename.key","r");
        $priv_key=fread($fp,8192);
        fclose($fp);
        $passphrase = '';
        $res = openssl_get_privatekey($priv_key,$passphrase);

        openssl_private_encrypt($value,$crypttext,$res);
        $crypttext = $this->safe_b64encode($crypttext);
        return $crypttext;

	}

    public function decryptData($crypttext) {
        global $module_file;
        $fp=fopen (NV_ROOTDIR . "/modules/" . $module_file . "/mysitename.crt","r");
        $pub_key=fread($fp,8192);
        fclose($fp);
        openssl_get_publickey($pub_key);
        /*
        * NOTE:  Here you use the $pub_key value (converted, I guess)
        */
        $crypttext = $this->safe_b64decode($crypttext);
        openssl_public_decrypt($crypttext,$newsource,$pub_key);

        return $newsource;
	}
}

?>