<a href="" class="primary">Inserir</a>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Ativo</th>
        <th>Grupo</th>
        <th>Ordem</th>
        <th>Forma</th>
        <th>Quantidade</th>
        <th>Sender</th>
        <th>Destino</th>
        <th>Chance</th>
        <th>Usar Adf.ly</th>
        <th>Tags</th>
        <th>Descricao</th>
        <th>Ação</th>
    </tr>
<?php
foreach($jobs_envio as $job)
{
?>
    <tr>
        <td><?php echo $job['id']; ?></td>
        <td><?php echo $job['ativo']; ?></td>
        <td><?php echo $job['grupo']; ?></td>
        <td><?php echo $job['ordem']; ?></td>
        <td><?php echo $job['ordem_forma']; ?></td>
        <td><?php echo $job['quantidade']; ?></td>
        <td><?php echo $job['sender_id']; ?></td>
        <td><?php echo $job['destino_id']; ?></td>
        <td><?php echo $job['chance_de_ser_executado']; ?></td>
        <td><?php echo $job['usar_encurtador_link']; ?></td>
        <td><?php echo $job['tags']; ?></td>
        <td><?php echo $job['descricao']; ?></td>
        <td><?php echo anchor('jobs_envio/alterar/'.$job['id'], 'Alterar'); ?> | Excluir</td>
    </tr>
<?php    
}
?>
</table>