$a = 1;

$b = 57;
$c = $b;

if($a -ne 0) {
    $b *= 100;      # 5700
    $b -= -100000;  # 105700

    $c = $b;        # 105700
    $c -= -17000;   # 122700
}

do {
    $f = 1; # L9
    $d = 2; #L10

    do {
        $e = 2; #L11

        do {
            $g = ($d * $e) - $b; #L12-14

            if($g -eq 0) { #L15
                $f = 0; # L16
            }

            $e++; #L17

            $g = $e - $b; #L18-19

        }while($g -ne 0); #L20

        $d++; #L21

        $g = $d - $b; #L22-23

    } while ($g -ne 0); #L24

    if($f -eq 0) {
        $h -= -1;
    }

    $g = $b;
    $g -= $c;

    if($g -eq 0) {
        write-output $h;
        exit;
    }

    $b -= -17;
}while($true);

# 1000 too high