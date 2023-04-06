<?

namespace Dmkuz\Reviews;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class ReviewTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_dmkuz_review';
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
            new Entity\DatetimeField(
                'TIME_CREATE',
                [
                    'required' => true,
                    'default_value' => new Type\DateTime
                ]
            ),
            new Entity\TextField('TEXT'),
            new Entity\IntegerField(
                'RATING',
                [
                    'required' => true
                ]
            ),

            new Entity\IntegerField(
                'BOOK_ID',
                [
                    'required' => true
                ]
            ),

            new Entity\ReferenceField(
                'BOOK',
                '\Dmkuz\Reviews\BookTable',
                ['=this.BOOK_ID' => 'ref.ID']
            ),
        ];
    }
}

?>