<?

use Bitrix\Main\Localization\Loc;

echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
<form action="<? echo $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<? echo LANGUAGE_ID ?>">
    <input type="submit" name="" value="<? echo GetMessage("MOD_BACK") ?>">
</form>
