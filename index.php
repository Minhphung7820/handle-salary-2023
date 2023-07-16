<?php
class Salary
{
    const CTS_PERSONAL_INCOME = 'personal_income';
    const CTS_PERSONAL_INCOME_TAX = 'personal_income_tax';
    const CTS_ENTERPRISE_PAY = 'enterprise_pay';
    const CTS_OVERVIEW = 'overview';
    protected $inputSalary = 40000000;
    protected $salary;
    protected $typeSalary = 'NET';
    protected $giamtrgiacanh = 11000000;
    protected $number_of_dependents = 1;
    protected $totalNumberOfDepen;
    protected $ttncn = 0;
    protected $gross;
    protected $net;
    protected $conditionBH = 29000000;
    protected $baseSalary = 1490000;
    // 
    protected $bhxh;
    protected $bhyt;
    protected $bhtn;
    protected $totalBH;
    // 
    protected $bhxh_cpn;
    protected $bhyt_cpn;
    protected $bhtn_cpn;
    protected $bhbnn_cpn;
    protected $totalBH_cpn;
    // 
    protected $tntt;
    protected $tnct;
    // người phụ thuộc
    protected $result = [
        'overview' => [
            'salary_gross' => null,
            'salary_net' => null,
            'insurance' => null,
            'salary_gross' => null,
            'personal_income_tax' => null
        ],
        'personal_income' => [
            'salary_gross' => null,
            'social_insurance' => null,
            'health_insurance' => null,
            'unemployment_insurance' => null,
            'income_before_tax' => null,
            'income_taxes' => null,
            'salary_net' => null,
        ],
        'personal_income_tax' => [
            'TH1' => null,
            'TH2' => null,
            'TH3' => null,
            'TH4' => null,
            'TH5' => null,
            'TH6' => null,
            'TH7' => null,
        ],
        'enterprise_pay' => [
            'salary_gross' => null,
            'social_insurance' => null,
            'health_insurance' => null,
            'unemployment_insurance' => null,
            'occupational_disease_insurance' => null,
            'total' => null,
        ],
    ];
    protected $ortherSalary = null;
    protected $convertedIncome;
    public function __construct()
    {
        $this->totalNumberOfDepen = $this->number_of_dependents *  4400000;
        switch ($this->typeSalary) {
            case 'GROSS':
                $this->gross = $this->inputSalary;
                $this->result[self::CTS_PERSONAL_INCOME]['salary_gross'] = $this->gross;
                if ($this->ortherSalary !== null) {
                    $this->bhxh =  ($this->ortherSalary > 29000000) ? 1490000 * 20 * 0.08 : $this->ortherSalary * 0.08;
                    $this->bhyt = ($this->ortherSalary > 29000000) ? 1490000 * 20 * 0.015 : $this->ortherSalary * 0.015;
                    $this->bhtn =  ($this->ortherSalary > 29000000) ? 9360000 * 0.1 : $this->ortherSalary * 0.01;
                } else {
                    $this->bhxh =  ($this->gross > 29000000) ? 1490000 * 20 * 0.08 : $this->gross * 0.08;
                    $this->bhyt = ($this->gross > 29000000) ? 1490000 * 20 * 0.015 : $this->gross * 0.015;
                    $this->bhtn =  ($this->gross > 29000000) ? 9360000 * 0.1 : $this->gross * 0.01;
                }
                $this->result[self::CTS_PERSONAL_INCOME]['social_insurance'] = $this->bhxh;
                $this->result[self::CTS_PERSONAL_INCOME]['health_insurance'] = $this->bhyt;
                $this->result[self::CTS_PERSONAL_INCOME]['unemployment_insurance'] = $this->bhtn;

                $this->totalBH = ($this->bhtn + $this->bhxh + $this->bhyt);
                $this->tntt = ($this->gross - $this->totalBH);
                $this->result[self::CTS_PERSONAL_INCOME]['income_before_tax'] =  $this->tntt;

                if ($this->tntt < $this->giamtrgiacanh) {
                    $this->net = $this->tntt;
                    $this->result[self::CTS_PERSONAL_INCOME]['salary_net'] =  $this->net;
                } else {
                    $this->tnct = $this->tntt -  $this->giamtrgiacanh -  $this->totalNumberOfDepen;
                    $this->result[self::CTS_PERSONAL_INCOME]['income_taxes'] = $this->tnct;
                    if ($this->tnct > 0) {
                        if ($this->tnct < 5000000) {
                            $thuesuat = $this->tnct * 0.05;
                        } else {
                            $thuesuat = 5000000 * 0.05;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH1'] = $thuesuat;
                    }
                    if ($this->tnct > 5000000) {
                        if ($this->tnct > 5000000 && $this->tnct <= 10000000) {
                            $thuesuat = ($this->tnct - 5000000) * 0.10;
                        } else {
                            $thuesuat = (10000000 - 5000000) * 0.10;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH2'] = $thuesuat;
                    }
                    if ($this->tnct > 10000000) {
                        if ($this->tnct > 10000000 && $this->tnct <= 18000000) {
                            $thuesuat = ($this->tnct - 10000000) * 0.15;
                        } else {
                            $thuesuat = (18000000 - 10000000) * 0.15;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH3'] = $thuesuat;
                    }
                    if ($this->tnct  > 18000000) {
                        if ($this->tnct  > 18000000 && $this->tnct  <= 32000000) {
                            $thuesuat = ($this->tnct  - 18000000) * 0.2;
                        } else {
                            $thuesuat = (32000000 - 18000000) * 0.2;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH4'] = $thuesuat;
                    }
                    if ($this->tnct > 32000000) {
                        if ($this->tnct > 32000000 && $this->tnct <= 52000000) {
                            $thuesuat = ($this->tnct - 32000000) * 0.25;
                        } else {
                            $thuesuat = (52000000 - 32000000) * 0.25;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH5'] = $thuesuat;
                    }

                    if ($this->tnct > 52000000) {
                        if ($this->tnct > 52000000 && $this->tnct <= 80000000) {
                            $thuesuat = ($this->tnct - 52000000) * 0.3;
                        } else {
                            $thuesuat = (80000000 - 52000000) * 0.3;
                        }
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH6'] = $thuesuat;
                    }
                    if ($this->tnct > 80000000) {
                        $thuesuat = ($this->tnct - 80000000) * 0.35;
                        $this->ttncn += $thuesuat;
                        $this->result[self::CTS_PERSONAL_INCOME_TAX]['TH7'] = $thuesuat;
                    }
                    $this->ttncn =    $this->ttncn;
                    $this->net = $this->tntt - $this->ttncn;
                    $this->result[self::CTS_PERSONAL_INCOME]['salary_net'] =  $this->net;
                    $this->bhxh_cpn = ($this->gross >= 29800000) ? 5066000 : $this->gross * 0.17;
                    $this->bhbnn_cpn  = ($this->gross >= 29800000) ? 149000 : $this->gross * 0.005;                                          // bảo hiểm tai nạn
                    $this->bhyt_cpn = ($this->gross > 29800000) ? 894000 : $this->gross * 0.03;
                    $this->bhtn_cpn  = ($this->gross > 94000000) ? 936000 : $this->gross * 0.01;
                    $this->totalBH_cpn = ($this->bhxh_cpn +  $this->bhbnn_cpn +  $this->bhyt_cpn +  $this->bhtn_cpn);
                    $this->result[self::CTS_ENTERPRISE_PAY]['salary_gross'] = $this->gross;
                    $this->result[self::CTS_ENTERPRISE_PAY]['social_insurance'] = $this->bhxh_cpn;
                    $this->result[self::CTS_ENTERPRISE_PAY]['health_insurance'] = $this->bhyt_cpn;
                    $this->result[self::CTS_ENTERPRISE_PAY]['unemployment_insurance'] =  $this->bhtn_cpn;
                    $this->result[self::CTS_ENTERPRISE_PAY]['occupational_disease_insurance'] =  $this->bhbnn_cpn;
                    $this->result[self::CTS_ENTERPRISE_PAY]['total'] =  ($this->gross +  $this->totalBH_cpn);
                    $this->result[self::CTS_OVERVIEW]['salary_gross'] =  $this->gross;
                    $this->result[self::CTS_OVERVIEW]['salary_net'] =   $this->net;
                    $this->result[self::CTS_OVERVIEW]['insurance'] =   $this->totalBH;
                    $this->result[self::CTS_OVERVIEW]['personal_income_tax'] =   $this->ttncn;
                }
                break;
            case 'NET':
                $this->net = $this->inputSalary;
                $this->result[self::CTS_PERSONAL_INCOME]['salary_net'] = $this->net;
                if ($this->net < ($this->giamtrgiacanh + ($this->number_of_dependents *  4400000))) {
                    $this->gross = ceil($this->net / 0.895);
                    $this->tntt =   $this->inputSalary;
                } else {
                    $this->convertedIncome = $this->net - ($this->giamtrgiacanh + ($this->number_of_dependents *  4400000));
                    $this->tnct = $this->incomeTaxes($this->convertedIncome);

                    $this->result[self::CTS_PERSONAL_INCOME]['income_taxes'] = $this->tnct;
                    $this->tntt = ($this->giamtrgiacanh + ($this->number_of_dependents *  4400000) + $this->tnct);
                    if ($this->net > 2900000) {
                        $this->gross = ceil($this->tntt / 0.91);
                    } else {
                        $this->gross = ceil($this->tntt / 0.895);
                    }
                }
                $this->result[self::CTS_PERSONAL_INCOME]['income_before_tax'] =  $this->tntt;
                $this->result[self::CTS_PERSONAL_INCOME]['salary_gross'] =  $this->gross;
                if ($this->ortherSalary !== null) {
                    $this->bhxh =  ($this->ortherSalary > 29000000) ? 1490000 * 20 * 0.08 : $this->ortherSalary * 0.08;
                    $this->bhyt = ($this->ortherSalary > 29000000) ? 1490000 * 20 * 0.015 : $this->ortherSalary * 0.015;
                    $this->bhtn =  ($this->ortherSalary > 29000000) ? 9360000 * 0.1 : $this->ortherSalary * 0.01;
                } else {
                    $this->bhxh =  ($this->gross > 29000000) ? 1490000 * 20 * 0.08 : $this->gross * 0.08;
                    $this->bhyt = ($this->gross > 29000000) ? 1490000 * 20 * 0.015 : $this->gross * 0.015;
                    $this->bhtn =  ($this->gross > 29000000) ? 9360000 * 0.1 : $this->gross * 0.01;
                }
                $this->result[self::CTS_PERSONAL_INCOME]['social_insurance'] = $this->bhxh;
                $this->result[self::CTS_PERSONAL_INCOME]['health_insurance'] = $this->bhyt;
                $this->result[self::CTS_PERSONAL_INCOME]['unemployment_insurance'] = $this->bhtn;
                break;
        }
    }

    public function incomeTaxes($value)
    {
        $rs = 0;
        if ($value < 4750000) {
            $rs = $value / 0.95;
        } elseif ($value >= 4750000 && $value < 9250000) {
            $rs = ceil(($value - 250000) / 0.9);
        } elseif ($value >= 9250000 && $value < 16050000) {
            $rs = ceil(($value - 750000) / 0.85);
        } elseif ($value >= 16050000 && $value < 27250000) {
            $rs = ceil(($value - 1650000) / 0.8);
        } elseif ($value >= 27250000 && $value < 42250000) {
            $rs = ceil(($value - 3250000) / 0.75);
        } elseif ($value >= 42250000 && $value < 61850000) {
            $rs = ceil(($value - 5850000) / 0.7);
        } elseif ($value > 61850000) {
            $rs = ceil(($value - 9850000) / 0.65);
        }
        return $rs;
    }

    public function responseSalaryInformation()
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return array_map('number_format', $item);
            }
            return number_format($item, ".", ",", "");
        }, $this->result);
    }
}

$salary = new Salary();

echo "<pre>";
print_r($salary->responseSalaryInformation());
echo "</pre>";
