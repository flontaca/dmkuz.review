<?

namespace Dmkuz\Reviews;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class BookTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_dmkuz_book';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]

            ),
            new Entity\StringField(
                'NAME',
                [
                    'required' => true
                ]

            ),
            new Entity\StringField(
                'AUTHOR',
                [
                    'required' => true
                ]

            ),
            new Entity\IntegerField(
                'RELEASED',
                [
                    'required' => true
                ]
            ),
            new Entity\FloatField(
                'AVG_RATING',
                []
            ),
        ];
    }
}

?>