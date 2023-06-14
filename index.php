<!DOCTYPE html>
<html>
<head>
  <title>Statut du service nm_gadget</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    h1 {
      color: #333;
    }

    p {
      font-size: 18px;
      color: #555;
    }

    button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #4caf50;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    button.clicked {
      background-color: #ff0000;
    }

    form {
      margin-top: 20px;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var changeStatusButton = document.getElementById('changeStatusButton');

      changeStatusButton.addEventListener('click', function() {
        this.classList.add('clicked');
      });
    });
  </script>
</head>
<body>
  <h1>Statut du service nm_gadget</h1>
  <?php
  header("refresh: 60");
  $output = shell_exec('/bin/systemctl -q is-enabled nm_gadget.service');
  $status = trim($output);

  if ($status === "0") {
    echo "<p>Le RPI 0 est en mode RDNIS (en mode Keypass).</p>";
  } else {
    echo "<p>Le RPI 0 est en mode classique.</p>";
  }
  ?>

  <form method="post">
    <input type="hidden" name="changeStatus" value="1">
    <button id="changeStatusButton" type="submit">Changer le statut</button>
  </form>

  <form method="post">
    <input type="hidden" name="checkStatus" value="1">
    <button type="submit">Vérifier le statut</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['changeStatus']) && $_POST['changeStatus'] === '1') {
      $output = shell_exec('bash rndis.sh');
      // Effectuer d'autres actions si nécessaire après le changement de statut

      // Mettre à jour le statut affiché
      $output = shell_exec('/bin/systemctl -q is-enabled nm_gadget.service');
      $status = trim($output);

      if ($status === "0") {
        echo "<p>Le RPI 0 est maintenant en mode RDNIS (en mode Keypass) et il va redémarrer dans 1 minute.</p>";
      } else {
        echo "<p>Le RPI 0 est maintenant en mode classique et il va redémarrer dans 1 minute.</p>";
      }
    } elseif (isset($_POST['checkStatus']) && $_POST['checkStatus'] === '1') {
      // Mettre à jour le statut affiché
      $output = shell_exec('/bin/systemctl -q is-enabled nm_gadget.service');
      $status = trim($output);

      if ($status === "0") {
        echo "<p>Le RPI 0 est en mode RDNIS (en mode Keypass).</p>";
      } else {
        echo "<p>Le RPI 0 est en mode classique.</p>";
      }
    }
  }
  ?>
</body>
</html>
