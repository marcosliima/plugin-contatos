<?php
// Lógica para recuperar e exibir a lista de contatos com telefones
global $wpdb;

$contatos = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}contato_pessoas",
    ARRAY_A
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Adicionar Pessoas</title> <!-- Alterado o título da página -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'includes/add-contatos.css'; ?>">
    <!-- Vinculado o mesmo CSS do código anterior -->
</head>
<body>
    <h1>Adicionar Pessoas</h1> <!-- Adicionando o elemento h1 no cabeçalho -->

    <?php
    // verificando se o usuário está logado antes de permitir o acesso
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url());
        exit;
    }

    // processando o formulário e inserindo dados no banco de dados
    if (isset($_POST['adicionar_contato'])) {
        global $wpdb;

        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $telefones = $_POST['telefones'];

        // verificando se o contato já existe pelo email
        $contato_existente = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}contato_pessoas WHERE email = %s", $email),
            ARRAY_A
        );

        if ($contato_existente) {
            echo '<p style="color: red;">Contato já existe no nosso banco de dados.</p>';
        } else {
            // insirindo o email e nome na tabela "contato pessoas"
            $wpdb->insert(
                $wpdb->prefix . 'contato_pessoas',
                array(
                    'nome' => $nome,
                    'email' => $email,
                )
            );

            $contato_id = $wpdb->insert_id;

            // inserindo os telefones na tabela "telefone_pessoas"
            foreach ($telefones as $telefone) {
                $wpdb->insert(
                    $wpdb->prefix . 'telefone_pessoas',
                    array(
                        'contato_id' => $contato_id,
                        'telefone' => $telefone,
                    )
                );
            }

            echo '<p style="color: green;">Contato adicionado com sucesso!</p>';
        }
    }
    ?>

    <!-- Seu formulário HTML aqui -->
    <form method="post" action="">
        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br>

        <div id="telefones">
            <label for="telefones">Telefones:</label><br>
            <input type="text" name="telefones[]" required><br>
        </div>
        
        <button type="button" id="add_telefone">+ Adicionar Telefone</button><br>

        <input class="bt-contato" type="submit" name="adicionar_contato" value="Adicionar Contato">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const telefonesDiv = document.querySelector('#telefones');
            const addTelefoneBtn = document.getElementById('add_telefone');

            addTelefoneBtn.addEventListener('click', function () {
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'telefones[]';
                input.required = true;
                telefonesDiv.appendChild(input);
            });
        });
    </script>
</body>
</html>
