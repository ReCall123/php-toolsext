<?php

namespace ext;

use InvalidArgumentException;

class ArrayExt
{
    /**
     * 根据指定字段对项目数组进行分组
     *
     * @param array $items 包含多个项目的数组，每个项目通常是一个字段数组
     * @param string $field 用于分组的字段名
     * @return array 返回一个数组，其中每个元素都是具有相同字段值的项目分组
     *
     * 该函数使用array_reduce对输入的项目数组进行迭代，根据指定的字段将每个项目分配到相应的分组中
     * 分组的键是每个项目中指定字段的值，值是属于该分组的项目数组
     * @throws InvalidArgumentException 如果 $items 为空数组，或者 $field 不存在于每个项目中
     */
    public static function groupByField(array $items, string $field): array
    {
        // 检查 $items 是否为空数组
        if (empty($items)) {
            return [];
        }

        // 使用 array_reduce 函数对 $items 数组进行迭代和分组
        return array_reduce($items, function (array $carry, array $item) use ($field) {
            // 检查字段是否存在
            if (!isset($item[$field])) {
                // 抛出异常
                throw new InvalidArgumentException("字段 '$field'不存在于\$items数组中");
            } else {
                $key = $item[$field];
            }

            // 将当前项目添加到相应的分组中
            $carry[$key][] = $item;

            // 返回更新后的结果数组
            return $carry;
        }, []);
    }


    /**
     * 翻转数组键值对
     *
     * 本函数通过接收一个数组，将其中的键和值进行翻转
     * 特别地，本函数在翻转之前，将所有值转换为字符串，以确保键的唯一性和正确性
     *
     * @param array $array 需要被翻转键值对的数组
     * @return array 翻转键值对后的数组
     */
    public static function arrayFlip(array $array): array
    {
        if (empty($array)) {
            return [];
        }

        try {
            // 检查数组中的值是否已经是字符串
            if (array_reduce($array, function ($carry, $item) {
                return $carry && is_string($item);
            }, true)) {
                return array_flip($array);
            }

            // 否则，使用array_map将数组中的每个值转换为字符串，然后使用array_flip翻转数组的键和值
            return array_flip(array_map('strval', $array));
        } catch (\Exception $e) {
            // 或者抛出自定义异常
            throw new InvalidArgumentException("数组包含无法转换为字符串的值");
        }
    }


    /**
     * 计算数组中所有 numeric 类型元素的和，并允许指定精度
     *
     * 此函数使用 bcadd 进行高精度计算，避免浮点数计算的精度问题
     * 它只处理数组中的 numeric 类型值，确保计算的安全性和准确性
     *
     * @param array $arr 需要计算和的数组
     * @param int $scale 计算结果的小数点后位数，默认为 20
     * @return string 计算结果的字符串表示
     *
     * @throws InvalidArgumentException 如果 $scale 参数不在 0 到 100 的范围内
     */
    public static function arraySum(array $arr, int $scale = 20): string
    {
        // 检查 scale 是否在合理范围内
        if ($scale < 0 || $scale > 100) {
            throw new InvalidArgumentException("比例必须介于 0 和 100 之间");
        }

        $sum = '0';

        // 遍历数组，只处理 numeric 类型的值
        foreach ($arr as $v) {
            if (is_numeric($v)) {
                // 使用 bcadd 进行高精度加法运算
                $sum = bcadd($sum, FloatExt::floatToString(floatval($v)), $scale);
            }
        }

        // 返回计算结果的字符串表示
        return FloatExt::floatToString($sum);
    }

}