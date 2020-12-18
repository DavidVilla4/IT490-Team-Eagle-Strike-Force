<h1><b> Recipe Website And App </b></h1>
<p> 
  Goal is to make a website that allows a user to view recipes that are rated on popularity. 
  The users can also comment on the recipes and make their own as they see fit. 
  To view other recipes Spoonacular offers a search function to find recipes specified to their search by ingredient.

  <b>Front End:</b> PHP, HTML, CSS, JavaScript</br>
  <b>Backend:</b> PHP, MySQL</br>
  <b>Technologies:</b> RabbitMQ, MySQL, GIT, Bootstrap, Oracle VirtualBox</br>
  <b>DMZ/Recipe Data Source:</b> Spoonacular, UniRest</br>
</p>
________________________________________________________________________________________________________________________
<h1><b> Change Log </b></h1>
<ol>
  <h2> 1. Git History </h2>
  <p><a href="https://github.com/DavidVilla4/IT490-Team-Eagle-Strike-Force.git">Project Repo</a></p>

  <h2> 2. Trello History </h2>
  <p>Accessed through the file in the master branch on github</p>

  <h2> 3. Server Documentation </h2>
   <ul>
    <p>
      Software for server connectivity is done through Hamachi and RabbitMQ which can be installed from the links below. Also KeepAlived is used for the hot standby.</br>
      <a href="https://hamachi.en.softonic.com/"> - Hamachi Download</a></br><a href="https://www.rabbitmq.com/download.html"> - RabbitMQ Download</a></br><a href="https://www.redhat.com/sysadmin/keepalived-basics"> - KeepAlived Download</a>
      </br></br>
      <b>Required Packages:</b> php, mysql/mysqli, gitk, php-amqp, rabbitmqserver</br>
      </br>
      For each of the pillars of the website listed under the goal, the .ini files BROKER_HOST value will need to be changed to the hamachi IP address
      of the computer hosting the servers.
    </p>
    </br>
    <p>
      To run the application:</br>
      <ul>
        <p>
          RabbitMQ</br>
          - Enable rabbitmq_management_plugin and start the rabbitMQ instance in web browser</br>
          - In terminal start loggingServer.php file to scan for errors from the network (This wil need to be done on all computers for log distribution)</br>
          - Start clusterServer.php for deployment and cluster to be set up</br>
          Back-End</br>
          - Ensure the database is working and does not need to be exchanged for the backup</br>
          - Start the testRabbitMQServer.php to have the connectivty between the website and database</br>
          - Start the ClientServer.php to enable connectivity between the database and DMZ</br>
          - Start Firewall program (Will need to be run on Front-End and DMZ as well)
          Front-End</br>
          - Ensure the web server that the website resides in is working and open to be able to access the website</br>
          DMZ</br>
          - Start RabbitMQServer.php to provide connectivity to the data source and the database</br>
        </p>
      </ul>
     Tests:</br>
      <ul>
        <p>
          - Create an account</br>
          - Login to created account</br>
          - Look at recipes in the database currently and their popularity</br>
          - Search for new recipes by ingredient name</br>
          - Create your own recipe</br>
          - Comment on other recipes through facebook</br>
          - Vote on other recipes</br>
        </p>
       </ul>
      Restart Procedures:</br>
       <ul>
        <p>
          -Create an exchange and queue for the DMZ, front-end, and back-end in the rabbitMQ website manager and bind each of them to each other
          -Use the backup database in the github repo
          -Continue with the steps above and the tests following
        </p>
       </ul>
    </p>
   </ul>
</ol>
________________________________________________________________________________________________________________________
<h1>Authors</h1>
<p>
 Kyle Partyka - RabbitMQ - <a href="https://github.com/kwp5">kwp5</a></br>
 Allison Mccarthy - Database - <a href="https://github.com/allisonmcca">allisonmcca</a></br>
 David Villa - Website - <a href="https://github.com/DavidVilla4">DavidVilla4</a></br>
 Giuseppe Logrande - DMZ - <a href="https://github.com/glogrande">glogrande</a>
</p>
