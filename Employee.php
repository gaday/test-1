<?php

class Employee 
{
    private $name;
    private $bdate;
    private $restiricted_days = ['x-10-29', 'x-1-1', 'x-11-24'];
    private $cake_receive_date;

    function Employee($name, $bdate)
    {
        $this->name = $name;
        $this->bdate = date_create($bdate);

        $this->setCakeReceiveDate($bdate);
    }

    public function setCakeReceiveDate($bdate)
    {
        $count=1;
        while(true)
        {
            if(date_create($bdate)->format("Y") > date("Y"))
                $receive = date(strtotime($bdate . " + " . $count . " days"));
            else
                $receive = date(date("Y") . "-m-d", strtotime($bdate . " + " . $count . " days"));
            // if missed this year, calculate for the next year
            if($receive < date("Y-m-d"))
                $receive = date(date("Y", strtotime(" + 1 year")) . "-m-d", strtotime($bdate . " + " . $count . " days"));

            if($this->ifPermitted($receive))
            {
                $this->cake_receive_date = date_create($receive);
                break;
            }
            $count++;
        }
    }

    public function getCakeReceiveDate()
    {
        return $this->cake_receive_date;
    }
    
    public function getBirthDate()
    {
        return  $this->bdate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return date_diff($this->getBirthDate(), date_create(date('Y-m-d')));
    }

    /**
     * @bdate : string yy-m-d
     */
    public static function verifyBirthDate($date)
    {
        $current_year = date('Y');
        $value = explode('-', $date);
        if(is_numeric($value[0]) && $value[0]>=1900 && $value[0]<=$current_year && $value[1]<=12 && $value[1]>=1 && $value[2]>=1 && $value[2]<=31)  
            return true;
        
        return false;
    }

    private function ifPermitted($date)
    {
        $d = date_create($date);

        // check if staurday or sunday
        if(strtolower($d->format('l')) == 'saturday' || strtolower($d->format('l')) == 'sunday')
            return false;

        foreach($this->restiricted_days as $r)
        {
            $each = explode('-', $r);

            if($each[0] == 'x' || $d->format('Y') == $each[0])
            {
                if($each[1] == 'x' || $d->format('m') ==  $each[1])
                {
                    if($each[2] == 'x' || $d->format('d') ==  $each[2])
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function toString()
    {
        return $this->getName() . "\t\t" . $this->getBirthDate()->format("Y-m-d") . "\t\t" . $this->getCakeReceiveDate()->format("Y-m-d");
    }
}


?>