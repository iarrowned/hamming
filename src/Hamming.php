<?php

namespace Main;

class Hamming
{
    public static function encode(string $input): string
    {
        $inputLength = strlen($input); // узнаем длину

        $k = 2;
        $l2n = log10(2); //
        while ($k * $l2n < log10($k + $inputLength + 1)) $k++; // вычисляем по формуле минимальное k


        $result = []; // заводим массив разрядов

        for ($i = 1, $j=0, $n = $k + $inputLength; $i <= $n; $i++) { // проходим по всем разрядам
            if (($i & $i - 1) == 0) { // если степень двойки, то есть контрольный разряд, то пропускаем
                $result[$i] = '-';
                continue;
            }
            $result[$i] = $input[$j++]; // в информационные разряды записываем исходные данные
        }

        $control = 0;
        for ($i = 1, $n = $k + $inputLength; $i <= $k + $inputLength; $i++) { // проходим по всем разрядам
            if ($result[$i] == 1)
                $control ^= $i; // если единица, то суммируем по модулю два
        }

        $control = decbin($control); // преобразовываем в двоичный код

        // дописываем спереди нули
        for ($i=0, $n = strlen($control); $i < $k - $n; $i++) {
            $control = "0" . $control;
        }


        for ($i = 0, $j = 1, $n = strlen($control); $i < $n; $i++, $j*=2) {
            $result[$j] = $control[$i];
        }

        $output = "";
        for ($i= 1, $n = count($result); $i <= $n; $i++) {
            $output .= $result[$i];
        }

        return $output;
    }

    public static function decode($code): array
    {
        $r = 0;
        while (pow(2, $r) < strlen($code) + 1) {
            $r++;
        }

        $data = '';
        $error = false;

        $j = 0;
        for ($i = 0; $i < strlen($code); $i++) {
            if (pow(2, $j) - 1 == $i) {
                $j++;
            } else {
                $data .= $code[$i];
            }
        }

        $j = 0;
        for ($i = 0; $i < $r; $i++) {
            $check = 0;
            for ($k = pow(2, $i) - 1; $k < strlen($code); $k += 2 * pow(2, $i)) {
                for ($m = 0; $m < pow(2, $i) && $k + $m < strlen($code); $m++) {
                    $check ^= $code[$k + $m];
                }
            }
            if ($check != 0) {
                $error = true;
                $data[pow(2, $i) - 1] = $data[pow(2, $i) - 1] == '1' ? '0' : '1';
            }
        }

        if ($error) {
            return ['data' => $data, 'error' => true];
        } else {
            return ['data' => $data, 'error' => false];
        }
    }
}