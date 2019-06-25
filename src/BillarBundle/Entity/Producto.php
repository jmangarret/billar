<?php

namespace BillarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity(repositoryClass="BillarBundle\Repository\ProductoRepository")
 */
class Producto
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_producto", type="string", length=100)
     */
    private $tipoProducto;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_compra", type="float", nullable=true)
     */
    private $valorCompra;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_venta", type="float")
     */
    private $valorVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=100, nullable=true)
     */
    private $foto;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Producto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipoProducto
     *
     * @param string $tipoProducto
     *
     * @return Producto
     */
    public function setTipoProducto($tipoProducto)
    {
        $this->tipoProducto = $tipoProducto;

        return $this;
    }

    /**
     * Get tipoProducto
     *
     * @return string
     */
    public function getTipoProducto()
    {
        return $this->tipoProducto;
    }

    /**
     * Set valorCompra
     *
     * @param float $valorCompra
     *
     * @return Producto
     */
    public function setValorCompra($valorCompra)
    {
        $this->valorCompra = $valorCompra;

        return $this;
    }

    /**
     * Get valorCompra
     *
     * @return float
     */
    public function getValorCompra()
    {
        return $this->valorCompra;
    }

    /**
     * Set valorVenta
     *
     * @param float $valorVenta
     *
     * @return Producto
     */
    public function setValorVenta($valorVenta)
    {
        $this->valorVenta = $valorVenta;

        return $this;
    }

    /**
     * Get valorVenta
     *
     * @return float
     */
    public function getValorVenta()
    {
        return $this->valorVenta;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Producto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Producto
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\OneToMany(targetEntity="Promocion", mappedBy="idProducto")
     */
    protected $promociones;

    /**
     * @ORM\OneToMany(targetEntity="CuentaDetalle", mappedBy="idProducto")
     */
    protected $detalles;

    public function __construct()
    {
        $this->promociones  = new ArrayCollection();
        $this->detalles     = new ArrayCollection();    
    }    
}

