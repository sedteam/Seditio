<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/users/lang/users.tr.lang.php
Version=185
Updated=2026-feb-21
Type=Module.lang
Author=Seditio Team
Description=Users Turkish language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ====== Auth ====== */

$L['aut_usernametooshort'] = "Kullanıcı adı en az 2 karakter uzunluğunda olmalıdır";
$L['aut_passwordtooshort'] = "Şifre en az 4 karakter uzunluğunda olmalı ve yalnızca alfanümerik karakterler ve alt çizgi içermelidir.";
$L['aut_emailtooshort'] = "E-posta geçerli değil.";
$L['aut_usernamealreadyindb'] = "Girdiğiniz kullanıcı adı zaten veritabanında mevcut";
$L['aut_emailalreadyindb'] = "Girdiğiniz e-posta zaten veritabanında mevcut";
$L['aut_passwordmismatch'] = "Şifre alanları eşleşmiyor!";
$L['aut_emailbanned'] = "Bu e-posta (veya bu host) yasaklanmış, sebep: ";

$L['aut_contactadmin'] = "Herhangi bir zorluk yaşarsanız lütfen site yöneticisi ile iletişime geçin";

$L['aut_regrequesttitle'] = "Kayıt talebi";
$L['aut_regrequest'] = "Merhaba %1\$s,\n\nBu e-postayı alıyorsunuz çünkü siz (veya sizin gibi birisi) web sitemizde yeni bir hesap oluşturmuşsunuz. Eğer bu e-postayı istemediyseniz, lütfen göz ardı edin. Eğer sürekli alıyorsanız, lütfen site yöneticisi ile iletişime geçin. \n\nHesabınız şu anda aktif değil, giriş yapabilmeniz için bir yönetici hesabınızı aktif hale getirmelidir. Bu işlem yapıldığında başka bir e-posta alacaksınız. O zaman giriş yapabileceksiniz: \n\nKullanıcı adı = %1\$s \nŞifre = %2\$s";

$L['aut_regreqnoticetitle'] = "Yeni hesap talebi";
$L['aut_regreqnotice'] = "Merhaba,\n\nBu e-postayı alıyorsunuz çünkü %1\$s yeni bir hesap talep etti.\nBu kullanıcı, hesabı 'aktif' olarak ayarlanana kadar giriş yapamayacak. Bunu burada manuel olarak yapabilirsiniz:\n\n %2\$s";

$L['aut_emailreg'] = "Merhaba %1\$s,\n\nBu e-postayı alıyorsunuz çünkü siz (veya sizin gibi birisi) web sitemizde yeni bir hesap oluşturmuşsunuz. Eğer bu e-postayı istemediyseniz, lütfen göz ardı edin. Eğer sürekli alıyorsanız, lütfen site yöneticisi ile iletişime geçin.\n\nHesabınızı kullanabilmek için şu bağlantıyı tıklayarak aktif etmeniz gerekiyor:\n\n %3\$s \n\nO zaman giriş yapabileceksiniz: \n\nKullanıcı adı = %1\$s \nŞifre = %2\$s";

$L['aut_registertitle'] = "Yeni bir üye hesabı kaydedin";
$L['aut_registersubtitle'] = "";
$L['aut_logintitle'] = "Giriş formu";

/* ====== Users ====== */

$L['use_title'] = "Kullanıcılar";
$L['use_subtitle'] = "Kayıtlı üyeler";
$L['useed_accountactivated'] = "Hesap aktifleştirildi";
$L['useed_email'] = "Bu e-postayı alıyorsunuz çünkü bir yönetici hesabınızı aktifleştirdi.\nArtık daha önce aldığınız kullanıcı adı ve şifreyle giriş yapabilirsiniz.\n\n";
$L['useed_title'] = "Düzenle";
$L['useed_subtitle'] = "&nbsp;";
$L['use_byfirstletter'] = "Adı ile başlayan";
$L['use_allusers'] = "Tüm kullanıcılar";
$L['use_allbannedusers'] = "Yasaklı kullanıcılar";
$L['use_allinactiveusers'] = "Pasif kullanıcılar";

/* ====== Profile ====== */

$L['pro_title'] = "Profil";
$L['pro_subtitle'] = "Kişisel hesabınız";
$L['pro_passtoshort'] = "Şifre en az 4 karakter uzunluğunda olmalı ve yalnızca alfanümerik karakterler ve alt çizgi içermelidir.";
$L['pro_passdiffer'] = "İki şifre alanı eşleşmiyor";
$L['pro_wrongpass'] = "Mevcut şifrenizi girmediniz veya yanlış girdiniz";
$L['pro_avatarsupload'] = "Bir avatar yükleyin";
$L['pro_sigupload'] = "Bir imza yükleyin";
$L['pro_photoupload'] = "Bir fotoğraf yükleyin";
$L['pro_avatarspreset'] = "...veya buraya tıklayarak önceden yüklenmiş avatarlar galerisini görüntüleyebilirsiniz";
$L['pro_avatarschoose'] = "Kendi avatarınızı ayarlamak için aşağıdaki resme tıklayın";
$L['pro_avataruploadfailed'] = "Yükleme başarısız oldu, eski avatarı silerek slotu boşaltın!";

/* ====== Config ====== */

$L['cfg_disablereg'] = array("Kayıt işlemini devre dışı bırak", "Kullanıcıların yeni hesaplar kaydetmesini engeller");
$L['cfg_defaultcountry'] = array("Yeni kullanıcılar için varsayılan ülke", "2 harfli ülke kodu");    // Yeni v130
$L['cfg_disablewhosonline'] = array("Kimler çevrimiçi kısmını devre dışı bırak", "Shield'ı etkinleştirirseniz otomatik olarak etkinleştirilir");
$L['cfg_maxusersperpage'] = array("Kullanıcı listesinde maksimum satır", "");
$L['cfg_regrequireadmin'] = array("Yöneticiler yeni hesapları onaylamalı", "");
$L['cfg_regnoactivation'] = array("Yeni kullanıcılar için e-posta kontrolünü atla", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_useremailchange'] = array("Kullanıcıların e-posta adreslerini değiştirmelerine izin ver", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_usertextimg'] = array("Kullanıcı imzasında resim ve HTML'ye izin ver", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_color_group'] = array("Kullanıcı gruplarını renkli yap", "Varsayılan: Hayır, daha iyi performans için");  // Yeni v175
$L['cfg_av_maxsize'] = array("Avatar, maksimum dosya boyutu", "Varsayılan: 8000 bayt");
$L['cfg_av_maxx'] = array("Avatar, maksimum genişlik", "Varsayılan: 64 piksel");
$L['cfg_av_maxy'] = array("Avatar, maksimum yükseklik", "Varsayılan: 64 piksel");
$L['cfg_usertextmax'] = array("Kullanıcı imzası için maksimum uzunluk", "Varsayılan: 300 karakter");
$L['cfg_sig_maxsize'] = array("İmza, maksimum dosya boyutu", "Varsayılan: 50000 bayt");
$L['cfg_sig_maxx'] = array("İmza, maksimum genişlik", "Varsayılan: 468 piksel");
$L['cfg_sig_maxy'] = array("İmza, maksimum yükseklik", "Varsayılan: 60 piksel");
$L['cfg_ph_maxsize'] = array("Fotoğraf, maksimum dosya boyutu", "Varsayılan: 8000 bayt");
$L['cfg_ph_maxx'] = array("Fotoğraf, maksimum genişlik", "Varsayılan: 96 piksel");
$L['cfg_ph_maxy'] = array("Fotoğraf, maksimum yükseklik", "Varsayılan: 96 piksel");
