<?php
$seHaInsertado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    $seHaInsertado = true;
    $username = htmlspecialchars($_POST['username']); // Protege el nombre de usuario de inyecciones

    // Construir la URL de la API usando el nombre de usuario proporcionado
    $api_url = 'https://api.twitch.tv/helix/users?login=' . $username;

    // Configuración de la conexión CURL
    $ch = curl_init($api_url);

    $headers = [
        'Authorization: Bearer cdrpstu2cmupxijmlcr6vlmdg7ugrg',  // Reemplaza con tu token OAuth válido
        'Client-Id: pdp08hcdlqz3u2l18wz5eeu6kyll93',  // Reemplaza con tu Client ID
        'Content-Type: application/json',
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar CURL y capturar la respuesta
    $response = curl_exec($ch);
    if ($response === false) {
        die('Error fetching data: ' . curl_error($ch));
    }
    curl_close($ch);

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);
    if ($data === null || !isset($data['data'][0])) {
        $error_message = "No se encontró el usuario.";
    } else {
        $user = $data['data'][0];
    }
} 
?>

<head>
  <meta charset="UTF-8" />
  <title>Buscar Usuario de Twitch</title>
  <meta name="description" content="Buscar detalles de usuario en Twitch" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css" />
</head>

<main>
    <h1>Buscar Usuario de Twitch</h1>
    <form method="POST">
        <label for="username">Nombre de usuario de Twitch:</label>
        <input type="text" name="username" id="username" placeholder="Ejemplo: auronplay" required />
        <button type="submit">Buscar</button>
    </form>
    <?php if ($seHaInsertado): ?>
        <section>
            <img src="<?= $user["profile_image_url"]; ?>" width="200" alt="Imagen de <?= $user["display_name"]; ?>" />
        </section>
        <hgroup>
            <h3><?= $user["display_name"]; ?> es <?= $user["broadcaster_type"]; ?></h3>
            <p>Descripción: <?= $user["description"]; ?></p>
        </hgroup>
        <section>
            <img src="<?= $user["offline_image_url"]; ?>" width="200" alt="Imagen offline de <?= $user["display_name"]; ?>" />
        </section>
    <?php endif; ?>

    <?php if (isset($response)): ?>
        <pre style="font-size: 12px; overflow:scroll; height:200px; width: 200px; white-space: pre-wrap; word-wrap: break-word; color: yellow;">
            <?php echo $response; ?>
        </pre>
    <?php endif; ?>
</main>

<style>
  :root {
    color-scheme: light dark;
  }
  body {
    display: grid;
    place-content: center;
  }
  section {
    display: flex;
    justify-content: center;
    text-align: center;
  }
  hgroup {
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }
  img {
    margin: 0 auto;
  }
</style>
