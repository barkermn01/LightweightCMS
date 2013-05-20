<?php 
function toNum($data) {
    $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                       'f', 'g', 'h', 'i', 'j',
                       'k', 'l', 'm', 'n', 'o',
                       'p', 'q', 'r', 's', 't',
                       'u', 'v', 'w', 'x', 'y',
                       'z', 'A','B','C','D','E',
					   'F','G','H','I','J','K',
					   'L','M','N','O','P','Q',
					   'R','S','T','U','V','W',
					   'X','Y', 'Z'
                       );
    $alpha_flip = array_flip($alphabet);
    $return_value = -1;
    $length = strlen($data);
    for ($i = 0; $i < $length; $i++) {
        $return_value +=
            ($alpha_flip[$data[$i]] + 1) * pow(count($alphabet), ($length - $i - 1));
    }
    return $return_value;
}

function toAlpha($data){
    $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                       'f', 'g', 'h', 'i', 'j',
                       'k', 'l', 'm', 'n', 'o',
                       'p', 'q', 'r', 's', 't',
                       'u', 'v', 'w', 'x', 'y',
                       'z', 'A','B','C','D','E',
					   'F','G','H','I','J','K',
					   'L','M','N','O','P','Q',
					   'R','S','T','U','V','W',
					   'X','Y', 'Z'
                       );
		$alpha_flip = array_flip($alphabet);
        if($data <= count($alphabet)){
          return $alphabet[$data];
        }
        elseif($data > count($alphabet)){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % count($alphabet);
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / count($alphabet)));
          } 
          return $alpha;
        }

}

$time = round(microtime(true) * 1000);
echo "DateTime is: ".$time."<br />\r\n";
$sTime = toAlpha($time);
echo "DateTime String: ".$sTime."<br />\r\n";
$iTime = toNum($sTime);
echo "DateTime from string as in is: ".$iTime."<br />\r\n";