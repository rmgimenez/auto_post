<html>
<head>
<?php
    if(!(isset($pagina_titulo)))
    {
        $pagina_titulo = 'Auto Post';
    }
?>
    <title><?php echo $pagina_titulo; ?></title>
    
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css"/>
    
<style>
.conteudo{
    margin: auto;
    width: 75%;
    border: 3px solid black;
    padding: 10px;
}

.borda{
    border: 3px solid black;
}
</style>
</head>
<body>
<div class="conteudo">
    <div class="pure-g">
        <div class="pure-u-1-5"><p>Menu lateral</p></div>
        <div class="pure-u-4-5">
            <h2>Corpo do site</h2>
            <p>Uma linha</p>
        </div>
    </div>
</div>
<script>

</script>
</body>
</html>