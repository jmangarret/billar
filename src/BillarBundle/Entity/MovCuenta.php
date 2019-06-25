<?php

namespace BillarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovCuenta
 *
 * @ORM\Table(name="mov_cuenta",
 * indexes={
 * @ORM\Index(name="cuenta_idx", columns={"id_cuenta"}),
 * }
 * )
 * @ORM\Entity(repositoryClass="BillarBundle\Repository\MovCuentaRepository")
 */
class MovCuenta
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
     * @ORM\ManyToOne(targetEntity="Cuenta", inversedBy="movimientos")
     * @ORM\JoinColumn(name="id_cuenta", referencedColumnName="id", nullable=false)
     */   
    protected $idCuenta;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_movimiento", type="string", length=100)
     */
    private $tipoMovimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hora", type="datetime")
     */
    private $fechaHora;

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
     * Set idCuenta
     *
     * @param integer $idCuenta
     *
     * @return MovCuenta
     */
    public function setIdCuenta($idCuenta)
    {
        $this->idCuenta = $idCuenta;

        return $this;
    }

    /**
     * Get idCuenta
     *
     * @return int
     */
    public function getIdCuenta()
    {
        return $this->idCuenta;
    }

    /**
     * Set tipoMovimiento
     *
     * @param string $tipoMovimiento
     *
     * @return MovCuenta
     */
    public function setTipoMovimiento($tipoMovimiento)
    {
        $this->tipoMovimiento = $tipoMovimiento;

        return $this;
    }

    /**
     * Get tipoMovimiento
     *
     * @return string
     */
    public function getTipoMovimiento()
    {
        return $this->tipoMovimiento;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return MovCuenta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     *
     * @return MovCuenta
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return MovCuenta
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

