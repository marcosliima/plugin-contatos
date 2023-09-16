<?php
// recuperar e exibir a lista de contatos com telefones
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
    <title>Lista de Contatos</title>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'includes/lista-contatos.css'; ?>">
    
</head>
<body>
    <h1>Lista de Pessoas</h1> 

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefones</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($contatos as $contato) : ?>
            <tr>
                <td><?php echo $contato['id']; ?></td>
                <td><?php echo $contato['nome']; ?></td>
                <td><?php echo $contato['email']; ?></td>
                <td>
                    <?php
                    $telefones = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT telefone FROM {$wpdb->prefix}telefone_pessoas WHERE contato_id = %d",
                            $contato['id']
                        ),
                        ARRAY_A
                    );

                    if (!empty($telefones)) {
                        foreach ($telefones as $telefone) {
                            echo $telefone['telefone'] . '<br>';
                        }
                    } else {
                        echo 'Nenhum telefone registrado.';
                    }
                    ?>
                </td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=editar-contato&id=' . $contato['id']); ?>">Editar</a>
                    <a href="<?php echo admin_url('admin.php?page=excluir-contato&id=' . $contato['id']); ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
