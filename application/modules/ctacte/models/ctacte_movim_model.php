<?php

class Ctacte_movim_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('ctacte_movim');
    }

    function getDetalle($cuenta, $estado)
    {
        $this->db->select('ctacte_movim.id');
        $this->db->select('DATE_FORMAT(ctacte_movim.fecha, "%d/%m/%Y") as fecha', FALSE);
        $this->db->select('CONCAT(ctacte_movim.puesto,"-",ctacte_movim.numero) AS firmado', FALSE);
        $this->db->select('ctacte_movim.importe');
        $this->db->select ( "CONCAT(fac_facencab.puesto,'-',fac_facencab.numero) AS comprobante", FALSE );
        $this->db->from($this->getTable());
        $this->db->join ( 'fac_facencab', 'ctacte_movim.idencab=fac_facencab.id', 'inner' );
        $this->db->where('id_cuenta', $cuenta);
        $this->db->where('ctacte_movim.estado', $estado);
        $this->db->order_by('fecha', 'ASC');
        return $this->db->get()->result();
    }

    function getTotalesAgrupados($estado = 'P')
    {
        $this->db->select('id_cuenta as id');
        $this->db->select('nombre as nombre');
        $this->db->select('COUNT(id_cuenta) AS cantidad', FALSE);
        $this->db->select("SUM(importe) as total", FALSE);
        $this->db->from($this->getTable());
        $this->db->join('cuenta', 'id_cuenta=cuenta.id', 'inner');
        $this->db->where('ctacte_movim.estado', $estado);
        $this->db->having('total > 0');
        $this->db->order_by('nombre');
        $this->db->group_by('id_cuenta');
        return $this->db->get()->result();
    }

    private function _buscoComprobante ( $id, $liq = FALSE ) {
        // buso el comprobante original
        $this->db->select('idencab as factura');
        $this->db->from($this->getTable());
        $this->db->where('id', $id);
        if (!$liq) {
            $this->db->where('id_liq IS NULL', '', FALSE);
        }
        $q = $this->db->get()->row();
        if (count($q) == 0) {
            return "no es posible cambiar de cuenta";
        } else {
            return $q->factura;
        }
    }

    /*
     * @method muestroComprobante
     * @param $id id del comprobante de cuenta corriente
     *
     */
    function getEncabezado($id, $liq = FALSE)
    {
        $idencab = $this->_buscoComprobante($id, $liq);
        $this->db->select('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha');
        $this->db->select ( 'fac_facencab.cuenta_id' );
        $this->db->select('cuenta.nombre as cuenta_nombre');
        $this->db->select ( 'CONCAT(cfg_tipcom.abreviatura," - ", fac_facencab.letra ) as tipocom', false );
        $this->db->select('CONCAT(puesto,"-",numero) as comprobante', false);
        $this->db->select ( 'IF(fac_facencab.estado=1,"Contado", "CtaCte") as condvta' );
        $this->db->select('importe as total');
        $this->db->from ( 'fac_facencab' );
        $this->db->join ( 'cuenta', 'cuenta.id=fac_facencab.cuenta_id', 'inner' );
        $this->db->join ( 'cfg_tipcom', 'cfg_tipcom.id=fac_facencab.tipcom_id', 'inner' );
        $this->db->where ( 'fac_facencab.id', $idencab );
        return $this->db->get()->row();
    }

    function getComprobante($id, $liq = FALSE)
    {
        $idencab = $this->_buscoComprobante($id, $liq);
        $this->db->select('codigobarra_articulo as Codigobarra');
        $this->db->select('cantidad_movim as Cantidad');
        $this->db->select ( 'fac_facmovim.id_articulo' );
        $this->db->select('descripcion_articulo as Nombre');
        $this->db->select('preciovta_movim as Precio');
        $this->db->select('cantidad_movim * preciovta_movim as Importe');
        $this->db->from ( 'fac_facmovim' );
        $this->db->join ( 'stk_articulos', 'stk_articulos.id_articulo=fac_facmovim.id_articulo', 'inner' );
        $this->db->where ( 'fac_facmovim.idencab', $idencab );
        return $this->db->get()->result();
    }

    /*
   * @method quitarDeLaCuenta
   * @param $id id del comprobante de cuenta corriente
   *
   */
    function quitarDeLaCuenta($id)
    {
        $idencab = $this->_buscoComprobante($id);
        $this->db->trans_begin();
        // cambio de estado el comprobante en la lista de movimientos de facturas
        $this->db->set('estado', 1);
        $this->db->where('id', $idencab);
        $this->db->update ( 'fac_facencab' );
        //$this->db->free_result();
        //borro de los movimientos de la cuenta corriente
        $this->db->where('id', $id);
        $this->db->where('id_liq IS NULL', '', FALSE);
        $this->db->delete('ctacte_movim');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        };
    }

    function getFecha($tipo = 'min', $estado = 'P', $cuenta = 0)
    {
        if ($tipo == 'min')
            $this->db->select_min('fecha');
        else
            $this->db->select_max('fecha');
        $this->db->from($this->getTable());
        $this->db->where('estado', $estado);
        $this->db->where('id_cuenta', $cuenta);
        return $this->db->get()->row()->fecha;
    }

    function Liquidar($idLiq, $datos)
    {
        foreach ($datos as $key => $valor) {
            $this->db->set('id_liq', $idLiq);
            $this->db->set('estado', 'L');
            $this->db->where('id', $valor);
            $this->db->update($this->getTable());
        }
        return true;
    }

    function getByLiq($idLiq)
    {
        $this->db->select('ctacte_movim.id');
        $this->db->select('DATE_FORMAT(ctacte_movim.fecha, "%d/%m/%Y") as fecha', FALSE);

        $this->db->select('CONCAT(ctacte_movim.puesto,"-",ctacte_movim.numero) AS firmado', FALSE);
        $this->db->select('ctacte_movim.importe');
        $this->db->select ( "CONCAT(fac_facencab.puesto,'-',fac_facencab.numero) AS comprobante", FALSE );
        $this->db->from($this->getTable());
        $this->db->join ( 'fac_facencab', 'ctacte_movim.idencab=fac_facencab.id', 'inner' );
        $this->db->where('id_liq', $idLiq);
        $this->db->order_by('fecha', 'ASC');
        return $this->db->get()->result();
    }

    function cobroFac($idLiq, $idRec)
    {
        $this->db->set('estado', 'C');
        $this->db->set('id_rec', $idRec);
        $this->db->where('id_liq', $idLiq);
        $this->db->update($this->getTable());
        return true;
    }

    function getLast($l = 20, $date = false)
    {
        $this->db->select('ctacte_movim.id');
        $this->db->select('date_format(fecha," %H:%i") as date', false);
        $this->db->select('CONCAT(id_cuenta," - ", cuenta.nombre) as cliente', false);
        $this->db->join('cuenta', 'cuenta.id=ctacte_movim.id_cuenta', 'inner');
        $this->db->select('importe');
        $this->db->from($this->getTable());
        if ($date) {
            $this->db->where('fecha >', $date);
        }
        $this->db->order_by('fecha', 'DESC');
        $this->db->limit($l);
        return $this->db->get()->result();
    }
}
