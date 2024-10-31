<?php

namespace ext;

use Exception;
use InvalidArgumentException;

class StringExt
{
    /**
     * 对字符串进行指定位置脱敏处理
     *
     * 该方法用于对字符串的指定位置进行脱敏处理，将指定范围内的字符替换为指定的掩码字符
     * 主要应用于敏感信息的显示，如手机号、身份证号等，需要在显示时对特定部分进行隐藏
     *
     * @param string $input 需要进行脱敏处理的字符串
     * @param int $start 脱敏开始位置（从0开始）
     * @param int $length 脱敏的字符长度
     * @param string $maskChar 用于替换的掩码字符，默认为 '*'
     * @return string 脱敏后的字符串
     *
     * @throws InvalidArgumentException 如果参数无效，则抛出参数异常
     */
    public static function desensitizeStringAtPosition(string $input, int $start, int $length, string $maskChar = '*'): string
    {
        try {
            // 如果输入为空字符串，直接返回
            if (empty($input)) {
                return $input;
            }

            // 获取输入字符串的长度
            $inputLength = mb_strlen($input, 'UTF-8');

            // 检查 start 和 length 是否在合理范围内
            if ($start < 0 || $start >= $inputLength) {
                throw new InvalidArgumentException('开始位置必须在字符串长度范围内');
            }
            if ($length < 0 || ($start + $length) > $inputLength) {
                throw new InvalidArgumentException('脱敏长度必须在字符串长度范围内');
            }

            // 获取脱敏前的部分
            $beforeMask = mb_substr($input, 0, $start, 'UTF-8');

            // 获取脱敏后的部分
            $afterMask = mb_substr($input, $start + $length, null, 'UTF-8');

            // 使用掩码字符进行脱敏
            $maskedPart = str_repeat($maskChar, $length);

            // 返回脱敏后的字符串
            return $beforeMask . $maskedPart . $afterMask;
        } catch (Exception $e) {
            // 异常
            throw new InvalidArgumentException("方法调用异常: " . $e->getMessage());
        }
    }

}