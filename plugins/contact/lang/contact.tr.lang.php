<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/lang/contact.tr.lang.php
Version=186
Updated=2026-sep-18
Type=
Author=Seditio Team
Description=
Translations=1
[END_SED]
==================== */

$L['contact_title'] = "İletişim";

$L['plu_explain'] = "Bize bir e-posta göndermek için bu formu doldurun, en kısa sürede cevap vereceğiz!";
$L['plu_recipients_title'] = "Alıcı";
$L['plu_email_title'] = "E-posta";
$L['plu_name_title'] = "Ad";
$L['plu_phone_title'] = "Telefon numarası";
$L['plu_subject_title'] = "Konu";
$L['plu_message_title'] = "Mesaj";
$L['plu_required'] = "* = Gerekli";
$L['plu_verify'] = "Sadece insan olduğunuzdan emin olmak için, lütfen bu sayıyı noktasız olarak tekrar girin: ";
$L['plu_send'] = "Gönder";

$L['plu_fieldempty'] = "En az bir alan boş.";
$L['plu_wrongentry'] = "En az bir alanda bir hata var.";
$L['plu_antispam'] = "Spambot koruma anahtarı yanlış, lütfen tekrar yazın!";
$L['plu_notsent'] = "Mesaj GÖNDERİLMEDİ.";
$L['plu_sent'] = "Mesaj başarıyla gönderildi!";
$L['plu_notice'] = "Bu mesaj " . (isset($cfg['maintitle']) ? $cfg['maintitle'] : "") . " adresinden şu kişi tarafından gönderildi: ";

$L['cfg_emails'] = array("Virgülle ayrılmış e-posta adresleri listesi", "");
$L['cfg_recipients'] = array("E-posta listesi sırasına göre virgülle ayrılmış alıcı isimleri", "");
$L['cfg_admincopy1'] = array("Mesajın bir kopyasını e-postaya gönder", "");
$L['cfg_admincopy2'] = array("Mesajın bir kopyasını e-postaya gönder", "");
$L['cfg_extra1'] = array("Ek alan #1 / skins/.../plugin.standalone.contact.tpl içinde {PLUGIN_CONTACT_EXTRA1}", "");
$L['cfg_extra2'] = array("Ek alan #2 / skins/.../plugin.standalone.contact.tpl içinde {PLUGIN_CONTACT_EXTRA2}", "");
$L['cfg_extra3'] = array("Ek alan #3 / skins/.../plugin.standalone.contact.tpl içinde {PLUGIN_CONTACT_EXTRA3}", "");
