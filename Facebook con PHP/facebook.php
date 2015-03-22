<?php
include_once("mysql.php");

class sistemaFacebook
	{
		private $idUser= '';
		private $correoUser='';
		private $autorizado = 'no';

		function salir()
			{
				$this->autorizado='no';
				header("Location: index.php");
			}
		function iniciarSistema()
			{
				
				if($this->autorizado=='no')
					{
						$this->inicioNoAutorizado();	
					}
				else if($this->autorizado=='si')
					{
						$this->inicioSiAutorizado();
					}				
			}
		function inicioSiAutorizado()
			{
				$nuevoEstado = $_POST["nuevoEstado"];
				if($nuevoEstado=='si')
					{
						$estado = $_POST["estado"];
						$nuevoId = '';
						$fecha = date("Y-m-d h:i:s"); 
						$idUsuario = $this->idUser;
						$nuevoMysql = sisMysql::conectarse();
						$sql = 
						"insert into mensajes
						(id,id_usuario,fecha, publicacion)
						values
						(?,?,?,?)";
						$sentencia = $nuevoMysql->prepare($sql);
						$paso = $sentencia->bind_param('isss',$nuevoId, $idUsuario, $fecha, $estado);
						echo "entro 2";
						$fin=false;
						if(!$paso)
							{
								//echo "fallo al asignar datos";
								$fin=false;
							}
						else
							{
								$paso2 = $sentencia->execute();	
								if($paso2)
									{
										//echo "datos insertados correctamente";
										$fin=true;
									}
								else
									{
										//echo "fallo al insertar datos"; 
										$fin=false;
									}	
							}		
						$sentencia->close();
						if($fin)
							{header("Location: index.php");}
						else if($fin==false)
							{header("Location: index.php?m=1");}






					}
				else
					{
						echo '<div>Usuario: '.$this->correoUser.'</div>';
						echo '<div><a href="index.php?salir=si">Salir</a></div>';
						echo '<br/><br/>';
						$m = $_GET["m"];
						if($m=="1")
							{
								echo "<div> Fallo Publicacion </div>";	
							}						
						echo '<form action="index.php" method="POST" > ';
							echo '<div>Estado</div>';
							echo '<div><textarea name="estado" rows="4" cols="50"></textarea></div>';
							echo '<div><input type="hidden" name="nuevoEstado" value = "si" /></div>';
							echo '<div><input type="submit" value="Publicar" /> </div>';
						echo '</form>';



						$nuevoMysql = sisMysql::conectarse();
						
						$sql = 
						"select m.fecha, m.publicacion, u.correo 
						from 
						mensajes  m,
						usuarios u
						where 
						m.id_usuario = u.id
						order by m.id desc
						";
						if ($resultado = $nuevoMysql->query($sql)) 
							{
					    		while ($fila = $resultado->fetch_row()) 
					    			{
					    				echo "<div> usuario: ".$fila[2].'</div>';
					    				echo "<div> fecha: ".$fila[0].'</div>';
					    				echo "<div> Mensaje:<br>".$fila[1].'</div>';
					    				echo "<hr/>";
					    			}					    		
					    		$resultado->close();
					    	}						
					}
			}
		function inicioNoAutorizado()
			{
				$funcion = $_GET["f"];
				if($funcion=='registro')
					{
						$accion = $_POST["guardar"];
						if($accion=='si')
							{
								echo "entro 1";
								$nuevoCorreo = $_POST["nuevoCorreo"];
								$nuvoPassword = $_POST["nuvoPassword"];
								$nuevoId = '';
								$nuevoMysql = sisMysql::conectarse();
								$sql = 
								"insert into usuarios
								(id,correo,pass)
								values
								(?,?,?)";
								$sentencia = $nuevoMysql->prepare($sql);
								$paso = $sentencia->bind_param('iss',$id, $nuevoCorreo, $nuvoPassword);
								echo "entro 2";
								$fin=false;
								if(!$paso)
									{
										//echo "fallo al asignar datos";
										$fin=false;
									}
								else
									{
										$paso2 = $sentencia->execute();	
										if($paso2)
											{
												//echo "datos insertados correctamente";
												$fin=true;
											}
										else
											{
												//echo "fallo al insertar datos"; 
												$fin=false;
											}	
									}		
								$sentencia->close();
								if($fin)
									{header("Location: index.php?f=registro&m=1");}
								else if($fin==false)
									{header("Location: index.php?f=registro&m=2");}
							}
						else
							{
								$m = $_GET["m"];
								if($m=="1")
									{
										echo "<div> Nuevo REgistro exitoso</div>";
									}
								if($m=="2")
									{
										echo "<div> Nuevo REgistro Fallo</div>";	
									}

								echo '<div>Registro Nuevo Usuario</div>';
								echo '<form action="index.php?f=registro" method="POST" > ';
									echo '<div>Nuevo Correo:  <input type="text" name="nuevoCorreo" /> </div>';
									echo '<div>Nuevo Password: <input type="Password" name="nuvoPassword" /></div>';
									echo '<div><input type="hidden" name="guardar" value = "si" /></div>';
									echo '<div><input type="submit" value="Inscribr" /> </div>';
								echo '</form>';
								echo '<div><a href="index.php">Regresar Inicio</a></div>';
							}
					}
				else
					{
						$accion = $_POST["validar"];
						if($accion=='si')
							{
								$nuevoMysql = sisMysql::conectarse();
								$usarCorreo = $_POST["usarCorreo"];
								$usarPassword = $_POST["usarPassword"];
								$sql = 
								"select id from usuarios
								where correo = ? and pass = ? ";
								$sentencia = $nuevoMysql->prepare($sql);
								$paso = $sentencia->bind_param('ss',$usarCorreo, $usarPassword);
								$fin=false;
								if(!$paso)
									{
										//echo "fallo al asignar datos";
										$fin=false;
									}
								else
									{
										$paso2 = $sentencia->execute();	
										if($paso2)
											{
												//echo "datos insertados correctamente";
												 $sentencia->bind_result($idResultado);
												 $sentencia->fetch();
												 if($idResultado!='')
												 	{
												 		$this->idUser= $idResultado;
												 		$this->correoUser=$usarCorreo;
												 		$this->autorizado='si';
												 		$fin=true;	
												 	}												
											}
										else
											{
												//echo "fallo al insertar datos"; 
												$fin=false;
											}	
									}
								$sentencia->close();
								if($fin)
									{header("Location: index.php");}
								else if($fin==false)
									{header("Location: index.php?m=1");}								


							}
						else
							{
								$m = $_GET["m"];
								if($m=="1")
									{
										echo "<div> Ingreso Error </div>";
									}
								echo '<div>Inicio Sesion</div>';
								echo '<form action="index.php" method="POST" > ';
									echo '<div>Usuario:  <input type="text" name="usarCorreo" /> </div>';
									echo '<div>Password: <input type="Password" name="usarPassword" /></div>';
									echo '<div><input type="hidden" name="validar" value = "si" /></div>';
									echo '<div><input type="submit" value="Igresar Sistema" /> </div>';
								echo '</form>';
								echo '<div><a href="index.php?f=registro">Crear Usuario</a></div>';

							}
					}
			}
	}
session_start(); 
if (!isset($_SESSION["objetoSistema"]))
	{
		$_SESSION["objetoSistema"] = new sistemaFacebook();
	}




?>