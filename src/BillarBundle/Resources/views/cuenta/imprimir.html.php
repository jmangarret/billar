<?php
use Dompdf\Dompdf;
ob_start();
?>
<style>
body {
font-family: Arial, sans-serif;
font-size: 10px;
padding: 0px;
margin: 2px;
}
</style>

<table class="table">
    <tbody>
        <tr>
            <td><b>Cuenta #</b></td>
            <td><?php echo $Cuenta->getId(); ?></td>
        </tr>
        <tr>
            <td><b>Mesa:</b></td>
            <td><?php echo $Cuenta->getMesa()->getNombre(); ?></td>
        </tr>
        <tr>
            <td><b>Cliente:</b></td>
            <td><?php echo $Cuenta->getCliente()->getNombre(); ?></td>
        </tr>
        <tr>
            <td><b>Fecha:</b></td>
            <td><?php echo date('d/m/Y H:i:s'); ?></td>
        </tr>
    </tbody>
</table>
<?php $total = 0 ?>
<table class="table">
    <thead>
        <tr>
            <td><b>Producto</b></td>
            <td><b>Cantidad</b></td>
            <td><b>Precio</b></td>
            <td><b>Total</b></td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($detalle as $d) { ?>
    <?php $espromo = ($d->getTipoPrecio()=='P' ? '*' : '') ?>
        <tr>
            <td> <?php echo $d->getProducto()->getNombre(); ?></td>
            <?php if ($d->getProducto()->getTipoProducto()=='Tiempo'){ ?>                
                <td> <?php echo gmdate('H:i', floor($d->getCantidad() * 3600)); ?></td>
            <?php }else{ ?>
                <td> <?php echo number_format($d->getCantidad(),2); ?></td>
            <?php } ?>            
            <td align="right"> <?php echo number_format($d->getPrecio(),2) . $espromo; ?></td>
            <td align="right"> <?php echo number_format($d->getPrecio() *  $d->getCantidad(),2); ?></td>
            <?php $total += $d->getPrecio() * $d->getCantidad(); ?>
        </tr>
   <?php } ?>
    <tr>
        <td><b>Total</b></td>
        <td colspan="3" align="right"><b><?php echo number_format($total,2) ?></b></td>
    </tr>
    </tbody>
</table>

<?php
//generate some PDFs!
$dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
$dompdf->loadHtml(ob_get_clean());
$dompdf->render();
$dompdf->stream("sample.pdf", array("Attachment"=>0));

?>