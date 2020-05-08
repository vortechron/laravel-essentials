<?php

namespace Vortechron\Essentials\Core;

class IC
{
    protected $ic;

    protected $day;
    protected $month;
    protected $year;
    protected $state;
    protected $gender;

    public function __construct($ic)
    {
        $this->ic = $ic;

        $this->day = substr($ic, 4, 2);
        $this->month = substr($ic, 2, 2);
        $this->year = substr($ic, 0, 2);
        $this->state = substr($ic, 6, 2);
        $this->gender = substr($ic, 11, 1);
    }

    public function getIc()
    {
        return $this->ic;
    }

    public function getDateOfBirth()
    {
        $parsedYear = $this->parseYear();

        return "{$parsedYear}-{$this->month}-{$this->day}";
    }

    public function getGender()
    {
        if ($this->gender % 2 == 0) $gender = 'female';
        else $gender = 'male';

        return $gender;
    }

    public function getState()
    {
        $states = array(
            '01' => "Johor",
            '21' => "Johor",
            '22' => "Johor",
            '23' => "Johor",
            '24' => "Johor",

            '02' => "Kedah",
            '25' => "Kedah",
            '26' => "Kedah",
            '27' => "Kedah",

            '03' => "Kelantan",
            '28' => "Kelantan",
            '29' => "Kelantan",

            '04' => "Melaka",
            '30' => "Melaka",

            '05' => "Negeri Sembilan",
            '31' => "Negeri Sembilan",
            '59' => "Negeri Sembilan",

            '06' => "Pahang",
            '32' => "Pahang",
            '33' => "Pahang",

            '07' => "Pulau Pinang",
            '34' => "Pulau Pinang",
            '35' => "Pulau Pinang",

            '08' => "Perak",
            '36' => "Perak",
            '37' => "Perak",
            '38' => "Perak",
            '39' => "Perak",

            '09' => "Perlis",
            '40' => "Perlis",

            '10' => "Selangor",
            '41' => "Selangor",
            '42' => "Selangor",
            '43' => "Selangor",
            '44' => "Selangor",

            '11' => "Terengganu",
            '45' => "Terengganu",
            '46' => "Terengganu",

            '12' => "Sabah",
            '47' => "Sabah",
            '48' => "Sabah",
            '49' => "Sabah",

            '13' => "Sarawak",
            '50' => "Sarawak",
            '51' => "Sarawak",
            '52' => "Sarawak",
            '53' => "Sarawak",

            '14' => "Wilayah Persekutuan Kuala Lumpur",
            '54' => "Wilayah Persekutuan Kuala Lumpur",
            '55' => "Wilayah Persekutuan Kuala Lumpur",
            '56' => "Wilayah Persekutuan Kuala Lumpur",
            '57' => "Wilayah Persekutuan Kuala Lumpur",

            '15' => "Wilayah Persekutuan Labuan",
            '58' => "Wilayah Persekutuan Labuan",

            '16' => "Wilayah Persekutuan Putrajaya",

            '82' => "Lain-Lain"
        );

        return $states[$this->state];
    }

    public function parseYear()
    {
        if ($this->year >= 00 && $this->year <= 30) {
            $parsedYear = 2000 + $this->year;
        }

        if ($this->year >= 31 && $this->year <= 99) {
            $parsedYear = 1900 + $this->year;
        }

        return $parsedYear;
    }
}
