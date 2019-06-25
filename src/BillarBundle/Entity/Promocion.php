<?php

namespace BillarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Promocion
 *
 * @ORM\Table(name="promocion",
 * indexes={
 * @ORM\Index(name="producto_idx", columns={"id_producto"})
 * })
 * @ORM\Entity(repositoryClass="BillarBundle\Repository\PromocionRepository")
 */
class Promocion
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
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="promociones")
     * @ORM\JoinColumn(name="id_producto", referencedColumnName="id", nullable=false)
     */
    protected $producto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_desde", type="string", length=20)
     */
    private $horaDesde;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_hasta", type="string", length=20)
     */
    private $horaHasta;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_venta", type="float")
     */
    private $valorVenta;

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
     * Set producto
     *
     * @param integer $producto
     *
     * @return Promocion
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return int
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fecha
     *
     * @return Promocion
     */
    public function setFechaCreacion($fecha)
    {
        $this->fechaCreacion = $fecha;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set horaDesde
     *
     * @param string $horaDesde
     *
     * @return Promocion
     */
    public function setHoraDesde($hora)
    {
        $this->horaDesde = $hora;

        return $this;
    }

    /**
     * Get horaDesde
     *
     * @return string
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * Set horaHasta
     *
     * @param string $horaHasta
     *
     * @return Promocion
     */
    public function setHoraHasta($hora)
    {
        $this->horaHasta = $hora;

        return $this;
    }

    /**
     * Get horaHasta
     *
     * @return string
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * Set valorVenta
     *
     * @param float $valorVenta
     *
     * @return Promocion
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
     * Set status
     *
     * @param integer $status
     *
     * @return Promocion
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

}

