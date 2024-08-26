# PonderadaSemana3
Este projeto foi desenvolvido com o objetivo de demonstrar uma integração entre uma aplicação web e um banco de dados na AWS. Foi criada uma tabela para armazenar dados sobre corpos celestes como nome, tipo, posição no céu e habitabilidade. Para demonstrar o processo, foi feito este [vídeo](https://youtu.be/LOZGipwltDI).

## Arquitetura Utilizada
A arquitetura abaixo foi utilizada como base para a construção da infraestrutura. Além disso, foram utilizados os tutoriais disponíveis [neste link](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/TUT_WebAppWithRDS.html). Com base neles, foram criadas instâncias EC2 e um banco de dados MySQL no serviço RDS.

![Arquitetura](./assets/con-VPC-sec-grp.png)

### Estrutura da Tabela Criada

1. **Nome da tabela: ESTELARSYSTEM**
   Esta tabela foi criada com o objetivo de armazenar informações referentes a corpos celestes de diferentes tipos e sistemas, contendo seus nomes, tipos e suas posições no céu para observação astronômica.
   - `ID` (inteiro, chave primária, auto-incremento)
   - `NAME` (VARCHAR) - Nome do corpo celeste
   - `TYPE` (VARCHAR) - Tipo do corpo celeste, ex: Estrela, Planeta, Exoplaneta, Nebulosa, etc.
   - `DECLINATION` (FLOAT) - Declinação (coordenada astronômica no céu)
   - `AR` (FLOAT) - Ascensão Reta (coordenada astronômica no céu)
   - `HABITABLE` (BOOL) - True para corpo habitável, False para inabitável

### Configuração do Servidor

1. **Conexão e Configuração do EC2**

   ```bash
   ssh -i location_of_pem_file ec2-user@ec2-instance-public-dns-name
   ```

   E para iniciar o servidor Apache:

   ```bash
    sudo dnf update -y   
    ```

   ```bash
    sudo dnf install -y httpd php php-mysqli mariadb105  
    ```
    
   ```bash
    sudo systemctl start httpd
    ```
    
   ```bash
    sudo systemctl enable httpd  
    ```
    
2. **Permissões de arquivo e diretório no servidor**

    Durante a configuração do servidor, é importante ajustar as permissões para garantir que o servidor web tenha acesso adequado aos arquivos e que outros usuários do sistema não tenham permissões excessivas.

    2.1 **Adicionar o usuário ec2-user ao grupo apache:**

   ```bash
    sudo usermod -a -G apache ec2-user  
    ```
    2.1 **Após isso, é necessário sair e voltar a conectar:**


   ```bash
    exit   
    ```
    Quando reconectar, é preciso verificar os grupos com o comando abaixo.
   ```bash
    groups   
    ```
    Devem estar neste formato, contendo apache dentro deles
   ```bash
    ec2-user adm wheel apache systemd-journal
    ```

    2.2 **Alterar a propriedade e permissões do diretório /var/www:**

   ```bash
    sudo chown -R ec2-user:apache /var/www
    ```
   ```bash
    sudo chmod 2775 /var/www
    find /var/www -type d -exec sudo chmod 2775 {} \;
    ```
   ```bash
    find /var/www -type f -exec sudo chmod 0664 {} \;
    ```

    Essas etapas garantem que o servidor Apache tenha permissões adequadas para gerenciar o conteúdo do site de forma segura e eficiente.


3. **Conectando apache web server ao RDS**

    3.1 **Criando arquivo de configuração do banco de dados**

   ```bash
    cd /var/www
    mkdir inc
    cd inc
    ```     
   ```bash
    >dbinfo.inc
    nano dbinfo.inc
    ```   


   ```bash
        <?php

    define('DB_SERVER', 'db_instance_endpoint');
    define('DB_USERNAME', 'tutorial_user');
    define('DB_PASSWORD', 'master password');
    define('DB_DATABASE', 'sample');
    ?>
    ```    
    Para salvar o arquivo , pressione ctrl+S para salvar as alterações e após ctrl+X para fechar a janela 

    3.2 **Criando pagina html para interagir com o banco de dados**

   ```bash
    cd /var/www/html
    ```     
   ```bash
    >SamplePage.php
    nano SamplePage.php
    ```     
    Após este comando aparecerá a mesma tela que o passo 3.1, você irá copiar o código php presente na pasta código e colar na janela, após isso irá apertar ctrl+S e ctrl+X para salvar e sair.

### Execução da Aplicação

 1. **Para acessar a aplicação vá até a instância EC2 e pegue o DNS IPv4 público**

 2. **Com posse dele, adicione /SamplePage.php para acessar a aplicação e testá-la**