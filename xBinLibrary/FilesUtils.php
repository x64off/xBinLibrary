<?php

namespace xBinLibrary;

class FilesUtils
{
    public static function compareLines($file1, $file2, $lineCount = 0,$bufferSize = 1) {
        $handle1 = fopen($file1, 'rb');
        $handle2 = fopen($file2, 'rb');
        if (!$lineCount) $lineCount = min(filesize($file1),filesize($file2));
        if ($handle1 && $handle2) {
            $lineNumber = 0;
            $match = true;
    
            while ($lineNumber < $lineCount) {
                $buffer1 = fread($handle1, $bufferSize);
                $buffer2 = fread($handle2, $bufferSize);
                if ($buffer1 !== $buffer2) {
                    $match = false;
                    break;
                }
                $lineNumber++;
            }
            fclose($handle1);
            fclose($handle2);
            if ($match)
                return true;
            else
                return false;
        } else {
            return NULL;
        }
    }

    public static function compareFileValues($filename1, $filename2, $startPosition, $length) {
        // Открываем файлы для чтения в двоичном режиме
        $file1 = fopen($filename1, 'rb');
        $file2 = fopen($filename2, 'rb');
    
        if ($file1 && $file2) {
            // Устанавливаем указатели файлов на начало интересующего диапазона
            fseek($file1, $startPosition);
            fseek($file2, $startPosition);
    
            // Читаем данные из файлов
            $data1 = fread($file1, $length);
            $data2 = fread($file2, $length);
    
            // Сравниваем значения
            if ($data1 === $data2) {
                return true;
            } else {
                return false;
            }
    
            // Закрываем файлы
            fclose($file1);
            fclose($file2);
        } else {
            return NULL;
        }
    }
    public static function searchPatterns($patterns, $offsets, $filename,$bufferSize = 4096) {
        $handle = fopen($filename, 'rb'); // открытие файла в режиме чтения в двоичном режиме
        $output = array();
        if ($handle) {
            foreach ($offsets as $index => $offset) {
                fseek($handle, $offset); // устанавливаем указатель файла на заданную позицию
                $found = false; // флаг, указывающий, было ли найдено значение
                while (!feof($handle)) {
                    $buffer = fread($handle, $bufferSize);
                    if (preg_match($patterns[$index]['pattern'], $buffer, $matches, PREG_OFFSET_CAPTURE)) {
                        $position = ftell($handle) - $bufferSize + $matches[0][1];
                        $formattedValue = $patterns[$index]['format']($matches[0][0]);
                        $output[] = $patterns[$index]['output']($formattedValue, $position);
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $output[] = NULL;
                }
            }
    
            fclose($handle);
        } else {
            return false;
        }
    
        return $output;
    }
    public static function copyFile($sourceFile, $destinationFile,$position=false,$offset = 0,$bufferSize = 8192) {
        $sourceHandle = fopen($sourceFile, 'rb');
        $destinationHandle = fopen($destinationFile, 'r+b');
        if (!$position) $position = filesize($sourceFile);
        if ($sourceHandle && $destinationHandle) {
            fseek($sourceHandle, $offset); // установка указателя файла-источника в начало файла
            fseek($destinationHandle, $offset); // установка указателя файла-назначения в начало файла
            $bytesToCopy = $position; // количество байтов для копирования
            $bytesCopied = 0; // счетчик скопированных байтов
            $remainingBytes = $bytesToCopy;
            while ($remainingBytes > 0 && !feof($sourceHandle)) {
                $buffer = fread($sourceHandle, min($bufferSize, $remainingBytes)); // чтение буфера из файла-источника
                fwrite($destinationHandle, $buffer); // запись буфера в файл-назначение
                $bytesCopied += strlen($buffer); // обновление счетчика скопированных байтов
                $remainingBytes = $bytesToCopy - $bytesCopied;
            }
            fclose($sourceHandle); // закрытие файла-источника
            fclose($destinationHandle); // закрытие файла-назначения
            return true;
        }
        return false;
    }
    
    
}
