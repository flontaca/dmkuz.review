<?

namespace Dmkuz\Reviews;

use Dmkuz\Reviews\ReviewTable;
use Dmkuz\Reviews\BookTable;
use Bitrix\Main\Entity;
use \Bitrix\Main\Diag;

class event
{
    public function addHandler(\Bitrix\Main\Entity\Event $event)
    {
        $fields = $event->getParameter('fields');
        $bookId = $fields['BOOK_ID'];
        self::updateBookRating($bookId);
    }

    public function updateHandler(\Bitrix\Main\Entity\Event $event)
    {
        $arId = $event->getParameter('primary');
        $id = $arId['ID'];
        $entity = $event->getEntity();
        $entityDataClass = $entity->GetDataClass();
        $qRes =
            (new Entity\Query($entityDataClass))
                ->setSelect([
                    'BOOK_ID'
                ])
                ->setFilter(
                    [
                        'ID' => $id
                    ])
                ->exec();
        $obj = $qRes->fetchObject();
        $bookId = $obj->get('BOOK_ID');
        self::updateBookRating($bookId);
    }

    private static $bookIdForDelReview = null;

    function onBeforeDelete(\Bitrix\Main\Entity\Event $event)
    {
        $arId = $event->getParameter('primary');
        $id = $arId['ID'];
        $entity = $event->getEntity();
        $entityDataClass = $entity->GetDataClass();
        $qRes =
            (new Entity\Query($entityDataClass))
                ->setSelect([
                    'BOOK_ID'
                ])
                ->setFilter(
                    [
                        'ID' => $id
                    ])
                ->exec();
        $obj = $qRes->fetchObject();
        $bookId = $obj->get('BOOK_ID');
        self::$bookIdForDelReview = $bookId;
    }

    function onAfterDelete(\Bitrix\Main\Entity\Event $event)
    {
        $bookId = self::$bookIdForDelReview;
        self::updateBookRating($bookId);
    }

    public function updateBookRating($bookId)
    {
        $avgRating = self::getAvgRating($bookId);
        BookTable::update($bookId, ['AVG_RATING' => $avgRating]);
    }

    public function getAvgRating($bookId)
    {
        $reviewEntity = ReviewTable::getEntity();
        $qRes =
            (new Entity\Query($reviewEntity))
                ->setSelect([
                    'RATING',
                ])
                ->setFilter(
                    [
                        'BOOK_ID' => $bookId
                    ])
                ->exec();

        $countRating = 0;
        $totalRating = 0;

        $ratingElems = $qRes->fetchCollection();
        foreach ($ratingElems as $ratingObj) {
            if ($ratingObj->get('RATING') != 0) {
                $totalRating += $ratingObj->get('RATING');
                $countRating++;
            }
        }

        $avgRating = round($totalRating / $countRating, 1);
        return $avgRating;
    }
}