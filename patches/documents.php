<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

Utils_CommonDataCommon::new_array("Kontrakty/raporty", array('email_upadki' => ''));
Utils_AttachmentCommon::new_addon("kontrakty",$caption="Dokumenty");
