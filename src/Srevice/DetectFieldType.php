<?php

namespace App\Srevice;

class DetectFieldType
{

    const ARRAY_TYPE = 'array';
    const TABLE_TYPE = 'table';
    const PROPERTY_LIST_TYPE = 'propertyList';

    public function detect($data, string $path, int $level)
    {
        $type = $this->detectType($data);

        if ($level > 0
            && in_array($type, [self::TABLE_TYPE, self::PROPERTY_LIST_TYPE])
        ) {
            $property = [];
            foreach ($data as $key => $item) {
                $itemType = $this->detectType($item);

                if ($itemType === self::TABLE_TYPE) {
                    $itemProperty = $this->detect(current($item), $path . '.' . $key,$level -1);
                } else {
                    $itemProperty = $this->detect($item, $path . '.' . $key,$level - 1 );
                }

                $property[] = [
                    'name' => $key,
                    'type' => $itemType,
                    'path' => $path . '.' . $key,
                    'property' => $itemProperty
                ];
            }

            return $property;
        }

        return $type;
    }

    private function detectType($data)
    {
        $type = gettype($data);
        if ($type === self::ARRAY_TYPE) {
            return is_int(array_key_first($data))
                ? self::TABLE_TYPE
                : self::PROPERTY_LIST_TYPE;
        }
        return $type;
    }
}