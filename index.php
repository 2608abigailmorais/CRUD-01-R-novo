<!DOCTYPE html>
<?php 
   include_once "conf/default.inc.php";
   require_once "conf/Conexao.php";
   $title = "Lista de Carros";
   $procurar = isset($_POST["procurar"]) ? $_POST["procurar"] : ""; 
   $consulta = isset($_POST["consulta"]) ? $_POST["consulta"] : 0;
?>
<html>
<head>
    <meta charset="UTF-8">
    <title> <?php echo $title; ?> </title>
    <link rel="stylesheet" href= "css/estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
    <form method="post">
    <fieldset>
        <legend>Procurar carros</legend>
        <input type="text"   name="procurar" id="procurar" size="37" value="<?php echo $procurar;?>"><br>
        <input type="radio" name="consulta" value="1" 
        <?php if ($consulta == 1) echo 'checked'; ?>>Nome<br><br>
        <input type="radio" name="consulta" value="2"
        <?php if($consulta == 2)echo "checked";?>>Valor<br><br>
        <input type="radio" name="consulta" value="3"
        <?php if($consulta==3)echo "checked";?>>Quilometragem<br><br>
        <input type="submit" name="acao" id="acao">
        <br><br>
        <table>
        <tr>
            <td><b>Código</b></td>
            <td><b>Nome</b></td> 
            <td><b>Valor</b></td>
            <td><b>Km</b></td>
            <td><b>Data de Fabricação</b></td>
            <td><b>Anos</b></td>
            <td><b>Média Km/Ano</b></td>
            <td><b>Valor com Descontos</b></td>
        </tr>
        <?php
        $pdo = Conexao::getInstance(); 
        if($consulta == 1) 
            $procura = $pdo->query("SELECT * FROM carro 
                                     WHERE nome LIKE '$procurar%' 
                                     ORDER BY nome");   
        elseif($consulta == 2) 
            $procura = $pdo->query("SELECT * FROM carro 
                                     WHERE valor<=  '$procurar%' 
                                     ORDER BY valor");
        else
            $procura = $pdo->query("SELECT * FROM carro 
                                     WHERE km<=  '$procurar%' 
                                     ORDER BY km");       
            while ($linha = $procura->fetch(PDO::FETCH_ASSOC))    {    
            $hoje = date("Y");
            $fab = date("Y", strtotime($linha['dataFabricacao']));
            $anos = $hoje - $fab;
            $média = $linha['km'] / $anos; 
            
            $valornovo = 0;
            $class = "black";
            if($linha['km'] >= 100000 && $anos <10){
                $class = "red";
                $desconto = $linha['valor'] * 0.10;
                $valornovo = $linha['valor'] - $desconto;                
    
            }
            elseif($linha['km'] < 100000 && $anos >= 10){
                $class = "red"; 
                $desconto = $linha['valor'] * 0.10;
                $valornovo = $linha['valor'] - $desconto;        
            }
            elseif($linha['km'] >= 100000 && $anos >= 10){
                $class = "red";
                $desconto = $linha['valor'] * 0.20;
                $valornovo = $linha['valor'] - $desconto;
            }
            else {
                $valornovo = $linha['valor'];
            }
            
        
        ?>
        <tr>
			<td><?php echo $linha['id'];?></td>
            <td><?php echo $linha['nome'];?></td>   
            <td><?php echo number_format($linha['valor'], 1, ',', '.');?></td>
            <td style = "color: <?php  echo  $class ; ?>" ><?php echo number_format($linha['km'], 1, ',', '.');?></td>  
            <td><?php echo date("d/m/Y",strtotime($linha['dataFabricacao']));?></td>  
            <td style = "color: <?php  echo  $class ; ?>" ><?php echo $anos;?></td> 
            <td><?php echo number_format($média, 2, ',', '.')?></td> 
            <td style = "color: <?php  echo  $class ; ?>" ><?php echo number_format($valornovo, 1, ',', '.')?></td>

        </tr>
    <?php } ?>
           
        </table>
    </fieldset>
    </form>
</body>
</html>

