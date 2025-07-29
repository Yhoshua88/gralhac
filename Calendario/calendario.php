<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<?php
/* *
error_reporting(E_ALL);
ini_set('display_errors', '1');
/* */
include_once('uodates.php');
try {
        $uodates = new Uodates('Servidor', 'Usuario', 'Password', 'DB');
        $fechas[]='';
        $modulo = (isset($_GET['modulo']))? $_GET['modulo'] : 'reservanew';
        $accion = (isset($_GET['accion']))? $_GET['accion'] : 'index';
        $segimos = '/calendario.php?';
        //echo $segimos;
        htmlspecialchars('', ENT_QUOTES);
        $mes=(isset($_GET['mes'])) ? $_GET['mes'] : date('m');
        $mes = (!intval($mes)||$mes=='0'||$mes>'12'||$mes<'0')?intval('01'):intval($mes);
        $ano=(!isset($_GET['ano'])) ? date('Y') : $_GET['ano'];
        $ano=!intval($ano)?date('Y'):$ano;
        $reserv=$uodates->reservas($ano,$mes);
        $cslimp='background-color: darkkhaki;';
        echo '<style>
        .center-block{
            overflow-wrap: anywhere;
        }
        .newbu{
            bottom: 1em;
            position: fixed;
            left: 1em;
        }
        .infdia{align-content: flex-start; white-space: nowrap;padding:1em;}
        .expl{align-content: flex-start;}
        td{border:1px dotted}
        .diavemos{border-bottom: 2px solid #0B00E0;}
        </style>
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" id="nuevo" data-target="#myModalx">
            Nuevas Reservas
        </button>';
        $fechas = $reserv;
        $salas = $fechas['salascantidad'];
        /* firstOfThisMonth --> variable para determinar el primer dia*/
        $firstOfThisMonth = date('Y').'-'.$mes.'-01';
        $aux = date('Y-m-d', strtotime("{$firstOfThisMonth} + 1 month"));
        $last_day = date('d', strtotime("{$aux} - 1 day"));
        $fechas["nmbform"]='myModalx';//identificador de ventana modal
        $fechas["nmbf"]='formedit';//id de form para ingresar los datos
		/****************************************************************************************************************************************************************************** */
		/****************************************************************************************************************************************************************************** */
		$fechas['formdatos']='<form action="" class="formedit" id="formedit">';
        //Formulario para la ventana modal
        $fechas["formdatos"].='
            <table>
                <tr>
                    <td>Inicio: <input type="datetime-local" name="inicio" id="inicio" min="'.date('Y-m-d', strtotime(date('Y-m-d')." - 1 year ")).'" max="'.date('Y-m-d', strtotime(date('Y-m-d')." + 1 year")).'"></td>
                    </tr>
                    <tr><td>Fin: <input type="datetime-local" name="fin" id="fin" min="'.date('Y-m-d', strtotime(date('Y-m-d')." - 1 year ")).'" max="'.date('Y-m-d', strtotime(date('Y-m-d')." + 1 year")).'"></td></tr>
                    <tr><td>Lugar: <input type="text" name="lugar" id="lugar"></td></tr>
                <input type="hidden" name="jidi" id="jidi">
            </table>
        ';
		$fechas['formdatos'].='</form>';
        $fechas['inputs']=['inicio','fin','lugar','jidi'];
        $color="";
        echo "<style>";
        $csscolor=["00"=>"#334cff","01"=>"#0d25ce", "02"=>"#3d4aa9", "03"=>"#0757ab", "04"=>"#4889cd", "05"=>"#067cf7", "07"=>"#06f7bd", "08"=>"#4ad0b0", "09"=>"#04a07b", "10"=>"#7304a0", "11"=>"#943eb6", "12"=>"#ac08ec"];
        //Muestreo de css para los diferentes lugares a guardar maximo 12 salas
		for($i='0';$i<$salas;$i++){
			
            //Se agregan los estilos para las fechas y cada dato
            $color.='.evento'.str_pad($i, 2,"0", STR_PAD_LEFT).'{color:black;
            box-shadow: 0px 5px 15px 12px rgba(0, 0, 0, 0.4);
            border-left: 10px solid '.$csscolor[str_pad($i, 2,"0", STR_PAD_LEFT)].';
            margin-top: 1em;
            width: max-content;
            margin-bottom: 1em;
            max-height: 5em;
            overflow-y: auto;
            }
            ';
        }
        echo $color;
        echo '</style>';
        if(!is_int($fechas['salascantidad'])){
            $salas='0';//die('No es un numero');
        }else{
            $salas=$fechas['salascantidad'];
        }
        //Se inicia el calndario
		echo '
		<div class="table-responsive">
			<button id="ssemana" onclick="this.disabled = true">Ver Semana</button><button id="smes">Ver Mes</button><button id="sdia">semana 3</button>
			<table id="mesesnav" class="table align-middle">
				<tr>
		';
		switch ($mes){
                case 01:
                    echo '<td><select name="meses" id="meses" onchange="location = this.value">
                    <option value=""></option>
                    <option value="'.$segimos.'&mes=1">Enero</option>
                    <option value="'.$segimos.'&mes=2">Febrero</option>
                    <option value="'.$segimos.'&mes=3">Marzo</option>
                    <option value="'.$segimos.'&mes=4">Abril</option>
                    <option value="'.$segimos.'&mes=5">Mayo</option>
                    <option value="'.$segimos.'&mes=6">Junio</option>
                    <option value="'.$segimos.'&mes=7">Julio</option>
                    <option value="'.$segimos.'&mes=8">Agosto</option>
                    <option value="'.$segimos.'&mes=9">Septiembre</option>
                    <option value="'.$segimos.'&mes=10">Octubre</option>
                    <option value="'.$segimos.'&mes=11">Noviembre</option>
                    <option value="'.$segimos.'&mes=12">Diciembre</option>
                </select></td>
                    <td>'.date('F', strtotime($firstOfThisMonth . ' -0 month')).'</td>
                    <td><a href="'.$segimos.'&mes='.date('m', strtotime($firstOfThisMonth . ' +1 month')).'">'.date('F', strtotime($firstOfThisMonth . ' +1 month')).'</a></td>';
                    break;
                case 12:
                    echo '<td><a href="'.$segimos.'&mes='.date('m', strtotime($firstOfThisMonth . ' -1 month')).'">'.date('F', strtotime($firstOfThisMonth . ' -1 month')).'</a></td>
                    <td>'.date('F', strtotime($firstOfThisMonth . ' -0 month')).'</td>
                    <td><select name="meses" id="meses" onchange="location = this.value">
                    <option value=""></option>
                    <option value="'.$segimos.'&mes=1">Enero</option>
                    <option value="'.$segimos.'&mes=2">Febrero</option>
                    <option value="'.$segimos.'&mes=3">Marzo</option>
                    <option value="'.$segimos.'&mes=4">Abril</option>
                    <option value="'.$segimos.'&mes=5">Mayo</option>
                    <option value="'.$segimos.'&mes=6">Junio</option>
                    <option value="'.$segimos.'&mes=7">Julio</option>
                    <option value="'.$segimos.'&mes=8">Agosto</option>
                    <option value="'.$segimos.'&mes=9">Septiembre</option>
                    <option value="'.$segimos.'&mes=10">Octubre</option>
                    <option value="'.$segimos.'&mes=11">Noviembre</option>
                    <option value="'.$segimos.'&mes=12">Diciembre</option>
                </select></td>';
                    break;
                default:
                echo '<td><a href="'.$segimos.'&mes='.date('m', strtotime($firstOfThisMonth . ' -1 month')).'">'.date('F', strtotime($firstOfThisMonth . ' -1 month')).'</a></td>
                <td>
                <select name="meses" id="meses" onchange="location = this.value">
                    <option value=""></option>
                    <option value="'.$segimos.'&mes=1">Enero</option>
                    <option value="'.$segimos.'&mes=2">Febrero</option>
                    <option value="'.$segimos.'&mes=3">Marzo</option>
                    <option value="'.$segimos.'&mes=4">Abril</option>
                    <option value="'.$segimos.'&mes=5">Mayo</option>
                    <option value="'.$segimos.'&mes=6">Junio</option>
                    <option value="'.$segimos.'&mes=7">Julio</option>
                    <option value="'.$segimos.'&mes=8">Agosto</option>
                    <option value="'.$segimos.'&mes=9">Septiembre</option>
                    <option value="'.$segimos.'&mes=10">Octubre</option>
                    <option value="'.$segimos.'&mes=11">Noviembre</option>
                    <option value="'.$segimos.'&mes=12">Diciembre</option>
                </select>
                '.date('F', strtotime($firstOfThisMonth . ' -0 month')).'</td>
                <td><a href="'.$segimos.'&mes='.date('m', strtotime($firstOfThisMonth . ' +1 month')).'">'.date('F', strtotime($firstOfThisMonth . ' +1 month')).'</a></td>';
            }
	echo '
	</tr>
    </table>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
        <table id="semnav" style="display:none" class="align-middle">
            <tr>
                <td><button id="sant">Semana Anterior</button></td>
                <td><button id="ssig">Siguiente Semana</button></td>
            </tr>
        </table>
            <table id="calendariomes" width="100%" border="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="diassemana">Domingo</th>
                        <th class="diassemana">Lunes</th>
                        <th class="diassemana">Martes</th>
                        <th class="diassemana">Miercoles</th>
                        <th class="diassemana">Jueves</th>
                        <th class="diassemana">Viernes</th>
                        <th class="diassemana">Sabado</th>
                    </tr>
                </thead>
                <tbody id="msdata">
				';
		$dia=1;
                            $sema=0;
                            echo '<tr id="sema'.$sema.'">';
							//echo '----'.json_encode($fechas).'';
                            for($di=0;$di<date('w', strtotime($firstOfThisMonth));$di++){
                                echo '<td class="antesmes infdia"></td>';
                            }
                            for($dia;$dia<=$last_day;$dia++){
                                echo '<td class="infdia"><div class="expl">
                                <div class="diacalendario">'.$dia.'</div>';
                                /* Forma para mostrar los datos pasa cada 10 minutos se cambiara en el futuro*/
                                for($w=0;$w<$salas;$w++){
                                    if(!is_string($fechas['salas'][$w])){die('No es un texto |'.$fechas['salas'][$w].'-');}
									
                                    echo '<div class="event"><div class="evento'.str_pad($w, 2,"0", STR_PAD_LEFT).'">';
                                        echo '<div class="diavemos">- '.$fechas['salas'][$w].'</div>';
										
                                            for($mitu='0';$mitu<'1440';$mitu+=10){
                                                
                                                if(isset($fechas[$fechas['salas'][$w]][str_pad($dia, 2,"0", STR_PAD_LEFT)][date('H:i', strtotime($firstOfThisMonth . ' +'.$mitu.' minute'))])){
													$limp='';
                                                    echo '<div class="detallehijo">
                                                        <button class="datoedit" style="'.$limp.'" data-toggle="modal" data-target="#myModalx" id="'.$fechas['salas'][$w].'>'.str_pad($dia, 2,"0", STR_PAD_LEFT).'>'.date('H:i', strtotime($firstOfThisMonth . ' +'.$mitu.' minute')).'">
                                                            '.substr($fechas[$fechas['salas'][$w]][str_pad($dia, 2,"0", STR_PAD_LEFT)][date('H:i', strtotime($firstOfThisMonth . ' +'.$mitu.' minute'))]['inicio'], 11, -3).'-'.substr($fechas[$fechas['salas'][$w]][str_pad($dia, 2,"0", STR_PAD_LEFT)][date('H:i', strtotime($firstOfThisMonth . ' +'.$mitu.' minute'))]['fin'], 11, -3).'
                                                        </button>
                                                    </div>';
                                                }
                                            }
                                        echo '</div></div>';
                                }
                                /************************************************************Se crea el div para los detalles de la reserva */
                                echo '</div></td>';
                                if($dia==$last_day){
                                    echo '</tr>';
                                    break;
                                }
                                if(date('w', strtotime(date('Y').'-'.$mes.'-'.$dia))==6){
                                    $sema++;
                                    echo '</tr><tr id="sema'.$sema.'"></td>';
                                }
                            }
                            echo '</tr>
                    </tbody>
                </table>';
		echo '</div></div>

	        <!-- Modal fechas ------------------------------------------------------------------------------------------------------->
        <div class="modal fade" id="myModalx" tabindex="-1" role="dialog" aria-labelledby="myModalxLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalxLabel">Modal title xxxxxxx</h4>
                </div>
                <div class="modal-body center-block">';
				echo utf8_decode($fechas["formdatos"]);
                echo '
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cambia"></button>
                    <button type="button" class="btn btn-danger" style="display:none;" id="elimina" >Eliminar</button>
                </div>
                </div>
            </div>
            </div>';
			echo date('w', strtotime($firstOfThisMonth)).'<br>';
		echo date('l jS \of F Y h:i:s A').'<br>';
		?>  
            </body>
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script>
            const fechas=JSON.parse(JSON.stringify(<?php echo json_encode($fechas);?>));
            const btns = document.querySelectorAll('button');
            btns.forEach((btn) => {
            btn.addEventListener('click', e => {
                var miCadena = e.target.id;
                switch  (miCadena) {
                    case 'ssig':
                            var jid = document.getElementById("msdata").lastChild.previousElementSibling.id;
                            if(window.getComputedStyle(document.getElementById(jid), null).display!='none'){console.log('adiu');break;}
                            for (let i = 0; i < jid.substring(4, 5); i++) {
                                var sep='sema'+i;
                                element= window.getComputedStyle(document.getElementById(sep), null).display;
                                if(element!='none'){
                                    document.getElementById(sep).setAttribute('style','display:none');
                                    i++;sep='sema'+i;
                                    if(sep.substring(4, 5)>jid.substring(4, 5)){break;}
                                    document.getElementById(sep).setAttribute('style','display:auto');
                                    i=0;break;
                                }
                            }
                        break;
                    case 'sant':
                        var jid = document.getElementById("msdata").lastChild.previousElementSibling.id;
                            if(window.getComputedStyle(document.getElementById('sema0'), null).display!='none'){
                                console.log('adiu');
                                break;}
                            for (let i = jid.substring(4, 5); i >= 0 ; i--) {
                                var sep='sema'+i;
                                var com=sep.substring(4, 5);
                                element= window.getComputedStyle(document.getElementById(sep), null).display; 
                                if(element!='none'){
                                    i--;
                                    document.getElementById(sep).setAttribute('style','display:none');
                                    sep='sema'+i;
                                    document.getElementById(sep).setAttribute('style','display:auto');
                                    i=0;break;
                                }
                            }
                        break;
                    case 'ssemana':
                            var jid = document.getElementById("msdata").lastChild.previousElementSibling.id;
                            navsemana=document.getElementById("semnav");
                            navsemana.setAttribute('style','display:auto');
                            for (let i = 1; i <= jid.substring(4, 5); i++) {
                                var sep='sema'+i;
                                document.getElementById(sep).setAttribute('style','display:none');
                            }
                        break;
                    case 'smes':
                            var jid = document.getElementById("msdata").lastChild.previousElementSibling.id;
                            var ssemana =document.getElementById('ssemana');
                            ssemana.removeAttribute('disabled');
                            navsemana=document.getElementById("semnav");
                            navsemana.setAttribute('style','display:none');
                            for (let i = 0; i <= jid.substring(4, 5); i++) {
                                var sep='sema'+i;
                                document.getElementById(sep).setAttribute('style','display:auto');
                            }
                        break;
                    case 'nuevo':
                            var inputElements = fechas['inputs'];
                            inputElements.forEach(field =>{
                            try {
                                const el = document.getElementById(field);
                                el.value='';
                            } catch (error) {
                                console.error(error);
                            }
                            });document.getElementById('cambia').innerHTML='Nueva Reserva';
                            document.getElementById('elimina').setAttribute('style','display:none;');
                            document.getElementById("myModalxLabel").innerHTML="Nueva Reserva";
                        break;
                    case 'elimina':
                        Swal.fire({
                            title: "¿Esta seguro de eliminar la reserva?",
                            text: "No se podrá recuperar la reserva",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Si, Eliminar!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var clasu='#'+fechas['nmbf'];
                                const form = document.getElementById(fechas['nmbf']);
                                if (form) {
                                    const datos = document.querySelector(clasu);
                                    const datos_actualizar = new FormData(datos);
                                    fetch('./uodates.php?op=elim', {
                                        method: 'post',
                                        body: datos_actualizar
                                        }).then(response => response.json())
                                        .then(data => { 
                                            console.log('>'+data);
                                            if(data.responde=='1'){
                                                Swal.fire({
                                                    title: "Eliminado!",
                                                    text: "Su reserva a sido eliminada.",
                                                    icon: "success"
                                            }).then(function() {
                                                window.location = "calendario.php";
                                            });
                                            }else{
                                                Swal.fire({
                                                icon: "error",
                                                title: data.msgtitle,
                                                text: data.msgtxt,
                                                }).then(function() {
                                            });
                                    }}).catch(function(error){
                                console.error('Error:', error)
                            });
                            }
                        }
                        });
                        break;
                    case 'cambia':
                            var clasu='#'+fechas['nmbf'];
                            const form = document.getElementById(fechas['nmbf']);
                            if (form) {
                                const datos = document.querySelector(clasu);
                                const datos_actualizar = new FormData(datos);
                                //console.log(JSON.stringify(datos_actualizar));
                                fetch('..//uodates.php?op=camb', {
                                    method: 'post',
                                    body: datos_actualizar
                                    }).then(response => response.json())
                                    .then(data => {
                                        console.log('>'+data);
                                        if(data.responde=='1'){
                                            Swal.fire({
                                            title: data.msgtitle,
                                            text: data.msgtxt,
                                            icon: "success",
                                            confirmButtonText: "OK"
                                        }).then(function() {
                                            window.location = "calendario.php";
                                        });
                                        }else{
                                            Swal.fire({
                                            icon: "error",
                                            title: data.msgtitle,
                                            text: data.msgtxt,
                                            }).then(function() {
                                        });
                                        }}).catch(function(error){
                                console.error('Error:', error)
                            });
                            }/* */
                        break;
                    default:
                        
                        var cade = miCadena;
                        var io = document.getElementById(cade).className;
                        document.getElementById("myModalxLabel").innerHTML=cade;
                        var divisiones = cade.split(">", 3);
                            if(io=='datoedit'){
                                var fields2=fechas[divisiones['0']][divisiones['1']][divisiones['2']];
                                var inputElements = fechas['inputs'];
                                var i =0;
                                inputElements.forEach(field =>{
                                try{
                                    const el = document.getElementById(field);
									//console.log(field + ' -|- ' + fields2[field]);
                                        if(fields2[field]!=''){
											el.value=fields2[field];
										}
                                }catch (error) {
                                    console.error(error);
                                }
                                });
                                document.getElementById('elimina').removeAttribute('style');
                                document.getElementById('elimina').setAttribute('style','display:auto;');
                                document.getElementById('cambia').innerHTML='Cambiar Reserva';
                                
                            }
                    }
            });
            });
    </script>
</html>
		<?php
        $uodates = null;
}catch (Exception $e) {
   echo "Error: " . $e->getMessage();
   }
?>
