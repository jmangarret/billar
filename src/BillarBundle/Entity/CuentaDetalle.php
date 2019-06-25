<?php

namespace BillarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CuentaDetalle
 *
 * @ORM\Table(name="cuenta_detalle",
 * indexes={
 * @ORM\Index(name="cuentadetalle_idx", columns={"id_cuenta"}),
 * @ORM\Index(name="productodetalle_idx", columns={"id_producto"})
 * })
 * @ORM\Entity(repositoryClass="BillarBundle\Repository\CuentaDetalleRepository")
 */
class CuentaDetalle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Cuenta", inversedBy="detalles")
     * @ORM\JoinColumn(name="id_cuenta", referencedColumnName="id", nullable=false)
     */
    protected $cuenta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="detalles")
     * @ORM\JoinColumn(name="id_producto", referencedColumnName="id", nullable=false)
     */
    protected $producto;

    /**
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=10, scale=2)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_precio", type="string", length=1)
     */
    private $tipoPrecio;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cuenta
     *
     * @param integer $cuenta
     *
     * @return CuentaDetalle
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return int
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set Producto
     *
     * @param integer $producto
     *
     * @return CuentaDetalle
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get Producto
     *
     * @return int
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return CuentaDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return CuentaDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set tipoPrecio
     *
     * @param string $tipoPrecio
     *
     * @return CuentaDetalle
     */
    public function setTipoPrecio($tipoPrecio)
    {
        $this->tipoPrecio = $tipoPrecio;

        return $this;
    }

    /**
     * Get tipoPrecio
     *
     * @return string
     */
    public function getTipoPrecio()
    {
        return $this->tipoPrecio;
    }


}

