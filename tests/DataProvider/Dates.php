<?php

namespace Tests\DataProvider;

trait Dates
{
    /**
     * @property array|string[]
     */
    private array $dateFuture;

    /**
     * @return array|string[]
     */
    public function getBirthDate(): array
    {
        $day = date("d");
        $month = date("m");
        $today = date("Y-m-d");
        return [
            "1990-10-12",
            "1991-12-03",
            "1984-01-22",
            "1989-03-25",
            "1967-01-20",
            "1934-07-18",
            "1977-11-14",
            "1969-04-29",
            "196?-??-18",
            "1990-$month-$day",
            "1996-$month-$day",
            "1980-$month-$day",
            "1986-$month-$day"
        ];
    }

    /**
     * @return array|string[]
     */
    public function getDeathDate(): array
    {
        $day = date("d");
        $month = date("m");
        $today = date("Y-m-d");
        return [
            "2010-09-13",
            "2012-11-15",
            "2018-07-04",
            "2019-05-19",
            "2020-10-24",
            "2017-02-07",
            "2020-07-03",
            "2019-09-13",
            "2019-??-13",
            "2019-$month-$day",
            "2021-$month-$day",
            "2020-$month-$day",
            "2014-$month-$day",
            "2013-$month-$day",
            $today,
            $today,
            $today
        ];
    }

    /**
     * @return array|string[]
     */
    public function getDateBetween(): array
    {
        return [
            "2009-07-13",
            "2008-12-13",
            "2007-09-10",
            "2006-06-12",
            "2002-??-??",
            "200?-10-??",
            "2008-??-11",
            "????-10-01",
            "????-03-??",
            "??10-03-??",
            "20??-09-1?"
        ];
    }

    /**
     * @return array|string[]
     */
    public function getDatePatternWrong(): array
    {
        return [
            "20090713",
            "????-12-?",
            "2000-09",
            "2000",
            "?",
            "90-10-01",
            "2008-13-11",
            "2008-12-34"
        ];
    }

    /**
     * @return array|string[]
     */
    public function getDateFuture(): array
    {
        if (empty($this->dateFuture)) {
            $this->dateFuture = [
                (new \DateTime())->add(new \DateInterval("P1Y"))->format("Y-m-d"),
                (new \DateTime())->add(new \DateInterval("P10Y1M"))->format("Y-m-d"),
                (new \DateTime())->add(new \DateInterval("P5Y2M5D"))->format("Y-m-d"),
                (new \DateTime())->add(new \DateInterval("P2Y11M14D"))->format("Y-m-d"),
                (new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m-d"),
                (new \DateTime())->add(new \DateInterval("P1D"))->format("Y-m-d"),
            ];
        }

        return $this->dateFuture;
    }
}
