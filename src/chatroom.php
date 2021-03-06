<?php require_once('./include/sessions.php'); ?>
<?php require_once('./database/open-connection.php'); ?>
<?php require_once('./database/queries.php'); ?>
<?php require_once('./include/functions.php'); ?>
<?php confirm_user_authentication(); ?>
<?php
  $username = $_SESSION['username'];
  $room_id = $_GET['room-id'];

  // search query
  $query = $query = get_chatroom_user_query($room_id);
  $result = mysqli_query($connection, $query);

  $found = false;

  while($chatroom_user = mysqli_fetch_assoc($result))
  {
    if($chatroom_user['User'] == $username)
    {
      $found = true;
    }
  }

  unset($query);
  unset($result);

  // if user is not found add the user
  if(!$found)
  {
    // add user to the chatroom list
    $query = add_chatroom_user_query($username, $room_id);
    $result = mysqli_query($connection, $query);
  }

  unset($query);
  unset($result);

  // retrieve the chatroom list
  $query = get_chatroom_user_query($room_id);
  $result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <title>GyroChan</title>
    <!-- Bootstrap Core CSS -->
    <?php include "bootstrap.php"; ?>
    <!-- Custom CSS -->
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/chatroom-style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php include "navbar.php" ?>
    <div id="wrapper">
      <?php include "sidebar.php"; ?>
        <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Chat Room: <?= $_GET['room-name']; ?></h1><br>
                <!--
                <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                -->
                <div class="row">
                  <div class="col-md-3">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>
                            Connected Users
                          </th>
                        </tr>
                      </thead>
                      <tbody id="chat-list-tbody">
                          <?php
                            while($chatroom_user = mysqli_fetch_assoc($result))
                            {
                              echo '<tr class="chatroom-list">';
                              echo '<td>';
                              echo '<span class="glyphicon glyphicon-ok-sign"></span>&nbsp;';
                              echo '<span class="connected-user">' . $chatroom_user['User'] .'</span>';
                              echo '</td>';
                              echo '</tr>';
                            }

                            mysqli_free_result($result);
                          ?>
                      </tbody>
                    </table>
                    <form method="POST" action="dashboard.php">
                      <input type="hidden" name="room-id" value="<?= $_GET['room-id'] ?>">
                      <input class="btn btn-warning btn-block" type="submit" value="Leave Chatroom">
                    </form>
                  </div>
                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        Conversations&nbsp;&nbsp;<span class="glyphicon glyphicon-comment"></span>
                      </div>
                    </div>
                    <div class="panel-body">
                      <ul id="chat" class="chat">
                      <!--
                        CHAT LOGS HERE..
                      -->
                      </ul>
                    </div>
                  <div class="panel-footer">
                    <div class="input-group">
                      <input id="chat-input" type="text" class="form-control" placeholder="Type your message here..." />
                      <?php
                        echo '<input type="hidden" id="room-id" value="' . $_GET['room-id'] . '">';
                        echo '<input type="hidden" id="room-name" value="' . $_GET['room-name'] . '">';
                      ?>
                      <span class="input-group-btn">
                        <button class="btn btn-success" id="sendChat">Send</button>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "scripts.php"; ?>
    <!-- Menu Toggle Script -->
    <script src="js/sidebar.js"></script>
    <script src="js/chatroom.js"></script>
  </body>
</html>
<?php require_once('./database/close-connection.php'); ?>
