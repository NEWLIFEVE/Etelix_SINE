 <?php

/**
 * @package reportes
 * @version 1.0
 */
class segurityRetainer extends Reportes 
{
    private $styleNumberRow ="style='border:1px solid silver;text-align:center;background:#83898F;color:white;'";
    private $styleBasic ="style='border:1px solid silver;text-align:center;'";
    private $styleCarrierHead ="style='border:1px solid silver;background:silver;text-align:center;color:white;'";
    private $styleAmountHead ="style='border:1px solid silver;background:#3466B4;text-align:center;color:white;'";
    private $styleSegurRetaHead ="style='border:1px solid silver;background:#E99241;text-align:center;color:white;'";
    private $styleDocNumberHead ="style='border:1px solid silver;background:#248CB4;text-align:center;color:white;'";
    private $styleIssueDateHead ="style='border:1px solid silver;background:#C37881;text-align:center;color:white;'";
    private $styleNull ="style='border:1px solid white;'";
    private $totalAmount =0;

    public function report($date)
    {
        $body=NULL;
        $model=$this->getData($date);
        $body.="<h1>REDS </h1>al {$date}
                <table style='width: 100%;'>
                    <tr>
                        <td ".$this->styleNumberRow.">N°</td>
                        <td ".$this->styleCarrierHead."> Carrier </td>
                        <td ".$this->styleSegurRetaHead."> Segurity Retainer </td>
                        <td ".$this->styleDocNumberHead."> Doc Number  </td>
                        <td ".$this->styleIssueDateHead."> Issue Date</td>
                        <td ".$this->styleAmountHead."> Amount </td>
                        <td ".$this->styleNumberRow.">N°</td>
                    </tr>";
        foreach ($model as $key => $value) 
        {  
            $pos=$key+1;
            $this->totalAmount+=$value->amount;
            $body.="<tr>
                        <td ".$this->styleNumberRow.">{$pos}</td>
                        <td ".$this->styleBasic."> ".$value->carrier." </td>
                        <td ".$this->styleBasic."> ".$value->segurity_retainer." </td>
                        <td ".$this->styleBasic."> ".$value->doc_number."  </td>
                        <td ".$this->styleBasic."> ".$value->issue_date." </td>
                        <td ".$this->styleBasic."> ".Yii::app()->format->format_decimal($value->amount).$value->currency." </td>
                        <td ".$this->styleNumberRow.">{$pos}</td>
                    </tr>";
        }
        $body.="<tr>
                    <td ".$this->styleNull." colspan='5'></td>
                    <td ".$this->styleAmountHead."> ".Yii::app()->format->format_decimal($this->totalAmount)." </td>
                    <td ".$this->styleNull."></td>
                </tr>";
        $body.="</table>";
        return $body;
    }
    private function getData($date)
    {
        $sql="SELECT c.name AS carrier, tac.name AS segurity_retainer, ac.doc_number AS doc_number, ac.issue_date AS issue_date, ac.amount AS amount, cu.name AS currency
              FROM carrier c,carrier_groups cg, accounting_document ac, type_accounting_document tac, currency cu
              WHERE c.id in(SELECT id FROM carrier where id_carrier_groups=cg.id)
                AND c.id=ac.id_carrier
                AND id_type_accounting_document IN(16,17)
                AND id_type_accounting_document=tac.id
                AND ac.id_currency=cu.id
                AND ac.issue_date<='{$date}'
              ORDER BY tac.id";
        return AccountingDocument::model()->findAllBySql($sql);
    }
}
?>