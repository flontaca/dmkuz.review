<?

namespace Dmkuz\Reviews;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Dmkuz\Reviews\BookTable;
use Dmkuz\Reviews\ReviewTable;

class Reviewsbook
{
    public function getList($page = 0, $limit = 50)
    {
        $reviewEntity = ReviewTable::getEntity();
        $qRes =
            (new Entity\Query($reviewEntity))
                ->setSelect([
                    'TIME_CREATE',
                    'TEXT',
                    'RATING',
                    'BOOK',
                ])
                ->setLimit($limit)
                ->setOffset($page * $limit)
                ->exec();
                
        $elements = $qRes->fetchCollection();
        foreach ($elements as $obj) {
            $arRes[] = [
                'date' => $obj->get('TIME_CREATE')->format('d.m.Y'),
                'text' => $obj->get('TEXT'),
                'rating' => $obj->get('RATING'),
                'book' => [
                    'title' => $obj->get('BOOK')->get('NAME'),
                    'author' => $obj->get('BOOK')->get('AUTHOR'),
                    'year' => $obj->get('BOOK')->get('RELEASED')
                ]

            ];
        }
        echo "<pre>";print_r($arRes);echo "</pre>";
    }
}