Param(
    [string]$InputString = 'reyedfim'
);

$md5 = new-object -TypeName System.Security.Cryptography.MD5CryptoServiceProvider;
$utf8 = new-object -TypeName System.Text.UTF8Encoding;

$Found = 0;
$Password = "";
$i=0;
do {
    $Hash = [System.BitConverter]::ToString($md5.ComputeHash($utf8.GetBytes("${InputString}${i}")));
    if($Hash.Substring(0, 7) -eq "00-00-0") {
        $Found++;
        $Password = $Password + $Hash.Substring(7,1);
    }
    $i++;
}while ($Found -lt 8);

Write-Output "Part 1: ${Password}";

$Found = 0;
$Password = ("_" * 8).ToCharArray();
$i=0;
Write-Host "Part 2: (Enjoy the animation!)"
do {
    $Hash = [System.BitConverter]::ToString($md5.ComputeHash($utf8.GetBytes("${InputString}${i}")));
    if($Hash.Substring(0, 7) -eq "00-00-0") {
        if($Hash.Substring(7,1) -Match "^[0-7]$") {
            if($Password[[int]$Hash.Substring(7,1)] -eq '_') {
                $Found++;
                $Password[[int]$Hash.Substring(7,1)] = $Hash.Substring(9,1);
                Write-Host -NoNewLine ("`r" + ($Password -Join ""));
            }
        }
    }
    $i++;
}while ($Found -lt 8);
Write-Host "";