<?php
// Author: Shyam Makwana
// Website : http://shyammakwana.me
$a = array(
    2 => array('name' => 'john', 'age' => 34),
    3 => array('name' => 'doe', 'age' => 45),
    '4c' => array(
        'family' => array(
            'toe' => array(
                'name' => 'doe',
                'age' => 39
            ),
            'age' => 45
        ))
);

function in_array_r($needle, $val, $arr)
{
    $flag = false;
    if (isset($arr[$needle]) && $arr[$needle] == $val) {
        $flag = true;
    } else {
        foreach ($arr as $k => $subArr) {
            if (is_array($subArr)) {
                if (in_array_r($needle, $val, $subArr)) {
                    $flag = $subArr;
                    break;
                }
            }
        }
    }
    return $flag;
}

echo '<pre>';
var_dump(in_array_r('age', 39, $a));
?>