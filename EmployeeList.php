<?php

require("Employee.php");

class EmployeeList
{
    private $list;

    public function EmployeeList()
    {
        $list = array();
    }

    public function addEmployee($e)
    {
        $this->list[] = $e;
    }

    public function addEmployeeFromFile($file)
    {
        while($lines = fgets($file))
        {
            $line = array_map('trim', explode(',', $lines));
            $d = $line[1] . "-" . $line[2] . "-" . $line[3];
            if(Employee::verifyBirthDate($d))
            {
                $p = new Employee($line[0], $d);
                if($p != null)
                {
                    $this->addEmployee($p);
                }
            }
        }
    }

    public function printEmployee()
    {
        foreach($this->list as $e)
        {
            print "\n" . $e->toString() . "\n";
        }
    }

    private function sortByCakeRecDate()
    {
        usort($this->list, function($a, $b){return strtotime($a->getCakeReceiveDate()->format('Y-m-d')) - strtotime($b->getCakeReceiveDate()->format('Y-m-d'));});
    }

    private function refineListOrder($index)
    {
        if($index < sizeof($this->list)-1)
        {
            $ddif = date_diff($this->list[$index]->getCakeReceiveDate(), $this->list[$index+1]->getCakeReceiveDate())->format("%R%a");
            if($ddif <= 1)
                $this->list[$index+1]->setCakeReceiveDate(date('Y-m-d' ,strtotime($this->list[$index+1]->getCakeReceiveDate()->format('Y-m-d') . "+ " . (1-$ddif) . " days")));
        }

        if($index < sizeof($this->list)-2)
        {
            $ddif = date_diff($this->list[$index+1]->getCakeReceiveDate(), $this->list[$index+2]->getCakeReceiveDate())->format("%R%a");
            if($ddif < 0)
                $this->list[$index+2]->setCakeReceiveDate(date('Y-m-d' ,strtotime($this->list[$index+2]->getCakeReceiveDate()->format('Y-m-d') . "+ " . (-1-$ddif) . " days")));
        }
    }

    public function getResult()
    {
        // print "\n ============ Initial Cake Delivery Date =================== \n";
        // $this->printEmployee();
        $out = '';
        $this->sortByCakeRecDate();

        $i=0;
        for (; $i<sizeof($this->list)-1; $i++)
        {
            $dif_day = date_diff($this->list[$i]->getCakeReceiveDate(), $this->list[$i+1]->getCakeReceiveDate())->format("%R%a");

            if($dif_day > 1)
            {
               $out .= "\n" . $this->list[$i]->getCakeReceiveDate()->format('Y-m-d') . ", " . 1 . ", " . "yok" . ", " . $this->list[$i]->getName();
            }
            else if($dif_day == 0)
            {
                $out .= "\n" . $this->list[$i]->getCakeReceiveDate()->format('Y-m-d') . ", " . "yok" . ", " . 1 . ", " . $this->list[$i]->getName() . " - " . $this->list[$i+1]->getName();
                $i++;
                $this->refineListOrder($i);
            }
            else if($dif_day == 1)
            {
                $this->list[$i]->setCakeReceiveDate(date('Y-m-d' ,strtotime($this->list[$i]->getCakeReceiveDate()->format('Y-m-d') . "+ 1 days")));
                $this->list[$i+1]->setCakeReceiveDate(date('Y-m-d' ,strtotime($this->list[$i+1]->getCakeReceiveDate()->format('Y-m-d') . "+ 0 days")));

                $out .= "\n" . $this->list[$i]->getCakeReceiveDate()->format('Y-m-d') . ", " . "yok" . ", " . 1 . ", " . $this->list[$i]->getName() . " - " . $this->list[$i+1]->getName();
                $i++;
                $this->refineListOrder($i);
            }
            else
            {
                $this->refineListOrder($i);
            }
        }   

        if($i == sizeof($this->list)-1)
            $out .= "\n" . $this->list[$i]->getCakeReceiveDate()->format('Y-m-d') . ", " . 1 . ", " . "yok" . ", " . $this->list[$i]->getName();


        // print "\n ============ Final Cake Delivery Date =================== \n";
        // $this->printEmployee();

        return $out;    
    }   
}


?>