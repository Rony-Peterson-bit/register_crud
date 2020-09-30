<?php 
Class Pessoa{
      //contrutor
      //conexao orientada a objeto
      private $pdo;

      public function __construct($dbname, $host, $user, $senha)
      {
          try 
          {
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
          }
           catch (PDOException $e)
            {
              echo "Erro com banco de dados: ".$e->getMessage();
              exit();
            }
            catch (Exception $e)
            {
               echo "Erro genérico: ".$e->getMessage();
               exit();
           }
      }

      //FUNÇÃO PARA BUSCAR DADOS E COLOCAR NO CANTO DIREITO DA TELA
      public function buscarDados()
      {
          $res = array();//caso banco esteja vazio não haverá erro, retornara array vazio
        //    $cmd = $this->pdo->prepare("SELECT*FROM  pessoas ORDER BY nome");// METODO PREPARE
          $cmd = $this->pdo->query("SELECT * FROM  pessoas ORDER BY nome"); //Query
          $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
          return $res;
      }

      //FUNÇÃO DE CADASTRAR PESSOAS NO BANCO DE DADOS
      public function cadastrarPessoa($nome, $telefone, $email)
      {
          //Antes de cadastrar verificar se já possui o email cadastrado
          $cmd = $this->pdo->prepare("SELECT id from pessoas WHERE email = :e");
          $cmd->bindValue(":e",$email);
          $cmd->execute();
          if($cmd->rowCount() > 0)// >0 email já exite no banco de dados
          {
              return false;
          }
          else //não encontrado email
          {
              $cmd = $this->pdo->prepare("INSERT INTO pessoas(nome, telefone, email) VALUES(:n, :t, :e)");
              $cmd->bindValue(":n",$nome);
              $cmd->bindValue(":t",$telefone);
              $cmd->bindValue(":e",$email);
              $cmd->execute();
              return true;
          }                    
      }
      public function excluirPessoa($id)
      {
          $cmd = $this->pdo->prepare("DELETE FROM pessoas WHERE id = :id");
          $cmd->bindValue(":id",$id);
          $cmd->execute();
      }
      //BUSCAR DADOS DE UMA PESSOA ESPECIFICA
      public function buscarDadosPessoa($id)
      {
          $res = array();
          $cmd = $this->pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
          $cmd->bindValue(":id",$id);
          $cmd->execute();
          $res = $cmd->fetch(PDO::FETCH_ASSOC);
          return $res;
      }
      
      
      //ATUALIZAR OS DADOS
      public function atualizarDados($id,$nome,$telefone,$email)
      {
        //ANTES DE ATUALIZAR VERI  FICAR SE EMAIL JÁ ESTÁ CADASTRADO
        //Antes de cadastrar verificar se já possui o email cadastrado
        $cmd = $this->pdo->prepare("SELECT id from pessoas WHERE email = :e");
        $cmd->bindValue(":e",$email);
        $cmd->execute();
        if($cmd->rowCount() > 0)// >0 email já exite no banco de dados
        {
            return false;
        }
        else
        {
          $cmd = $this->pdo->prepare("UPDATE pessoas SET nome = :n, telefone = :t, email = :e WHERE id = :id");
          $cmd->bindValue(":n",$nome);
          $cmd->bindValue(":t",$telefone);
          $cmd->bindValue(":e",$email);
          $cmd->bindValue(":id",$id);
          $cmd->execute();
          return true;
        }  
      }
}
 
?>