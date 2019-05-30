<?php
    //지역의 개수와 num의 개수를 맞춰준다.
    require_once "vendor/autoload.php";
    header("Content-Type:text/html;charset=utf-8");
    $num = $_GET['num'];
    $city = $_GET['city'];
    $city2 = $_GET['city2'];
    $city3 = $_GET['city3'];
    $city4 = $_GET['city4'];
    $collection = (new MongoDB\Client)->WeatherProjects->Weather;
    
    $sendCity = $city;
    
    include "CityCode.php"; //지역 코드 가져오기
    
    $code = constant($city);
    $code2 = constant($city2);
    $code3 = constant($city3);
    $code4 = constant($city4);

    //지역별 데이터 평균
    function CityAvg($collection){
        $rst = array();
        $temp = array();
        $cursor = $collection->aggregate([
            ['$group' => [
                '_id' => ['city'=> '$City'],
                'CityAvgTemp' => [
                    '$avg'=> '$AvgTemp'
                ],
                'CityAvgLowTemp' =>[
                    '$avg' => '$LowTemp'
                ],
                'CityAvgHighTemp' =>[
                    '$avg' => '$HighTemp'
                ],
                'CityAvgMaxMomentWindy' =>[
                    '$avg' => '$MaxMomentWindy'
                ],
                'CityAvgWindy' =>[
                    '$avg' => '$AvgWindy'
                ]
            ]]
        ]);
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'City' => $temp[_id][city],
                'CityAvgTemp' => $temp[CityAvgTemp],
                'CityAvgLowTemp' => $temp[CityAvgLowTemp],
                'CityAvgHighTemp' => $temp[CityAvgHighTemp],
                'CityAvgMaxMomentWindy' => $temp[CityAvgMaxMomentWindy],
                'CityAvgWindy' => $temp[CityAvgWindy]
            ));
        };
        // for ($row =0; $row < sizeof($rst); $row++){
        //     echo "지역 : ".$rst[$row]['City']." 평균 : ".$rst[$row]['CityAvgTemp']."&nbsp <br>";
        // }
        return $rst;
    }
    //년도별 데이터 평균
    function YearAvg($collection){
        $rst = array();
        $temp = array();
        $cursor = $collection->aggregate([
            ['$group' => [
                '_id' => ['year'=> '$Year'],
                'YearAvgTemp' => [
                    '$avg'=> '$AvgTemp'
                ],
                'YearAvgLowTemp' =>[
                    '$avg' => '$LowTemp'
                ],
                'YearAvgHighTemp' =>[
                    '$avg' => '$HighTemp'
                ],
                'YearAvgMaxMomentWindy' =>[
                    '$avg' => '$MaxMomentWindy'
                ],
                'YearAvgWindy' =>[
                    '$avg' => '$AvgWindy'
                ]
            ]],
            ['$sort'=> ['_id'=> 1]]
        ]);
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'Year' => $temp[_id][year],
                'YearAvgTemp' => $temp[YearAvgTemp],
                'YearAvgLowTemp' => $temp[YearAvgLowTemp],
                'YearAvgHighTemp' => $temp[YearAvgHighTemp],
                'YearAvgMaxMomentWindy' => $temp[YearAvgMaxMomentWindy],
                'YearAvgWindy' => $temp[YearAvgWindy]
            ));
        };
        // for ($row =0; $row < sizeof($rst); $row++){
        //     echo "년 평균 : ".$rst[$row]['YearAvgTemp']."&nbsp <br>";
        // }
        return $rst;
    }
    //지역 년도 데이터 평균
    function CityYearAvg($collection, $code){
        $rst = array();
        $temp = array();
        $num = 1;
        $cursor = $collection->aggregate([
            ['$match' => ["City"=>$code]],
            ['$group' => [
                '_id' => ['year'=> '$Year'],
                'CityYearAvgTemp' => [
                    '$avg'=> '$AvgTemp'
                ],
                'CityYearAvgLowTemp' =>[
                    '$avg' => '$LowTemp'
                ],
                'CityYearAvgHighTemp' =>[
                    '$avg' => '$HighTemp'
                ],
                'CityYearAvgMaxMomentWindy' =>[
                    '$avg' => '$MaxMomentWindy'
                ],
                'CityYearAvgWindy' =>[
                    '$avg' => '$AvgWindy'
                ]
            ]],
            ['$sort'=> ['_id'=> 1]]
        ]);
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'Year' => $temp[_id][year],
                'CityYearAvgTemp' => $temp[CityYearAvgTemp],
                'CityYearAvgLowTemp' => $temp[CityYearAvgLowTemp],
                'CityYearAvgHighTemp' =>$temp[CityYearAvgHighTemp],
                'CityYearAvgMaxMomentWindy' => $temp[CityYearAvgMaxMomentWindy],
                'CityYearAvgWindy' => $temp[CityYearAvgWindy],
            ));
            $num++;
        };
        //출력 형식
        //   for ($row =0; $row < sizeof($rst); $row++){
        //         echo "월 평균 온도 : ".$rst[$row]['CityYearAvgLowTemp']."&nbsp <br>";
        //   }
        return $rst;
    }
    //월별 평균 데이터
    function MonthAvg($collection){
        $rst = array();
        $temp = array();
        $cursor = $collection->aggregate([
            ['$group' => [
                '_id' => ['year'=> '$Year', 'month'=> '$Month'],
                'MonthAvgTemp' => [
                    '$avg'=> '$AvgTemp'
                ],
                'MonthAvgLowTemp' =>[
                    '$avg' => '$LowTemp'
                ],
                'MonthAvgHighTemp' =>[
                    '$avg' => '$HighTemp'
                ],
                'MonthAvgMaxMomentWindy' =>[
                    '$avg' => '$MaxMomentWindy'
                ],
                'MonthAvgWindy' =>[
                    '$avg' => '$AvgWindy'
                ]
            ]],
            ['$sort'=> ['_id'=> 1]]
        ]);
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'Year' => $temp[_id][year],
                'Month' => $temp[_id][month],
                'MonthAvgTemp' => round($temp[MonthAvgTemp])
            ));
        };
        //출력 형식
        // for ($row =0; $row < sizeof($rst); $row++){
        //     echo "월 평균 온도 : ".$rst[$row]['Month']."&nbsp <br>";
        // }
        return $rst;
    }
    //토탈 데이터
    function TotalData($collection){
        $cursor = $collection->count();
        //echo $cursor;
        return $cursor;
    }
    //년도별 평균 일일 강수량
    function YearDayWaterData($collection){
        $rst = array();
        $temp = array();
        $cursor = $collection->aggregate([
            ['$group' => [
                '_id' => [
                    'year'=> '$Year'],
                'avg' => [
                    '$avg'=> '$DayWater']
                ]
            ],
            ['$sort'=> ['_id'=> 1]]
        ]);
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'Year' => $temp[_id][year],
                'YearAvgDayWater' => round($temp[avg],1)
            ));
        };
        return $rst;

        //출력 형식
        //for ($row =0; $row < sizeof($rst); $row++){
        //    echo "년 평균 강수량 : ".$rst[$row]['YearAvgDayWater']."&nbsp <br>";
        //}
    }
    //지역별 일일 강수량 평균
    function CityDayWaterData($collection, $code){
        $rst = array();
        $temp = array();
        $cursor = $collection->aggregate([
            [
                '$match'=> [
                    'City'=> $code
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'city'=> '$City',
                        'year'=> '$Year'
                    ],
                    'avg' => [
                        '$avg'=> '$DayWater'
                    ]
                ]
            ],
            [
                '$sort'=> ['_id'=> 1]
            ]
        ]);
        
        foreach ($cursor as $temp){
            array_push($rst,
            array(
                'Year' => $temp[_id][year],
                'CityAvgDayWater' => round($temp[avg],1)
            ));
        };
        //출력 형식
        //for ($row =0; $row < sizeof($rst); $row++){
        //    echo "년 평균 강수량 : ".$rst[$row]['CityAvgDayWater']."&nbsp <br>";
       // }
        return $rst;
    }
    //지역별 강수량과 전체 지역 강수량 데이터 넣기
    function CityYearAvgWaterData($collection, $city, $code){
        $totalWaterData = YearDayWaterData($collection);
        $CityWaterData = CityDayWaterData($collection, $code);
        $data = array();
        $temp = array();
        $temp2 = array();
        
        for($a=0; $a<18; $a++){
            array_push($data,
            array(
                'Year' => $totalWaterData[$a][Year],
                'Values' => array(
                    array(
                        'value' => $totalWaterData[$a][YearAvgDayWater],
                        'City' => 'Total'
                    ),
                    array(
                        'value' => $CityWaterData[$a][CityAvgDayWater],
                        'City' => "$city"
                    )
                )
            )
            );
        }
        return $data;
    }
    function TotalAvgWater($collection){
        $data = array();
        $temp = array();
        $cursor = $collection->aggregate([
            [
                '$group'=>[
                    '_id'=> 'null',
                    'DayWater'=>[
                        '$avg'=> '$TenMinMaxWater'
                    ],
                    'TenMinMaxWater'=>[
                        '$avg'=> '$TenMinMaxWater'
                    ],
                    'OneHrMaxWater'=>[
                        '$avg'=>'$OneHrMaxWater'
                    ],
                    'MinHumi'=>[
                        '$avg'=>'$MinHumi'
                    ],
                    'AvgHumi'=>[
                        '$avg'=>'$AvgHumi'
                    ]
                ]
            ]
        ]);
        foreach ($cursor as $temp){
            array_push($data,
            array(
                'DayWater'=>round($temp[DayWater], 1),
                'TenMinMaxWater'=>round($temp[TenMinMaxWater],1),
                'OneHrMaxWater'=>round($temp[OneHrMaxWater],1),
                'MinHumi'=>round($temp[MinHumi],1),
                'AvgHumi'=>round($temp[AvgHumi],1)
            ));
        };
        return $data;
    }
    
    //CityYearAvg($collection, $code);
    //MonthAvgTemp($collection);
    //YearAvg($collection);
    //CityAvg($collection);
    //TotalData($collection);
    //YearDayWaterData($collection);
?>