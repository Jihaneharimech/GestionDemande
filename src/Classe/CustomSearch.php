<?php
namespace App\Classe;

use DateTime;

class CustomSearch{

    /**
     * @var string
     * Pour representer ( N°demande, NomClint)
     */
    public $string="";

     /**
     * @var ville[]
     */
    public $ville= [];

     /**
     * @var typeAppareil[]
     */
    public $typeAppareil= [];

     /**
     * @var statut[]
     */
    public $statut= [];

    /**
     * @var datefrom
     */
    public ?DateTime $datefrom = null;

     /**
     * @var dateto
     */
    public ?DateTime $dateto = null;

    /**
     * @var selectDate[]
     */
    public $selectDate=[];

}
