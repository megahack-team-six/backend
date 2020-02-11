<?php
session_start();
error_reporting(0);
echo "<!doctype html>\n";
echo "<html>\n";
echo "<meta charset=UTF-8>\n";
echo "<meta name=viewport content='width=device-width, initial-scale=1.0'>\n";
echo "<title>JusBras</title>\n";
echo "<style>\n";
echo "a{color:#3333aa; cursor:pointer;}\n";
echo "table{background-color:#f8f8f0; border-radius:8px; border:1px solid #333366; margin:0px auto;}\n";
echo "td,th{color:#333366; padding:6px 6px 2px 6px; text-align:left;}\n";
echo "th{background-color:#f7dCd6; border-bottom:1px solid #666699; padding-bottom:6px; text-align:center;}\n";
echo ".aviso{margin:20px 10px; background-color:#cc3333; border-radius:6px; color:#ffffff; padding:12px; text-align:center; font-weight:bold;}\n";
echo "</style>\n";
echo "<body style='font-family:Verdana;'>\n";
echo "<div style='background-color:#875650; color:#dddddd; font-size:40px; padding:20px; cursor:pointer;' onclick=\"location.href='index.php';\">JusBras</div>\n";
echo "<div style='min-height:72vh; margin:6px 0px; display:flex; justify-content:center; align-items:center;'><div style='text-align:center;'>\n";
function validchars($str){return preg_replace('/[^a-zA-Z0-9 \\-\\+@\\.ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĲĳĴĵĶķĹĺĻļĽľĿŀŁłŃńŅņŇňŉŌōŎŏŐőŒœŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſƒƠơƯưǍǎǏǐǑǒǓǔǕǖǗǘǙǚǛǜǺǻǼǽǾǿ]/','',$str);}
if($_GET[mdl]=="cadastro"&&$_POST[nome]&&$_POST[email]&&$_POST[senha]){
	if(filter_var($_POST[email],FILTER_VALIDATE_EMAIL)){
		$_POST[email]=substr(preg_replace('/[^0-9A-Za-z@\\.]/','',$_POST[email]),0,40);
		$novoregistro=md5($_POST[senha]).",".$_POST[email].",".validchars($_POST[nome])."\n";
		file_put_contents("./listausers.txt",file_get_contents("./listausers.txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])).$novoregistro,0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))or die("Erro ao Substituir o Arquivo Database");
		$_SESSION[user]=$_POST[email];
	}
	else echo "<br><br><br><br><div class=aviso>O endereço de E-mail não é válido.</div>\n";
}
if($_GET[mdl]=="logout"){
	unset($_SESSION[user]);
	unset($_SESSION[nivel]);
}
if($_POST[pwd]&&$_POST[email]){
	if(($_POST[pwd]=="juiz@judibras"&&$_POST[email]=="juiz@judibras")||($_POST[pwd]=="juiz@jusbras"&&$_POST[email]=="juiz@jusbras")){
		$_SESSION[user]=$_POST[email];
		$_SESSION[nivel]="juiz";
	}
	else if(strpos(file_get_contents("./listausers.txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])),md5($_POST[pwd]).",".$_POST[email].",")!==FALSE)$_SESSION[user]=$_POST[email];
	else echo "<br><br><br><br><div class=aviso>Usuário não Encontrado.</div>\n";
}
if(!$_SESSION[user]){
	switch($_GET[mdl]){
		case 'cadastro':
			echo "<form action=index.php?mdl=cadastro method=post>\n";
			echo "<table>\n";
			echo "<tr><th colspan=2>Cadastro de Usuário</th></tr>\n";
			echo "<tr><td colspan=2><input type=radio name=tipo value=juiz disabled>Juiz <input type=radio name=tipo value=advogado checked>Advogado <input type=radio name=tipo value=imprensa>Imprensa <input type=radio name=tipo value=outro>Outro</td></tr>\n";
			echo "<tr><td>Nome</td><td><input required name=nome></td></tr>\n";
			echo "<tr><td>Email</td><td><input required name=email></td></tr>\n";
			echo "<tr><td>Telefone</td><td><input></td></tr>\n";
			echo "<tr><td>Senha</td><td><input required name=senha type=password></td></tr>\n";
			echo "<tr><td>Confirma</td><td><input type=password></td></tr>\n";
			echo "<tr><td colspan=2 style='text-align:right;'><a style='font-size:12px;' onclick=\"location.href='index.php';\">..já sou cadastrado</a> <input type=submit value=Cadastrar></td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		break;
		default:
			echo "<form action=index.php method=post>\n";
			echo "<table>\n";
			echo "<tr><th colspan=2>Sistema JusBras</th></tr>\n";
			echo "<tr><td>Email</td><td><input required name=email></td></tr>\n";
			echo "<tr><td>Senha</td><td><input required name=pwd type=password></td></tr>\n";
			echo "<tr><td colspan=2 style='text-align:right;'><a style='font-size:12px;' onclick=\"alert('Em construção');\">..esqueci senha</a> <input type=button value=Cadastro onclick=\"location.href='index.php?mdl=cadastro';\"> <input type=submit value=Logar></td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
	}
}else{
	if($_SESSION[nivel]=="juiz"){
		switch($_GET[mdl]){
		case "caso":
			$detalhes=explode("\n",file_get_contents("./database/$_GET[data]/$_GET[id].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])));
			if($_GET[interessados]){
				$detalhes[5]=preg_replace('/[^0-9A-Za-z@\\.]/','',$_GET[interessados]);
				$novoregistro=$detalhes[0]."\n".$detalhes[1]."\n".$detalhes[2]."\n".$detalhes[3]."\n".$detalhes[4]."\n".$detalhes[5]."\n";
				file_put_contents("./database/$_GET[data]/$_GET[id].txt",file_get_contents("./database/$_POST[data]/$idcaso.txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])).$novoregistro,0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))or die("Erro ao Substituir o Arquivo Database");
			}
			echo "<input style='padding:12px; width:200px;' type=button value='Voltar à Lista de Processos' onclick=\"location.href='index.php';\"><br><br>\n";
			echo "<table>\n";
			echo "<tr><th colspan=2>$detalhes[2]</th></tr>\n";
			echo "<tr><td>Proponente</td><td>$detalhes[3]</td></tr>\n";
			echo "<tr><td>ContraParte</td><td>$detalhes[4]</td></tr>\n";
			echo "<tr><td colspan=2><br><div style='text-align:center;'>Usuários autorizados a Acompanhar o caso</div><textarea id=interessados style='width:360px; height:80px;' placeholder='Digite os endereços de e-mail separados por vírgula'>$detalhes[5]</textarea></td></tr>\n";
			echo "<tr><td colspan=2 style='text-align:right;'><input style='padding:12px;' type=button value='Salvar Lista de Usuários' onclick=\"location.href='index.php?mdl=caso&data=$_GET[data]&id=$_GET[id]&interessados='+document.getElementById('interessados').value;\"></td></tr>\n";
			echo "</table><br><br>\n";
			echo "<table><tr><th>Adicionar ao Processo</th></tr><tr><td><input type=file><br><textarea style='width:360px; height:80px;'></textarea><div style='text-align:right;'><input style='padding:12px;' type=button value='Enviar nova Entrada'></div></td></tr></table><br><br>\n";
			echo "<input style='padding:12px; width:200px;' type=button value='Excluir esse Processo' onclick=\"if(confirm('Tem certeza ?'))location.href='index.php?mdl=deletacaso&data=$_GET[data]&id=$_GET[id]';\"><br><br>\n";
			echo "<input style='padding:12px; width:200px;' type=button value='Voltar à Lista de Processos' onclick=\"location.href='index.php';\"><br><br>\n";
		break;
		default:
			if($_GET[mdl]=="deletacaso"){
				$casos=explode("\n",file_get_contents("./casos/$_SESSION[user].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])));
				$conteudo="";
				foreach($casos as $caso)if($caso)
					if(!strstr($caso,$_GET[id]))$conteudo+=$caso."\n";
				if($conteudo)file_put_contents("./casos/$_SESSION[user].txt",$conteudo,0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))or die("Erro ao Substituir o Arquivo Database");
				else unlink("./casos/$_SESSION[user].txt",stream_context_create(['gs'=>['Content-Type'=>'text/plain']]));
				unlink("./database/$_GET[data]/$_GET[id].txt",stream_context_create(['gs'=>['Content-Type'=>'text/plain']]));
			}
			if($_POST[titulo]&&$_POST[proponente]&&$_POST[contraparte]&&$_POST[data]){
				$_POST[data]=substr(preg_replace('/[^0-9-]/','',$_POST[data]),0,10);
				$pasta=substr($_POST[data],0,7);
				$idcaso=md5($_SESSION[user].date("YmdHis"));
				$novoregistro=$idcaso.",".$pasta.",".validchars($_POST[titulo])."\n";
				file_put_contents("./casos/$_SESSION[user].txt",file_get_contents("./casos/$_SESSION[user].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])).$novoregistro,0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))or die("Erro ao Substituir o Arquivo Database");
				$novoregistro=$_SESSION[user]."\n".$_POST[data]."\n".validchars($_POST[titulo])."\n".validchars($_POST[proponente])."\n".validchars($_POST[contraparte])."\n";
				file_put_contents("./database/$pasta/$idcaso.txt",file_get_contents("./database/$_POST[data]/$idcaso.txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])).$novoregistro,0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))or die("Erro ao Substituir o Arquivo Database");
			}
			if($_GET[debug]=="1")echo "<hr><pre>".file_get_contents("./casos/$_SESSION[user].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))."</pre><hr><br>\n";
			$casos=explode("\n",file_get_contents("./casos/$_SESSION[user].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])));
			if(count($casos)>1){
				echo "<table>\n<tr><th colspan=3>Processos Cadastrados</th></tr>\n";
				foreach($casos as $caso)if($caso){
					$caso=explode(",",$caso);
					$dtpt=explode("-",$caso[1]);
					echo "<tr><td><a href=index.php?mdl=caso&data=$caso[1]&id=$caso[0]>$caso[0]</a></td><td>$dtpt[1]/$dtpt[0]</td><td>$caso[2]</td></tr>\n"; 
				}
				echo "</table><br><br>\n";
			}
			echo "<input style='padding:12px;' type=button value='Cadastrar nova Ação' onclick=\"document.getElementById('novaacao').style.display=''; this.style.display='none';\"><br><br>\n";
			echo "<form action=index.php method=post>\n";
			echo "<table id=novaacao style='display:none;'>\n";
			echo "<tr><th colspan=2>Cadastro de Nova Causa</th></tr>\n";
			echo "<tr><td>Título</td><td><input required name=titulo></td></tr>\n";
			echo "<tr><td>Proponente</td><td><input required name=proponente></td></tr>\n";
			echo "<tr><td>ContraParte</td><td><input required name=contraparte></td></tr>\n";
			echo "<tr><td>Comarca</td><td><input name=comarca></td></tr>\n";
			echo "<tr><td>Data Acolhimento</td><td><input type=date name=data required></td></tr>\n";
			echo "<tr><td colspan=2 style='text-align:right;'><input type=submit value=Cadastrar></td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		break;
		}
	}
	else{
		switch($_GET[mdl]){
		case "caso":
			$detalhes=explode("\n",file_get_contents("./database/$_GET[id].txt",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])));
			echo "<table>\n";
			echo "<tr><th colspan=2>$detalhes[2]</th></tr>\n";
			echo "<tr><td>Proponente</td><td>$detalhes[3]</td></tr>\n";
			echo "<tr><td>ContraParte</td><td>$detalhes[4]</td></tr>\n";
			echo "</table><br><br>\n";
			echo "<table><tr><th>Adicionar ao Processo</th></tr><tr><td><input type=file><br><textarea style='width:360px; height:80px;'></textarea><div style='text-align:right;'><input style='padding:12px;' type=button value='Enviar nova Entrada'></div></td></tr></table><br><br>\n";
			echo "<input style='padding:12px; width:200px;' type=button value='Voltar à Lista de Processos' onclick=\"location.href='index.php';\">\n";
		break;
		default:
			$pastas=array();
			if($dh=opendir("./database",stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))){
				while(false!==($arq=readdir($dh)))array_push($pastas,$arq);
				closedir($dh);
			}
			$arqs=array();
			foreach($pastas as $pasta){
				if($dh=opendir("./database/$pasta",stream_context_create(['gs'=>['Content-Type'=>'text/plain']]))){
					while(false!==($arq=readdir($dh)))array_push($arqs,$pasta."$arq");
					closedir($dh);
				}
			}
			$links="";
			foreach($arqs as $arq){
				$detalhes=explode("\n",file_get_contents("./database/$arq",0,stream_context_create(['gs'=>['Content-Type'=>'text/plain']])));
				if(strstr($detalhes[5],$_SESSION[user])){
					$idcaso=str_replace(".txt","",$arq);
					$links.="<tr><td><a href=index.php?mdl=caso&id=$idcaso>$idcaso</td><td><a href=index.php?mdl=caso&id=$idcaso>$detalhes[2]</a></td></tr>\n";
				}
			}
			if($links)echo "<table><tr><th colspan=2>Processos Judiciais</th></tr>\n$links</table>\n";
			else echo "Você ainda não está autorizado a acessar nenhuma Causa Judicial cadastrada.\n";
		break;
		}
	}
}
echo "</div></div>\n";
if($_SESSION[user])echo "<div style='text-align:right; padding:12px;'><a href=index.php?mdl=logout>Logout</a></div>\n";
echo "<div style='background-color:#875650; color:#dddddd; font-size:18px; padding:12px; text-align:right;'>Software de Apoio ao Sistema Judiciário Brasileiro<br>@megahack-team-six</div>\n";
echo "</body>\n";
echo "</html>";
?>