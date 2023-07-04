# xBinLibrary
 library for working with bin files

Using:

<code>require_once 'xBinLibrary/FilesUtils.php';
use xBinLibrary\FilesUtils;
</code>

Functions:

<code>//compare first 1000 lines of files
FilesUtils::compareLines('file1.bin', 'file2.bin', 1000);
</code>
<br>
<code>//comparing file values starting from 131083 and length 5
FilesUtils::compareFileValues('file1.bin', 'file2.bin', 131083, 5);
</code>
<br>

<code>// search for a pattern yyyymmdd with offset 8127001
$patterns = array(
    array(
        'pattern' => '/\b\d{8}\b/',
        'format' => function ($value) {
            $year = substr($value, 0, 4);
            $month = substr($value, 4, 2);
            $day = substr($value, 6, 2);
            $formattedDate = "$day.$month.$year";
            return $formattedDate;
        },
        'output' => function ($value, $position) {
            return $value;
        }
    )
);</code>

<code>$offsets = array(
    8127001
);</code>

<code>FilesUtils::searchPatterns($patterns,$offsets,'file1.bin');
</code>
<br>

<code>//copy bytes from one file to another up to $position starting from $offset
FilesUtils::copyFile('file2.bin', 'test.bin',$position,$offset);
</code>
