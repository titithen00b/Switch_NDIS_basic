<!DOCTYPE html>
<html>
<head>
  <title>Statut du service nm_gadget</title>
  <style>
      body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    h1 {
      text-align: center;
    }

    .status-message {
      margin-bottom: 20px;
      padding: 10px;
      background-color: #f1f1f1;
      text-align: center;
    }

    .status-message.success {
      color: green;
    }

    .status-message.error {
      color: red;
    }

    .button-container {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .button-container button {
      margin: 0 10px;
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .button-container #changeStatusButton {
      background-color: #3498db;
      color: #fff;
    }

    .button-container #checkStatusButton {
      background-color: #f39c12;
      color: #fff;
    }

    .button-container #stopMachineButton {
      background-color: #e74c3c;
      color: #fff;
    }

  </style>
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var changeStatusButton = document.getElementById('changeStatusButton');
      var checkStatusButton = document.getElementById('checkStatusButton');
      var stopMachineButton = document.getElementById('stopMachineButton');
      var statusMessage = document.getElementById('statusMessage');

      changeStatusButton.addEventListener('click', function() {
        statusMessage.textContent = 'Changement de statut en cours...';
        statusMessage.classList.remove('success');
        statusMessage.classList.remove('error');
      });

      checkStatusButton.addEventListener('click', function() {
        statusMessage.textContent = 'Vérification du statut en cours...';
        statusMessage.classList.remove('success');
        statusMessage.classList.remove('error');
      });

      stopMachineButton.addEventListener('click', function() {
        statusMessage.textContent = 'Arrêt de la machine en cours...';
        statusMessage.classList.remove('success');
        statusMessage.classList.remove('error');
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var stopMachineButton = document.getElementById('stopMachineButton');
      var statusMessage = document.getElementById('statusMessage');

      stopMachineButton.addEventListener('click', function(event) {
        var confirmStop = confirm("Êtes-vous sûr de vouloir arrêter la machine ?");
        if (!confirmStop) {
          // Annuler l'action d'arrêt de la machine
          event.preventDefault();
        } else {
          // Fermer l'onglet après avoir arrêté la machine
          window.close();
        }
      });
    });
  </script>

</head>
<body>
  <div class="container">
    <h1>Statut du service nm_gadget</h1>
    <?php
    $status = null;
    $return_var = null;
    $output = null;

    system('sudo /bin/systemctl -q is-enabled nm_gadget.service', $return_var);

    if ($return_var === 0) {
      $status = "0";
    }

    if ($status === "0") {
      echo "<p id='statusMessage' class='status-message success'>Le RPI 0 est en mode RDNIS (en mode Keypass).</p>";
    } else {
      echo "<p id='statusMessage' class='status-message success'>Le RPI 0 est en mode classique.</p>";
    }
    ?>

    <div class="button-container">
      <form method="post">
        <input type="hidden" name="changeStatus" value="1">
        <button id="changeStatusButton" type="submit">Changer le statut</button>
      </form>

      <form method="post">
        <input type="hidden" name="checkStatus" value="1">
        <button id="checkStatusButton" type="submit">Vérifier le statut</button>
      </form>

      <form method="post">
        <input type="hidden" name="stopMachine" value="1">
        <button id="stopMachineButton" type="submit">Arrêter la machine</button>
      </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['changeStatus']) && $_POST['changeStatus'] === '1') {
        system('bash rndis.sh');
        // Effectuer d'autres actions si nécessaire après le changement de statut

        // Mettre à jour le statut affiché
        $status = null;
        $return_var = null;
        $output = null;

        system('sudo /bin/systemctl -q is-enabled nm_gadget.service', $return_var);

        if ($return_var === 0) {
          $status = "0";
        }

        if ($status === "0") {
          echo "<script>document.getElementById('statusMessage').textContent = 'Le RPI 0 est maintenant en mode RDNIS (en mode Keypass).';</script>";
        } else {
          echo "<script>document.getElementById('statusMessage').textContent = 'Le RPI 0 est maintenant en mode classique.';</script>";
        }
      } elseif (isset($_POST['checkStatus']) && $_POST['checkStatus'] === '1') {
        // Mettre à jour le statut affiché
        $status = null;
        $return_var = null;
        $output = null;

        system('sudo /bin/systemctl -q is-enabled nm_gadget.service', $return_var);

        if ($return_var === 0) {
          $status = "0";
        }

        if ($status === "0") {
          echo "<script>document.getElementById('statusMessage').textContent = 'Le RPI 0 est en mode RDNIS (en mode Keypass).';</script>";
        } else {
          echo "<script>document.getElementById('statusMessage').textContent = 'Le RPI 0 est en mode classique.';</script>";
        }
      } elseif (isset($_POST['stopMachine']) && $_POST['stopMachine'] === '1') {
        // Arrêt de la machine
        system('sudo poweroff');
        // Afficher le message d'arrêt de la machine
        echo "<script>document.getElementById('statusMessage').textContent = 'Arrêt de la machine en cours...';</script>";
      }
    }
    ?>
  </div>
</body>
</html>
