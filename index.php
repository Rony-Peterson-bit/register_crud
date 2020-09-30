<?php 
/*Chamar*/ 
require_once 'classe-pessoa.php';
/*Estanciar*/
$p = new Pessoa("crudpdo", "localhost","root", "");

?>
<!DOCTYPE html>
<html lang="br-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Document</title>
</head>

<body>
    <?php 
       if (isset($_POST['nome'])) //SE CLICOU NO BOTÃO CADASTRAR OU EDITAR
       {   if(isset($_GET['id_up']) && !empty($_GET['id_up']))
            {
                $id_upd = addslashes($_GET['id_up']);
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
           if (!empty($nome) && !empty($telefone) && !empty($email)  ) 
           {
            //--------------------------------EDITAR --------------------------------------------------  
               if (!$p->atualizarDados($id_upd, $nome, $telefone, $email)) 
               {
                   echo "Email já está cadastrado!";
               }
           }
           else
           {
               echo "Preencha todos od campos";
           }
            }

            
         //---------------------------------CADASTRAR----------------------------------------------------------------
           else
           {
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);
            if (!empty($nome) && !empty($telefone) && !empty($email)  ) 
            {
                //cadastrar
                if (!$p->cadastrarPessoa($nome, $telefone, $email)) 
                {
                    echo "Email já está cadastrado!";
                }
            }
            else
            {
                echo "Preencha todos od campos";
            }
           }
               
           
           
       }
    ?>
    <?php 
         if(isset($_GET['id_up']))//SE CLICOU NO BOTÃO EDITAR
         {
             $id_update = addslashes($_GET['id_up']);
             $res = $p->buscarDadosPessoa($id_update);

         }
    ?>
    <section id="esquerda">
    <form method="post" action=""> 
         <h2>CADASTRAR PESSOA</h2>
         <label for="nome">Nome</label>
         <input type="text" name="nome" id="nome" value="<?php if(isset($res)){echo $res['nome'];} ?>">

         <label for="telefone">Telefone</label>
         <input type="text" name="telefone" id="telefone" value="<?php if(isset($res)){echo $res['telefone'];} ?>">

         <label for="email">Email</label>
         <input type="text" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];} ?>">

         <input type="submit" value="<?php if(isset($res)){echo "ATUALIZAR";}else{echo "ENVIAR";} ?>">
         
    </form>
    </section>


    <section id="direita"> 
    <table> 
        <tr id="titulo">
           <td>NOME</td>
           <td>TELEFONE</td>
           <td colspan="2">EMAIL</td>
        </tr>

        <?php 
            /*Acessar método para buscar os dados*/ 
            $dados = $p->buscarDados();
            if(count($dados) > 0)//Se tem pessoas no banco de dados
            {
                for ($i=0; $i < count($dados); $i++) { 
                    echo  "<tr>";  
                    foreach ($dados[$i] as $k => $v) 
                    {
                        if($k != "id")
                        {
                           echo "<td> ".$v." </td> ";
                        }
                    }
        ?>            
             <td>
                <a href="index.php?id_up=<?php echo $dados[$i] ['id'] ?> ">Editar</a>
                <a href="index.php?id=<?php echo $dados[$i] ['id'] ?> ">Excluir</a>
            </td> <!-- Fazer link virar botão -->
        <?php 
                    echo  "</tr>";
                }                               
            }
            else//O BANCO DE DADOS ESTÁ VAZIO
            {
                echo "Ainda não há pessoas cadastradas!";
            }
            //Teste para ver como chegam as informações no browser      
            //echo "<pre>";
            //var_dump($dados);
            //echo "</pre>";
        ?> 
       <!-- Exemplo de como ficaria apenas com html
        <tr>
           <td>Maria</td>
           <td>555555</td>
           <td>ma@gmail</td>
           <td><a href="">Editar</a><a href="">Excluir</a></td> 
        
        </tr>
        -->
    </table>
    </section>
</body>
</html>
<?php 
    if(isset($_GET['id']))
    {
        $id_pessoa = addslashes($_GET['id']);
        $p->excluirPessoa($id_pessoa);
        header("location: index.php");
    }
?>