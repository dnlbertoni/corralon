<?php

/**
 * Description of wizard_model
 *
 * @author dnl
 */
class Wizard_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->setTable('wizard');
    }

    function getWizardDias()
    {
        $this->db->select('MIN(time)          AS minimo', FALSE);
        $this->db->select('MAX(time)          AS maximo', FALSE);
        $this->db->select('COUNT(id_articulo) AS cantidad', FALSE);
        $this->db->select('TO_DAYS(MAX(time)) - TO_DAYS(MIN(time)) AS dias ', FALSE);
        $this->db->from($this->getTable());
        return $this->db->get()->row();
    }

    function getWizardSemanas()
    {
        $this->db->select('COUNT(id_articulo) AS cantidad', FALSE);
        $this->db->select('DATE_FORMAT(time, "%U") AS semana ', FALSE);
        $this->db->select('DATE_FORMAT(time, "%Y") AS ano ', FALSE);
        $this->db->from($this->getTable());
        $this->db->group_by('DATE_FORMAT(time, "%U")');
        $this->db->order_by('ano', 'asc');
        $this->db->order_by('semana', 'asc');
        return $this->db->get()->result();
    }
}
