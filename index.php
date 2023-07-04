<?php

require_once 'xBinLibrary/FilesUtils.php';

use xBinLibrary\FilesUtils;

// сравнение файлов (количество строк)
echo FilesUtils::compareLines('file1.bin', 'file2.bin', 1000);
// сравнение значений файлов (начальная позиция,длинна)
echo FilesUtils::compareFileValues('file1.bin', 'file2.bin', 131083, 5); 

$patterns = array(
    array(
        'pattern' => '/\b\d{8}\b/', // регулярное выражение для поиска строки с датой в формате "yyyymmdd"
        'format' => function ($value) {
            $year = substr($value, 0, 4);
            $month = substr($value, 4, 2);
            $day = substr($value, 6, 2);
            // Формирование новой строки в формате "dd mm yyyy"
            $formattedDate = "$day.$month.$year";
            return $formattedDate; // Форматирование для даты: оставляем без изменений
        },
        'output' => function ($value, $position) {
            return $value; // Формат вывода для даты
        }
    )
);
$offsets = array(
    8127001
);

// поиск паттернов в файле
var_dump(FilesUtils::searchPatterns($patterns,$offsets,'file1.bin'));
// копирование значений в файл (можно указать : до каких значений,c каких значений,буфер)
copy('file1.bin', 'test.bin');
echo FilesUtils::copyFile('file2.bin', 'test.bin');