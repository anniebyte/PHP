<?php

class sisMysql
	{
		public static function conectarse()
			{
				$host = "us-cdbr-azure-east2-d.cloudapp.net";
				$user = "b69330f3c803ee";
				$pass = "bf5a0354";
				$base = "phphubmx";
				//echo "entro conectarte";
				$objMysql = new MySQLi($host, $user, $pass, $base);
				if($objMysql->connect_errno)
					{
						$objMysql = 'error';
						echo "error";
					}
				return $objMysql;	
			}
	}

?>