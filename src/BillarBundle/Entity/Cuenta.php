<?php

namespace BillarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cuenta
 *
 * @ORM\Table(name="cuenta",
 * indexes={
 * @ORM\Index(name="mesa_idx", columns={"id_mesa"}),
 * @ORM\Index(name="cliente_idx", columns={"id_cliente"}),
 * @ORM\Index(name="usuario_idx", columns={"id_usuario"})
 * }
 *)
 * @ORM\Entity(repositoryClass="BillarBundle\Repository\CuentaRepository")
 */
class Cuenta
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
     * @ORM\ManyToOne(targetEntity="Mesa", inversedBy="cuentas")
     * @ORM\JoinColumn(name="id_mesa", referencedColumnName="id", nullable=false)
     */    
    protected $mesa;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="cuentas")
     * @ORM\JoinColumn(name="id_cliente", referencedColumnName="id", nullable=false)
     */    
    protected $cliente;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="cuentas")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id", nullable=false)
     */    
    protected $usuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cierre", type="datetime", nullable=true)
     */
    private $fechaCierre;

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
     * Set mesa
     *
     * @param integer $mesa
     *
     * @return Cuenta
     */
    public function setMesa($mesa)
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get mesa
     *
     * @return int
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * Set cliente
     *
     * @param integer $cliente
     *
     * @return Cuenta
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return int
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set usuario
     *
     * @param integer $usuario
     *
     * @return Cuenta
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return int
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Cuenta
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

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
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Cuenta
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     *
     * @return Cuenta
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Cuenta
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
     * @ORM\OneToMany(targetEntity="MovCuenta", mappedBy="idCuenta")
     */
    protected $movimientos;

    /**
     * @ORM\OneToMany(targetEntity="CuentaDetalle", mappedBy="idCuenta")
     */
    protected $detalles;

    public function __construct()
    {
        $this->movimientos  = new ArrayCollection();
        $this->detalles     = new ArrayCollection();
    }      

}

