<?php

namespace ext;

class FloatExt
{
    /**
     * 将浮点数转换为字符串
     * 此函数处理浮点数的字符串表示，去除不必要的零和处理科学计数法
     * 特别适用于数据库操作，避免因数据类型不匹配导致的错误
     *
     * @param mixed $value 要转换的浮点数值可以是字符串或浮点数
     * @param string $nullDefault 当结果为空时返回的内容，默认为空
     * @return string 转换后的字符串表示的浮点数或者$nullDefault
     */
    public static function floatToString($value, string $nullDefault = ''): string
    {
        // 确保$value是合法的数值类型
        if (!is_numeric($value)) {
            return false;
        }

        // 检查$value是否为浮点数的字符串表示
        if (preg_match('/^-?\d+\.\d+$/', (string)$value)) {
            // 小数点的数字不显示后边0，如：5.100 转为 5.1
            $value = floatval($value);
        }
        // 处理科学计数法表示的浮点数
        if (is_float($value) && strpos((string)$value, 'E-') !== false) {
            // 科学计数法的浮点数转为字符串 3e-6 转为 0.000003
            $split = explode('E-', (string)$value);
            $wei   = $split[1];
            // 处理如9.80e-5的情况
            if (strpos($split[0], '.') !== false) {
                $split1 = explode('.', rtrim($split[0], '0'));
                $wei    += strlen($split1[1]);
            }
            // 根据计算出的小数位数格式化浮点数
            $value = sprintf("%.{$wei}f", $value);
        }
        // 将处理后的值转换为字符串
        $res = (string)$value;
        // 如果结果为空字符串且设置了默认值，则返回默认值
        if ($res === '' && $nullDefault !== '') {
            return $nullDefault;
        }
        // 返回转换后的字符串
        return $res;
    }

}