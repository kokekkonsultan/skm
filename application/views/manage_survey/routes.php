<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'HomeController';
$route['404_override'] = 'App_error/not_found';
$route['translate_uri_dashes'] = FALSE;
$route['web-service'] = 'App_error/service';

$route['template'] = 'TemplateController/index';

// Home
$route['home'] = 'HomeController/index';
$route['about'] = 'HomeController/about';
$route['team'] = 'HomeController/team';
$route['contact'] = 'ContactController/index';
$route['contact/validate-message'] = 'ContactController/validate_message';
$route['contact/refresh-captcha'] = 'ContactController/refresh_captcha';
$route['privacy'] = 'HomeController/privacy';
$route['legal'] = 'HomeController/legal';
$route['validasi-sertifikat/(:any)'] = 'HomeController/validasi_sertifikat/$1';
$route['cari'] = 'HomeController/cari';

// Auth
$route['auth'] = 'Auth/index';
$route['auth/index'] = 'Auth/index';
$route['auth/login'] = 'Auth/login';
$route['auth/_redirection'] = 'Auth/_redirection';
$route['auth/logout'] = 'Auth/logout';
$route['user-logout'] = 'Auth/user_logout';
$route['auth/change_password'] = 'Auth/change_password';
$route['auth/forgot_password'] = 'Auth/forgot_password';
$route['auth/reset_password/(:num)'] = 'Auth/reset_password/$1';
$route['auth/activate/(:num)/(:num)'] = 'Auth/activate/$1/$2';
$route['auth/deactivate/(:num)'] = 'Auth/deactivate/$1';
$route['auth/create_user'] = 'Auth/create_user';
$route['auth/redirectUser'] = 'Auth/redirectUser';
$route['auth/edit_user/(:num)'] = 'Auth/edit_user/$1';
$route['auth/create_group'] = 'Auth/create_group';
$route['auth/edit_group/(:num)'] = 'Auth/edit_group/$1';
$route['auth/_get_csrf_nonce'] = 'Auth/_get_csrf_nonce';
$route['auth/_valid_csrf_nonce'] = 'Auth/_valid_csrf_nonce';
$route['auth/_render_page/(:num)/(:num)/(:num)'] = 'Auth/_render_page/$1/$2/$3';
$route['auth/delete_user'] = 'Auth/delete_user';
$route['auth/generate-password'] = 'Auth/generate_password';
$route['auth/generate-password-form'] = 'Auth/generate_password_form';
$route['auth/delete-user/(:num)'] = 'Auth/delete_user/$1';
$route['auth/update-aside/(:num)'] = 'Auth/update_aside/$1';

// DASHBOARD
$route['dashboard'] = 'DashboardController/index';
$route['dashboard/jumlah-survei'] = 'DashboardController/jumlah_survei';
$route['prosedur-penggunaan-aplikasi'] = 'DashboardController/prosedur_aplikasi';
$route['(:any)/dashboard/chart-survei'] = 'DashboardController/get_chart_survei/$1';
$route['(:any)/dashboard/tabel-survei'] = 'DashboardController/get_tabel_survei/$1';
$route['(:any)/dashboard/ajax-list-tabel-survei'] = 'DashboardController/ajax_list_tabel_survei/$1';
$route['(:any)/dashboard/detail-hasil-analisa/(:num)'] = 'DashboardController/get_detail_hasil_analisa/$1/$2';

// RESELLER REQUEST
$route['reseller-request'] = 'ResellerRequestController/index';
$route['reseller-request/ajax-list'] = 'ResellerRequestController/ajax_list';
$route['reseller-request/get-detail'] = 'ResellerRequestController/get_detail';
$route['reseller-request/ajax-delete/(:num)'] = 'ResellerRequestController/ajax_delete/$1';

// RESELLER AREA
$route['reseller-area'] = 'ResellerAreaController/index';
$route['form-pendaftaran-reseller'] = 'ResellerAreaController/registration_form';
$route['form-pendaftaran-reseller/validate-message'] = 'ResellerAreaController/validate_message';
$route['form-pendaftaran-reseller/refresh-captcha'] = 'ResellerAreaController/refresh_captcha';

// ARTICLE/ ARTIKEL
$route['article'] = 'ArticleController/index';
$route['article/post/(:any)'] = 'ArticleController/view/$1';

// ARTICLE/ ARTIKEL
$route['publikasi'] = 'PublikasiController/index';
$route['publikasi-link-survei'] = 'PublikasiController/publikasi_link_survei/$1';

// INBOX
$route['inbox'] = 'InboxController/index';
$route['inbox/data-inbox'] = 'InboxController/get_data_inbox';
$route['inbox/reply/(:num)'] = 'InboxController/reply/$1';
$route['inbox/validate-message'] = 'InboxController/validate_message';
$route['inbox/delete-reply/(:num)'] = 'InboxController/delete_reply/$1';
$route['inbox/ajax-list'] = 'InboxController/ajax_list';

// WEBSITE CONFIGURATION
$route['website-configuration'] = 'WebsiteController/index';
$route['update-website-configuration'] = 'WebsiteController/update_website_configuration';
$route['update-home-configuration'] = 'WebsiteController/update_home_configuration';
$route['website-reseller-area-configuration/ajax-edit/(:num)'] = 'WebsiteController/ajax_edit_reseller_area/$1';
$route['website-reseller-area-configuration/ajax-save-reseller-config'] = 'WebsiteController/update_reseller_area_configuration';

// BANNER
$route['banner'] = 'BannerController/index';
$route['banner/ajax-list'] = 'BannerController/ajax_list';
$route['banner/add'] = 'BannerController/add';
$route['banner/edit/(:num)'] = 'BannerController/edit/$1';
$route['banner/delete/(:num)'] = 'BannerController/delete/$1';
$route['banner/get-detail'] = 'BannerController/get_detail';
$route['banner/get-detail-link'] = 'BannerController/get_detail_link';
$route['banner/update-is-show-value'] = 'BannerController/update_is_show_value';
$route['banner/update-is-show'] = 'BannerController/update_is_show';
$route['banner/update-read-more-active-value'] = 'BannerController/update_read_more_active_value';
$route['banner/update-read-more-active'] = 'BannerController/update_read_more_active';
$route['banner/update-contact-active-value'] = 'BannerController/update_contact_active_value';
$route['banner/update-contact-active'] = 'BannerController/update_contact_active';

$route['article-post'] = 'ArticlePostController/index';
$route['article-post/ajax-list'] = 'ArticlePostController/ajax_list';
$route['article-post/create'] = 'ArticlePostController/create';
$route['article-post/edit/(:any)'] = 'ArticlePostController/edit/$1';
$route['article-post/delete/(:any)'] = 'ArticlePostController/delete/$1';
$route['article-post/get-detail'] = 'ArticlePostController/get_detail';
$route['article-post/update-is-show-value'] = 'ArticlePostController/update_is_show_value';
$route['article-post/update-is-show'] = 'ArticlePostController/update_is_show';

$route['article-category-post'] = 'ArticleCategoryController/index';
$route['article-category-post/ajax-add'] = 'ArticleCategoryController/ajax_add';
$route['article-category-post/ajax-edit/(:num)'] = 'ArticleCategoryController/ajax_edit/$1';
$route['article-category-post/ajax-update'] = 'ArticleCategoryController/ajax_update';
$route['article-category-post/ajax-delete/(:num)'] = 'ArticleCategoryController/ajax_delete/$1';
$route['article-category-post/ajax-list'] = 'ArticleCategoryController/ajax_list';

$route['list-link-article'] = 'ArticlePostController/list_link_article';
$route['list-link-article/ajax-list'] = 'ArticlePostController/ajax_list_link_article';

// IMAGE UPLOAD
$route['image-upload'] = 'ImageUploadController/index';
$route['image-upload/ajax-list'] = 'ImageUploadController/ajax_list';
$route['image-upload/process-upload'] = 'ImageUploadController/process_upload';
$route['image-upload/get-detail'] = 'ImageUploadController/get_detail';
$route['image-upload/ajax-delete/(:any)'] = 'ImageUploadController/ajax_delete/$1';

// UNSUR SKM
$route['unsur-skm'] = 'UnsurSkmController/index';

// REVIEW PERTANYAAN UNSUR
$route['review-pertanyaan-unsur'] = 'ReviewPertanyaanUnsurController/index';
$route['review-pertanyaan-unsur/detail-unsur/(:num)'] = 'ReviewPertanyaanUnsurController/detail_unsur/$1';
$route['review-pertanyaan-unsur/detail-terbuka/(:num)'] = 'ReviewPertanyaanUnsurController/detail_terbuka/$1';

// NILAI SKM
$route['nilai-skm'] = 'NilaiSkmController/index';

// SAMPLING
$route['sampling/krejcie'] = 'SamplingController/krejcie';
$route['sampling/cochran'] = 'SamplingController/cochran';
$route['sampling/slovin'] = 'SamplingController/slovin';

// PROFIL RESPONDEN
$route['profil-responden'] = 'ProfilRespondenController/index';

//PENGGUNA ADMINISTRATOR
$route['pengguna-administrator'] = 'Auth/index';
$route['pengguna-administrator/ajax-list-administrator'] = 'Auth/ajax_list_administrator';
$route['pengguna-administrator/create-administrator'] = 'Auth/create_administrator';
$route['pengguna-administrator/edit_administrator/(:num)'] = 'Auth/edit_administrator/$1';
$route['auth/delete_user'] = 'Auth/delete_user';

//PENGGUNA KLIEN
$route['pengguna-klien'] = 'Auth/pengguna_klien';
$route['pengguna-klien/detail'] = 'Auth/get_detail';
$route['pengguna-klien/ajax-list-klien'] = 'Auth/ajax_list_klien';
$route['pengguna-klien/create-klien'] = 'Auth/create_klien';
$route['pengguna-klien/get-send-email'] = 'Auth/get_send_email';
$route['pengguna-administrator/edit_klien/(:num)'] = 'Auth/edit_klien/$1';
$route['pengguna-administrator/delete_klien'] = 'Auth/delete_klien';

//PENGGUNA SURVEYOR
$route['pengguna-surveyor'] = 'Auth/pengguna_surveyor';
$route['pengguna-surveyor/ajax-list-surveyor'] = 'Auth/ajax_list_surveyor';
$route['data-surveyor/edit-surveyor/(:num)'] = 'DataSurveyorController/edit_surveyor/$1';
$route['pengguna-surveyor/delete-user/(:num)'] = 'Auth/delete_surveyor/$1';


// JENIS SURVEY
$route['jenissurvey'] = 'JenisSurveyController/index';
$route['jenissurvey/create'] = 'JenisSurveyController/create';
$route['jenissurvey/insert'] = 'JenisSurveyController/insert';
$route['jenissurvey/edit/(:num)'] = 'JenisSurveyController/edit/$1';
$route['jenissurvey/update/(:num)'] = 'JenisSurveyController/update/$1';
$route['jenissurvey/delete/(:num)'] = 'JenisSurveyController/delete/$1';

// PAKET
$route['paket'] = 'PaketController/index';
$route['paket/ajax-list'] = 'PaketController/ajax_list';
$route['paket/ajax-add'] = 'PaketController/ajax_add';
$route['paket/ajax-edit/(:num)'] = 'PaketController/ajax_edit/$1';
$route['paket/ajax-update'] = 'PaketController/ajax_update';
$route['paket/ajax-delete/(:num)'] = 'PaketController/ajax_delete/$1';
$route['paket/get-detail'] = 'PaketController/get_detail';
$route['paket/update-status-aktif'] = 'PaketController/update_status_aktif';
$route['paket/update-status-aktif-value'] = 'PaketController/update_status_aktif_value';

$route['paket/trial-ajax-list'] = 'PaketController/trial_ajax_list';
$route['paket/ajax-add-trial'] = 'PaketController/ajax_add_trial';

$route['paket/add'] = 'PaketController/add';
$route['paket/edit/(:num)'] = 'PaketController/edit/$1';
$route['paket/delete/(:num)'] = 'PaketController/delete/$1';

// BERLANGGANAN
$route['berlangganan'] = 'BerlanggananController/index';
$route['berlangganan/data-langganan/(:any)'] = 'BerlanggananController/data_berlangganan/$1';
$route['berlangganan/data-langganan/perpanjangan/(:any)'] = 'BerlanggananController/perpanjangan/$1';
$route['berlangganan/data-langganan/edit-perpanjangan/(:any)'] = 'BerlanggananController/edit_perpanjangan/$1';
$route['berlangganan/data-langganan/delete-perpanjangan/(:any)'] = 'BerlanggananController/delete_perpanjangan/$1';
$route['berlangganan/detail'] = 'BerlanggananController/get_detail';
$route['berlangganan/get-send-email'] = 'BerlanggananController/get_send_email';
$route['berlangganan/get-detail-ajax'] = 'BerlanggananController/get_detail_ajax';

// Data Survey Klien
$route['data-survey-klien'] = 'DataSurveyKlienController/index';
$route['data-survey-klien/ajax-list'] = 'DataSurveyKlienController/ajax_list';
$route['data-survey-klien/detail/(:any)'] = 'DataSurveyKlienController/detail_survey/$1';
$route['data-survey-klien/ajax-list-detail/(:any)'] = 'DataSurveyKlienController/ajax_list_detail/$1';
$route['data-survey-klien/do/(:any)'] = 'DataSurveyKlienController/get_detail_survey/$1';
$route['data-survey-klien/profil-responden-survei/(:any)'] = 'DataSurveyKlienController/profil_responden/$1';
$route['data-survey-klien/ajax-list-profil-responden-survei/(:any)'] = 'DataSurveyKlienController/ajax_list_profil_responden/$1';
// $route['data-survey-klien/unsur-pelayanan/(:any)'] = 'DataSurveyKlienController/unsur_pelayanan/$1';
// $route['data-survey-klien/ajax-list-unsur/(:any)'] = 'DataSurveyKlienController/ajax_list_unsur/$1';
$route['data-survey-klien/pertanyaan-unsur/(:any)'] = 'DataSurveyKlienController/pertanyaan_unsur/$1';
$route['data-survey-klien/ajax-list-pertanyaan-unsur/(:any)'] = 'DataSurveyKlienController/ajax_list_pertanyaan_unsur/$1';
$route['data-survey-klien/pertanyaan-harapan/(:any)'] = 'DataSurveyKlienController/pertanyaan_harapan/$1';
$route['data-survey-klien/ajax-list-pertanyaan-harapan/(:any)'] = 'DataSurveyKlienController/ajax_list_pertanyaan_harapan/$1';
$route['data-survey-klien/pertanyaan-tambahan/(:any)'] = 'DataSurveyKlienController/pertanyaan_tambahan/$1';
$route['data-survey-klien/ajax-list-pertanyaan-tambahan/(:any)'] = 'DataSurveyKlienController/ajax_list_pertanyaan_tambahan/$1';
$route['data-survey-klien/pertanyaan-kualitatif/(:any)'] = 'DataSurveyKlienController/pertanyaan_kualitatif/$1';
$route['data-survey-klien/ajax-list-pertanyaan-kualitatif/(:any)'] = 'DataSurveyKlienController/ajax_list_pertanyaan_kualitatif/$1';
$route['data-survey-klien/form-survei/(:any)'] = 'DataSurveyKlienController/form_survei/$1';
$route['data-survey-klien/scan-barcode/(:any)'] = 'DataSurveyKlienController/scan_barcode/$1';
$route['data-survey-klien/data-surveyor/(:any)'] = 'DataSurveyKlienController/data_surveyor/$1';
$route['data-survey-klien/ajax-list-data-surveyor/(:any)'] = 'DataSurveyKlienController/ajax_list_data_surveyor/$1';
$route['data-survey-klien/data-perolehan-surveyor/(:any)'] = 'DataSurveyKlienController/data_perolehan_surveyor/$1';
$route['data-survey-klien/link-survey/(:any)']                 = 'DataSurveyKlienController/link_survey/$1';
$route['data-survey-klien/data-prospek-survey/(:any)'] = 'DataSurveyKlienController/data_prospek_survey/$1';
$route['data-survey-klien/ajax-list-data-prospek-survey/(:any)'] = 'DataSurveyKlienController/ajax_list_data_prospek_survey/$1';
$route['data-survey-klien/data-perolehan-survey/(:any)']     = 'DataSurveyKlienController/data_perolehan_survey/$1';
$route['data-survey-klien/ajax-list-data-perolehan-survey/(:any)']     = 'DataSurveyKlienController/ajax_list_data_perolehan_survey/$1';
$route['data-survey-klien/olah-data/(:any)']                 = 'DataSurveyKlienController/olah_data/$1';
$route['data-survey-klien/ajax-list-olah-data/(:any)']                 = 'DataSurveyKlienController/ajax_list_olah_data/$1';
$route['data-survey-klien/chart-visualisasi/(:any)']         = 'DataSurveyKlienController/chart_visualisasi/$1';
$route['data-survey-klien/kuadran/(:any)']                     = 'DataSurveyKlienController/kuadran/$1';
$route['data-survey-klien/rekap-responden/(:any)']             = 'DataSurveyKlienController/rekap_responden/$1';
$route['data-survey-klien/alasan-jawaban/(:any)']             = 'DataSurveyKlienController/alasan_jawaban/$1';
$route['data-survey-klien/ajax-list-alasan/(:any)']             = 'DataSurveyKlienController/ajax_list_alasan/$1';
$route['data-survey-klien/alasan-jawaban/(:any)/(:num)']             = 'DataSurveyKlienController/detail_alasan/$1/$2';
$route['data-survey-klien/ajax-list-detail-alasan/(:any)/(:num)']             = 'DataSurveyKlienController/ajax_list_detail_alasan/$1/$2';

$route['data-survey-klien/rekap-harapan/(:any)'] = 'DataSurveyKlienController/rekap_harapan/$1';
$route['data-survey-klien/ajax-list-rekap-harapan/(:any)'] = 'DataSurveyKlienController/ajax_list_rekap_harapan/$1';
$route['data-survey-klien/rekap-harapan/(:any)/(:num)'] = 'DataSurveyKlienController/detail_rekap_harapan/$1/$2';
$route['data-survey-klien/ajax-list-detail-rekap-harapan/(:any)/(:num)'] = 'DataSurveyKlienController/ajax_list_detail_rekap_harapan/$1/$2';
$route['data-survey-klien/rekap-tambahan/(:any)']             = 'DataSurveyKlienController/rekap_tambahan/$1';
$route['data-survey-klien/jawaban-pertanyaan-kualitatif/(:any)'] = 'DataSurveyKlienController/jawaban_pertanyaan_kualitatif/$1';
$route['data-survey-klien/ajax-list-jawaban-kualitatif/(:any)'] = 'DataSurveyKlienController/ajax_list_jawaban_kualitatif/$1';
$route['data-survey-klien/jawaban-pertanyaan-kualitatif/(:any)/(:num)'] = 'DataSurveyKlienController/detail_jawaban_kualitatif/$1/$2';
$route['data-survey-klien/ajax-list-detail-jawaban-kualitatif/(:any)/(:num)'] = 'DataSurveyKlienController/ajax_list_detail_jawaban_kualitatif/$1/$2';
$route['data-survey-klien/inovasi-saran/(:any)']             = 'DataSurveyKlienController/inovasi_saran/$1';
$route['data-survey-klien/ajax-list-inovasi-saran/(:any)']             = 'DataSurveyKlienController/ajax_list_inovasi_saran/$1';
$route['data-survey-klien/analisa-survei/(:any)'] = 'DataSurveyKlienController/analisa_survei/$1';
$route['data-survey-klien/ajax-list-analisa-survei/(:any)'] = 'DataSurveyKlienController/ajax_list_analisa_survei/$1';
$route['data-survey-klien/laporan-survei/(:any)']             = 'DataSurveyKlienController/laporan_survei/$1';
$route['data-survey-klien/draft-kuesioner/(:any)']             = 'DataSurveyKlienController/draft_kuesioner/$1';
$route['data-survey-klien/e-sertifikat/(:any)']             = 'DataSurveyKlienController/e_sertifikat/$1';


// PENGATURAN
$route['pengaturan'] = 'SettingsController/index';
$route['pengaturan/update-email'] = 'SettingsController/update_email';
$route['pengaturan/test-email'] = 'SettingsController/test_email';

// SURVEI
$route['survei/template'] = 'SurveiController/template';
$route['survei/(:any)'] = 'SurveiController/form_opening/$1';
$route['survei/(:any)/data-responden'] = 'SurveiController/data_responden/$1';
$route['survei/(:any)/add-data-responden'] = 'SurveiController/add_data_responden/$1';
$route['survei/(:any)/pertanyaan/(:any)'] = 'SurveiController/data_pertanyaan/$1/$2';
$route['survei/(:any)/add_pertanyaan/(:any)'] = 'SurveiController/add_pertanyaan/$1/$2';
$route['survei/(:any)/pertanyaan-harapan/(:any)'] = 'SurveiController/data_pertanyaan_harapan/$1/$2';
$route['survei/(:any)/add-pertanyaan-harapan/(:any)'] = 'SurveiController/add_pertanyaan_harapan/$1/$2';
$route['survei/(:any)/pertanyaan-kualitatif/(:any)'] = 'SurveiController/pertanyaan_kualitatif/$1/$2';
$route['survei/(:any)/add-kualitatif/(:any)'] = 'SurveiController/add_kualitatif/$1/$2';
$route['survei/(:any)/saran/(:any)'] = 'SurveiController/saran/$1/$2';
$route['survei/(:any)/add-saran/(:any)'] = 'SurveiController/add_saran/$1/$2';
$route['survei/(:any)/form-konfirmasi/(:any)'] = 'SurveiController/form_konfirmasi/$1/$2';
$route['survei/(:any)/add-konfirmasi/(:any)'] = 'SurveiController/add_konfirmasi/$1/$2';
$route['survei/(:any)/selesai/(:any)'] = 'SurveiController/form_closing/$1//$2';



//EDIT SURVEI
$route['survei/(:any)/data-responden/(:any)/edit'] = 'SurveiController/edit_data_responden/$1/$2';
$route['survei/(:any)/data-responden/(:any)/update'] = 'SurveiController/update_data_responden/$1/$2';
$route['survei/(:any)/pertanyaan/(:any)/edit'] = 'SurveiController/data_pertanyaan/$1/$2';
$route['survei/(:any)/pertanyaan-harapan/(:any)/edit'] = 'SurveiController/data_pertanyaan_harapan/$1/$2';
$route['survei/(:any)/pertanyaan-kualitatif/(:any)/edit'] = 'SurveiController/pertanyaan_kualitatif/$1/$2';
$route['survei/(:any)/saran/(:any)/edit'] = 'SurveiController/saran/$1/$2';
$route['survei/(:any)/form-konfirmasi/(:any)/edit'] = 'SurveiController/form_konfirmasi/$1//$2';


$route['survey/(:any)/unopened'] = 'SurveiController/unopened/$1/';
$route['survey/(:any)/survey-end'] = 'SurveiController/survey_end/$1/';
$route['survey/(:any)/survey-hold'] = 'SurveiController/survey_hold/$1/';
$route['survey/(:any)/survey-not-question'] = 'SurveiController/survey_not_question/$1/';
$route['survei/(:any)/download-link'] = 'SurveiController/download_link/$1';

$route['survey/petunjuk-pengisian-survey'] = 'SurveiController/petunjuk_pengisian_survey';
$route['survey/faq'] = 'SurveiController/faq';
$route['survey/kontak-kami'] = 'SurveiController/kontak_kami';

//SURVEI SURVEYOR
$route['survei/(:any)/(:any)'] = 'SurveiController/form_opening/$1/$2';
$route['survei/(:any)/data-responden/(:any)'] = 'SurveiController/data_responden/$1/$2';
$route['survei/(:any)/add-data-responden/(:any)'] = 'SurveiController/add_data_responden/$1/$';


// DRAF KUESIONER
// $route['(:any)/(:any)/draf-kuesioner'] = 'DrafKuesionerController/index/$1/$2';
$route['(:any)/(:any)/draf-kuesioner'] = 'DrafKuesionerController/tcpdf/$1/$2';


$route['reporting'] = 'Reporting';
$route['(:any)/(:any)/reporting'] = 'Reporting/index/$1/$2';
$route['welcome'] = 'Welcome/index';


// KLASIFIKASI SURVEY
$route['klasifikasi-survei'] = 'KlasifikasiSurveyController/index';
$route['klasifikasi-survei/ajax-list'] = 'KlasifikasiSurveyController/ajax_list';
$route['klasifikasi-survei/add'] = 'KlasifikasiSurveyController/add';
$route['klasifikasi-survei/edit/(:num)'] = 'KlasifikasiSurveyController/edit/$1';
$route['klasifikasi-survei/delete/(:num)'] = 'KlasifikasiSurveyController/delete/$1';

// JENIS PELAYANAN
$route['jenis-pelayanan'] = 'JenisPelayananController/index';
$route['jenis-pelayanan/ajax-list'] = 'JenisPelayananController/ajax_list';
$route['jenis-pelayanan/list/(:num)'] = 'JenisPelayananController/list_jenis_pelayanan/$1';
$route['jenis-pelayanan/ajax-list-jenis-pelayanan'] = 'JenisPelayananController/ajax_list_jenis_pelayanan';
$route['jenis-pelayanan/add/(:num)'] = 'JenisPelayananController/add/$1';
$route['jenis-pelayanan/edit/(:num)/(:num)'] = 'JenisPelayananController/edit/$1/$2';
$route['jenis-pelayanan/delete/(:num)'] = 'JenisPelayananController/delete/$1';

// UNSUR PELAYANAN
$route['unsur-pelayanan'] = 'UnsurPelayananController/index';
$route['unsur-pelayanan/ajax-list'] = 'UnsurPelayananController/ajax_list';
$route['unsur-pelayanan/add'] = 'UnsurPelayananController/add';
$route['unsur-pelayanan/edit/(:num)'] = 'UnsurPelayananController/edit/$1';
$route['unsur-pelayanan/delete/(:num)'] = 'UnsurPelayananController/delete/$1';

// PERTANYAAN TERBUKA
$route['pertanyaan-terbuka'] = 'PertanyaanTerbukaController/index';
$route['pertanyaan-terbuka/ajax-list'] = 'PertanyaanTerbukaController/ajax_list';
$route['pertanyaan-terbuka/add'] = 'PertanyaanTerbukaController/add';
$route['pertanyaan-terbuka/edit/(:num)'] = 'PertanyaanTerbukaController/edit/$1';
$route['pertanyaan-terbuka/delete/(:num)'] = 'PertanyaanTerbukaController/delete/$1';

// PERTANYAAN UNSUR PELAYANAN
$route['pertanyaan-unsur-pelayanan'] = 'PertanyaanUnsurPelayananController/index';
$route['pertanyaan-unsur-pelayanan/ajax-list'] = 'PertanyaanUnsurPelayananController/ajax_list';

$route['pertanyaan-unsur-pelayanan/jenis-pelayanan/(:num)'] = 'PertanyaanUnsurPelayananController/list_jenis_pelayanan/$1';
$route['pertanyaan-unsur-pelayanan/ajax-list-jenis-pelayanan'] = 'PertanyaanUnsurPelayananController/ajax_list_jenis_pelayanan';

$route['pertanyaan-unsur-pelayanan/list-unsur-pelayanan/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/list_unsur_pelayanan/$1/$2';
$route['pertanyaan-unsur-pelayanan/ajax-list-unsur-pelayanan'] = 'PertanyaanUnsurPelayananController/ajax_list_unsur_pelayanan';
$route['pertanyaan-unsur-pelayanan/add-unsur/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/add_unsur/$1/$2';
$route['pertanyaan-unsur-pelayanan/edit-unsur/(:num)'] = 'PertanyaanUnsurPelayananController/edit_unsur/$1';
$route['pertanyaan-unsur-pelayanan/delete-unsur/(:num)'] = 'PertanyaanUnsurPelayananController/delete_unsur/$1';

$route['pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-unsur-pelayanan'] = 'PertanyaanUnsurPelayananController/ajax_list_pertanyaan_unsur_pelayanan';
$route['pertanyaan-unsur-pelayanan/add/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/add/$1/$2';
$route['pertanyaan-unsur-pelayanan/edit/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/edit/$1/$2';
$route['pertanyaan-unsur-pelayanan/delete/(:num)'] = 'PertanyaanUnsurPelayananController/delete/$1';

$route['pertanyaan-unsur-pelayanan/preview/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/preview_hasil/$1/$2';
$route['pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-harapan'] = 'PertanyaanUnsurPelayananController/ajax_list_pertanyaan_harapan';
$route['pertanyaan-unsur-pelayanan/ajax-list-pertanyaan-tambahan'] = 'PertanyaanUnsurPelayananController/ajax_list_pertanyaan_tambahan';

$route['pertanyaan-unsur-pelayanan/add-pertanyaan-tambahan/(:num)/(:num)'] = 'PertanyaanUnsurPelayananController/add_pertanyaan_tambahan/$1/$2';
$route['pertanyaan-unsur-pelayanan/edit-pertanyaan-tambahan/(:num)'] = 'PertanyaanUnsurPelayananController/edit_pertanyaan_tambahan/$1';
$route['pertanyaan-unsur-pelayanan/delete-pertanyaan-tambahan/(:num)'] = 'PertanyaanUnsurPelayananController/delete_pertanyaan_tambahan/$1';



//PERTANYAAN UNSUR SURVEY
$route['(:any)/(:any)/pertanyaan-survey'] = 'PertanyaanSurveyController/index/$1/$2';
$route['(:any)/(:any)/pertanyaan-survey/ajax-list'] = 'PertanyaanSurveyController/ajax_list/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur-survey/add'] = 'PertanyaanSurveyController/add_unsur/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur-survey/edit/(:num)'] = 'PertanyaanSurveyController/edit_unsur/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur-survey/delete/(:num)'] = 'PertanyaanSurveyController/delete_unsur/$1/$2';

//PERTANYAAN TERBUKA SURVEY
$route['(:any)/(:any)/pertanyaan-survey/ajax-list-pertanyaan-terbuka-survei'] = 'PertanyaanSurveyController/ajax_list_pertanyaan_terbuka_survei/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka-survey/add'] = 'PertanyaanSurveyController/add_terbuka/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka-survey/edit/(:num)'] = 'PertanyaanSurveyController/edit_terbuka/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka-survey/delete/(:num)'] = 'PertanyaanSurveyController/delete_terbuka/$1/$2';

//PERTANYAAN KUALITATIF SURVEY
$route['(:any)/(:any)/pertanyaan-survey/ajax-list-pertanyaan-kualitatif-survei'] = 'PertanyaanSurveyController/ajax_list_pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif-survey/add'] = 'PertanyaanSurveyController/add_kualitatif/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif-survey/edit/(:num)'] = 'PertanyaanSurveyController/edit_kualitatif/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif-survey/delete/(:num)'] = 'PertanyaanSurveyController/delete_kualitatif/$1/$2';


//PERTANYAAN HARAPAN SURVEY
$route['(:any)/(:any)/pertanyaan-survey/ajax-list-pertanyaan-harapan-survei'] = 'PertanyaanSurveyController/ajax_list_pertanyaan_harapan_survei/$1/$2';
$route['(:any)/(:any)/pertanyaan-harapan-survey/edit'] = 'PertanyaanSurveyController/edit_harapan/$1/$2';



// PERTANYAAN HARAPAN
$route['pertanyaan-harapan'] = 'PertanyaanHarapanController/index';
$route['pertanyaan-harapan/ajax-list'] = 'PertanyaanHarapanController/ajax_list';
// $route['pertanyaan-unsur-pelayanan/add'] = 'PertanyaanUnsurPelayananController/add';
// $route['pertanyaan-unsur-pelayanan/edit/(:num)'] = 'PertanyaanUnsurPelayananController/edit/$1';
// $route['pertanyaan-unsur-pelayanan/delete/(:num)'] = 'PertanyaanUnsurPelayananController/delete/$1';

// PERINCIAN PERTANYAAN TERBUKA
$route['perincian-pertanyaan-terbuka'] = 'PerincianPertanyaanTerbukaController/index';
$route['perincian-pertanyaan-terbuka/ajax-list'] = 'PerincianPertanyaanTerbukaController/ajax_list';
$route['perincian-pertanyaan-terbuka/add'] = 'PerincianPertanyaanTerbukaController/add';
$route['perincian-pertanyaan-terbuka/edit/(:num)'] = 'PerincianPertanyaanTerbukaController/edit/$1';
$route['perincian-pertanyaan-terbuka/delete/(:num)'] = 'PerincianPertanyaanTerbukaController/delete/$1';

// PROFIL RESPONDEN KUESIONER
$route['profil-responden-kuesioner'] = 'ProfilRespondenKuesionerController/index';
$route['profil-responden-kuesioner/ajax-list'] = 'ProfilRespondenKuesionerController/ajax_list';
$route['profil-responden-kuesioner/add'] = 'ProfilRespondenKuesionerController/add';
$route['profil-responden-kuesioner/detail/(:num)'] = 'ProfilRespondenKuesionerController/detail/$1';
$route['profil-responden-kuesioner/delete/(:num)'] = 'ProfilRespondenKuesionerController/delete/$1';

// LINK SURVEY SURVEYOR
// $route['link-survey-surveyor/(:any)/(:any)'] = 'LinkSurveySurveyorController/index/$1/$2';
// $route['link-survey-surveyor/data-responden/(:any)/(:any)'] = 'LinkSurveySurveyorController/data_responden/$1/$2';

// -- SURVEYOR --

// DATA PEROLEHAN SURVEYOR
$route['link-per-surveyor'] = 'LinkPerSurveyorController/proses';

$route['data-perolehan-surveyor'] = 'DataPerolehanSurveyorController/index';
$route['data-perolehan-surveyor/ajax-list'] = 'DataPerolehanSurveyorController/ajax_list';
$route['data-perolehan-surveyor/delete/(:num)'] = 'DataPerolehanSurveyorController/delete/$1';

// JENIS PELAYANAN
$route['prospek-surveyor'] = 'ProspekSurveyorController/index';
$route['prospek-surveyor/ajax-list'] = 'ProspekSurveyorController/ajax_list';
$route['prospek-surveyor/add'] = 'ProspekSurveyorController/add';
$route['prospek-surveyor/edit/(:num)'] = 'ProspekSurveyorController/edit/$1';
$route['prospek-surveyor/delete/(:num)'] = 'ProspekSurveyorController/delete/$1';
$route['prospek-surveyor/get-email'] = 'ProspekSurveyorController/get_email';

// PENGGUNA RESELLER
$route['pengguna-reseller/detail'] = 'PenggunaResellerController/get_detail';

$route['pengguna-reseller'] = 'PenggunaResellerController/index';
$route['pengguna-reseller/ajax-list'] = 'PenggunaResellerController/ajax_list';
$route['pengguna-reseller/add'] = 'PenggunaResellerController/add';
$route['pengguna-reseller/edit/(:num)'] = 'PenggunaResellerController/edit/$1';
$route['pengguna-reseller/delete/(:num)'] = 'PenggunaResellerController/delete/$1';

$route['(:any)/manage-survey'] = 'ManageSurveyController/index';
$route['(:any)/create-survey-client'] = 'ManageSurveyController/create_survey_client';
$route['(:any)/check-packet/(:any)'] = 'ManageSurveyController/check_packet/$1/$2';
$route['(:any)/info-berlangganan'] = 'ManageSurveyController/info_berlangganan';
$route['(:any)/info-berlangganan/data-berlangganan'] = 'ManageSurveyController/data_berlangganan';
$route['(:any)/info-berlangganan/data-terakhir-berlangganan'] = 'ManageSurveyController/data_terakhir_berlangganan';
$route['(:any)/info-berlangganan/get-invoice'] = 'ManageSurveyController/get_invoice';
$route['(:any)/manage-survey/create-survey/(:any)'] = 'ManageSurveyController/add/$1/$2';
$route['(:any)/manage-survey/save-survey/(:any)'] = 'ManageSurveyController/create/$1/$2';

$route['(:any)/organisasi'] = 'OrganisasiController/index';
$route['organisasi/ajax_list'] = 'OrganisasiController/ajax_list';

$route['template-pertanyaan/get-detail'] = 'TemplatePertanyaanController/get_detail';

// PROFILE
$route['profile']             = 'ProfileController/index';
$route['profile/update-profile']     = 'ProfileController/update_profile';
$route['profile/update-foto']             = 'ProfileController/update_foto';
// $route['profile-organisasi'] = 'ProfileController/index';
// $route['delete-foto-profile'] = 'ProfileController/delete_foto_profile';

// MANAGE USERS MANAGEMENT
$route['(:any)/users-management'] = 'UsersManagementController/index/$1';
$route['(:any)/users-management/ajax-list'] =  'UsersManagementController/ajax_list/$1';
$route['(:any)/users-management/list-users/(:any)'] = 'UsersManagementController/list_users/$1/$2';
$route['(:any)/users-management/ajax-list-users/(:any)'] = 'UsersManagementController/ajax_list_users/$1/$2';
$route['(:any)/users-management/list-users/(:any)/add'] = 'UsersManagementController/add_list_users/$1/$2';
$route['(:any)/users-management/list-users/(:any)/edit/(:num)'] = 'UsersManagementController/edit_list_users/$1/$2';
$route['(:any)/users-management/list-users/(:any)/delete/(:num)'] = 'UsersManagementController/delete_list_users/$1/$2';

$route['(:any)/ajax-list-division/(:any)'] =  'UsersManagementController/ajax_list_division/$1/$2';
$route['(:any)/add-division'] =  'UsersManagementController/add_division/$1';
$route['(:any)/edit-division'] =  'UsersManagementController/edit_division/$1';
$route['(:any)/delete-division/(:num)'] =  'UsersManagementController/delete_division/$1';

//RESELLER
$route['list-klien'] = 'ListKlienController/index';
$route['list-klien/ajax-list'] = 'ListKlienController/ajax_list';


//MANAGE SURVEI
$route['manage-survey/ajax-list'] = 'ManageSurveyController/ajax_list';
$route['(:any)/(:any)/link-survey'] = 'ManageSurveyController/link_survey/$1/$2';
$route['(:any)/(:any)/link-survey/update-link'] = 'ManageSurveyController/update_link/$1/$2';
$route['(:any)/(:any)/link-survey/update-link/do'] = 'ManageSurveyController/do_update_link/$1/$2';
$route['(:any)/(:any)/data-surveyor'] = 'DataSurveyorController/index/$1/$2';
$route['(:any)/(:any)/data-surveyor/ajax-list'] = 'DataSurveyorController/ajax_list/$1/$2';
$route['(:any)/(:any)/data-surveyor/add'] = 'DataSurveyorController/add_surveyor/$1/$2';
$route['(:any)/(:any)/data-surveyor/edit/(:num)'] = 'DataSurveyorController/edit_surveyor/$1/$2';
$route['(:any)/(:any)/data-surveyor/delete/(:num)'] = 'DataSurveyorController/delete_surveyor/$1/$2';
// $route['(:any)/(:any)/relasikan'] = 'ManageSurveyController/relasikan/$1/$2';

//PEROLEHAN SURVEYOR
$route['(:any)/(:any)/perolehan-surveyor'] = 'PerolehanSurveyorController/index/$1/$2';
$route['(:any)/(:any)/perolehan-surveyor/ajax-list'] = 'PerolehanSurveyorController/ajax_list/$1/$2';
$route['(:any)/(:any)/detail-perolehan/(:any)'] = 'PerolehanSurveyorController/detail_perolehan_surveyor/$1/$2/$3';
$route['(:any)/(:any)/detail-perolehan/ajax-list-detail/(:any)'] = 'PerolehanSurveyorController/ajax_list_detail/$1/$2/$3';
$route['perolehan-surveyor/get-email'] = 'PerolehanSurveyorController/get_email';
$route['(:any)/(:any)/perolehan-surveyor/delete/(:num)'] = 'PerolehanSurveyorController/delete/$1/$2';
// $route['perolehan-surveyor/get-send-email'] = 'PerolehanSurveyorController/get_send_email';

//UNSUR PELAYANAN SURVEI
$route['(:any)/(:any)/unsur-pelayanan-survey'] = 'UnsurPelayananSurveyController/index/$1/$2';
$route['(:any)/(:any)/unsur-pelayanan-survey/ajax-list'] = 'UnsurPelayananSurveyController/ajax_list/$1/$2';
$route['(:any)/(:any)/unsur-pelayanan-survey/add'] = 'UnsurPelayananSurveyController/add/$1/$2';
$route['(:any)/(:any)/unsur-pelayanan-survey/edit/(:num)'] = 'UnsurPelayananSurveyController/edit/$1/$2';
$route['(:any)/(:any)/unsur-pelayanan-survey/delete/(:num)'] = 'UnsurPelayananSurveyController/delete/$1/$2';

// PERTANYAAN KUALITATIF
$route['(:any)/(:any)/pertanyaan-kualitatif'] = 'PertanyaanKualitatifController/index/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif/ajax-list'] = 'PertanyaanKualitatifController/ajax_list/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif/add'] = 'PertanyaanKualitatifController/add_pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif/edit/(:num)'] = 'PertanyaanKualitatifController/edit_pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/pertanyaan-kualitatif/delete/(:num)'] = 'PertanyaanKualitatifController/delete_pertanyaan_kualitatif/$1/$2';

//FORM SURVEI
$route['(:any)/(:any)/form-survei'] = 'FormSurveiController/index/$1/$2';
$route['(:any)/(:any)/form-survei/update-saran'] = 'FormSurveiController/update_saran/$1/$2';
$route['(:any)/(:any)/form-survei/update-display'] = 'FormSurveiController/update_display/$1/$2';
$route['(:any)/(:any)/form-survei/update-header'] = 'FormSurveiController/update_header/$1/$2';
$route['(:any)/(:any)/form-survei/do-uploud'] = 'FormSurveiController/do_uploud/$1/$2';

$route['(:any)/(:any)/form-survei/opening'] = 'FormSurveiController/form_opening/$1';
$route['(:any)/(:any)/form-survei/data-responden'] = 'FormSurveiController/data_responden/$1/$2';
$route['(:any)/(:any)/form-survei/add-custom-data-responden'] = 'FormSurveiController/add_custom_data_responden/$1/$2';
$route['(:any)/(:any)/form-survei/edit-data-responden/(:num)'] = 'FormSurveiController/edit_data_responden/$1/$2';

$route['(:any)/(:any)/form-survei/pertanyaan'] = 'FormSurveiController/data_pertanyaan/$1/$2';
$route['(:any)/(:any)/form-survei/add-pertanyaan-unsur'] = 'FormSurveiController/add_pertanyaan_unsur/$1/$2';
$route['(:any)/(:any)/form-survei/add-pertanyaan-sub-unsur'] = 'FormSurveiController/add_pertanyaan_sub_unsur/$1/$2';
$route['(:any)/(:any)/form-survei/detail-edit-pertanyaan-unsur/(:num)'] = 'FormSurveiController/get_detail_edit_pertanyaan_unsur/$1/$2';
$route['(:any)/(:any)/form-survei/edit-pertanyaan-unsur/(:num)'] = 'FormSurveiController/edit_pertanyaan_unsur/$1/$2';

$route['(:any)/(:any)/form-survei/add-pertanyaan-tambahan'] = 'FormSurveiController/add_pertanyaan_tambahan/$1/$2';
$route['(:any)/(:any)/form-survei/detail-edit-pertanyaan-tambahan/(:num)'] = 'FormSurveiController/get_detail_edit_pertanyaan_tambahan/$1/$2';
$route['(:any)/(:any)/form-survei/edit-pertanyaan-tambahan/(:num)'] = 'FormSurveiController/edit_pertanyaan_tambahan/$1/$2';

$route['(:any)/(:any)/form-survei/pertanyaan-harapan'] = 'FormSurveiController/data_pertanyaan_harapan/$1/$2';
$route['(:any)/(:any)/form-survei/edit-pertanyaan-harapan'] = 'FormSurveiController/edit_pertanyaan_harapan/$1/$2';

$route['(:any)/(:any)/form-survei/pertanyaan-kualitatif'] = 'FormSurveiController/pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/form-survei/add-pertanyaan-kualitatif'] = 'FormSurveiController/add_pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/form-survei/detail-edit-pertanyaan-kualitatif/(:num)'] = 'FormSurveiController/get_detail_edit_pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/form-survei/edit-pertanyaan-kualitatif/(:num)'] = 'FormSurveiController/edit_pertanyaan_kualitatif/$1/$2';

$route['(:any)/(:any)/form-survei/saran'] = 'FormSurveiController/saran/$1/$2';
$route['(:any)/(:any)/form-survei/konfirmasi'] = 'FormSurveiController/form_konfirmasi/$1/$2';
$route['(:any)/(:any)/form-survei/selesai'] = 'FormSurveiController/form_closing/$1//$2';


//PREVIEW FORM SURVEI
$route['(:any)/(:any)/preview-form-survei/opening'] = 'PreviewFormSurveiController/form_opening/$1';
$route['(:any)/(:any)/preview-form-survei/data-responden'] = 'PreviewFormSurveiController/data_responden/$1/$2';
$route['(:any)/(:any)/preview-form-survei/pertanyaan'] = 'PreviewFormSurveiController/data_pertanyaan/$1/$2';
$route['(:any)/(:any)/preview-form-survei/pertanyaan-harapan'] = 'PreviewFormSurveiController/data_pertanyaan_harapan/$1/$2';
$route['(:any)/(:any)/preview-form-survei/pertanyaan-kualitatif'] = 'PreviewFormSurveiController/pertanyaan_kualitatif/$1/$2';
$route['(:any)/(:any)/preview-form-survei/saran'] = 'PreviewFormSurveiController/saran/$1/$2';
$route['(:any)/(:any)/preview-form-survei/konfirmasi'] = 'PreviewFormSurveiController/form_konfirmasi/$1/$2';
$route['(:any)/(:any)/preview-form-survei/selesai'] = 'PreviewFormSurveiController/form_closing/$1//$2';

//PERTANYAAN UNSUR SURVEI
$route['(:any)/(:any)/pertanyaan-unsur'] = 'PertanyaanUnsurSurveiController/index/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/ajax-list'] = 'PertanyaanUnsurSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/add'] = 'PertanyaanUnsurSurveiController/add/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/add-sub'] = 'PertanyaanUnsurSurveiController/add_sub/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/add-sub/(:num)'] = 'PertanyaanUnsurSurveiController/add_sub/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/edit/(:num)'] = 'PertanyaanUnsurSurveiController/edit/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/delete/(:num)'] = 'PertanyaanUnsurSurveiController/delete/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/detail-alur/(:num)'] = 'PertanyaanUnsurSurveiController/detail_alur/$1/$2';
$route['(:any)/(:any)/pertanyaan-unsur/update-detail-alur'] = 'PertanyaanUnsurSurveiController/update_detail_alur/$1/$2';

//PERTANYAAN HARAPAN SURVEI
$route['(:any)/(:any)/pertanyaan-harapan'] = 'PertanyaanHarapanSurveiController/index/$1/$2';
$route['(:any)/(:any)/pertanyaan-harapan/ajax-list'] = 'PertanyaanHarapanSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/pertanyaan-harapan/cari'] = 'PertanyaanHarapanSurveiController/cari/$1/$2';
$route['(:any)/(:any)/pertanyaan-harapan/edit'] = 'PertanyaanHarapanSurveiController/edit/$1/$2';
// $route['(:any)/(:any)/pertanyaan-harapan/delete/(:num)'] = 'PertanyaanHarapanUnsurSurveiController/delete/$1/$2';

//PERTANYAAN TERBUKA SURVEI
$route['(:any)/(:any)/pertanyaan-terbuka'] = 'PertanyaanTerbukaSurveiController/index/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/ajax-list'] = 'PertanyaanTerbukaSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/add/(:num)'] = 'PertanyaanTerbukaSurveiController/add/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/edit/(:num)'] = 'PertanyaanTerbukaSurveiController/edit/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/delete/(:num)'] = 'PertanyaanTerbukaSurveiController/delete/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/detail-alur/(:num)'] = 'PertanyaanTerbukaSurveiController/detail_alur/$1/$2';
$route['(:any)/(:any)/pertanyaan-terbuka/update-detail-alur'] = 'PertanyaanTerbukaSurveiController/update_detail_alur/$1/$2';


$route['(:any)/(:any)/olah-data'] = 'OlahDataController/index/$1/$2';
$route['(:any)/(:any)/olah-data/ajax-list'] = 'OlahDataController/ajax_list/$1/$2';
$route['(:any)/(:any)/visualisasi-data'] = 'VisualisasiController/proses/$1/$2';
$route['(:any)/(:any)/rekap-responden'] = 'RekapRespondenController/index/$1/$2';

// PROSPEK SURVEY
$route['data-prospek-survey/get-send-email'] = 'DataProspekSurveyController/get_send_email';
$route['(:any)/(:any)/data-prospek-survey'] = 'DataProspekSurveyController/index/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/ajax-list'] = 'DataProspekSurveyController/ajax_list/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/ajax-add'] = 'DataProspekSurveyController/ajax_add/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/ajax-edit/(:any)'] = 'DataProspekSurveyController/ajax_edit/$1/$2/$3';
$route['(:any)/(:any)/data-prospek-survey/ajax-update'] = 'DataProspekSurveyController/ajax_update/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/ajax-delete/(:any)'] = 'DataProspekSurveyController/ajax_delete/$1/$2/$3';
$route['(:any)/(:any)/data-prospek-survey/detail'] = 'DataProspekSurveyController/detail/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/bagikan-email'] = 'DataProspekSurveyController/bagikan_email/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/bagikan-whatsapp'] = 'DataProspekSurveyController/bagikan_whatsapp/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/import'] = 'DataProspekSurveyController/import/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/preview'] = 'DataProspekSurveyController/preview/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/proses'] = 'DataProspekSurveyController/proses/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/truncate'] = 'DataProspekSurveyController/truncate/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/cancel-import'] = 'DataProspekSurveyController/cancel_import/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/download-template'] = 'DataProspekSurveyController/download_template/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/update-email-prospek'] = 'DataProspekSurveyController/update_email_prospek/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/delete-attachment'] = 'DataProspekSurveyController/delete_attachment/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/update-email-footer-prospek'] = 'DataProspekSurveyController/update_email_footer_prospek/$1/$2';
$route['(:any)/(:any)/data-prospek-survey/delete-logo'] = 'DataProspekSurveyController/delete_logo/$1/$2';

// KUADRAN
$route['(:any)/(:any)/kuadran'] = 'KuadranController/index/$1/$2';
$route['(:any)/(:any)/kuadran/convert'] = 'KuadranController/convert_kuadran/$1/$2';

//REKAP ALASAN
$route['(:any)/(:any)/alasan'] = 'AlasanController/index/$1/$2';
$route['(:any)/(:any)/alasan/ajax-list'] = 'AlasanController/ajax_list/$1/$2';
$route['(:any)/(:any)/alasan/detail/(:num)'] = 'AlasanController/detail/$1/$2';
$route['(:any)/(:any)/alasan/ajax-list-detail/(:num)'] = 'AlasanController/ajax_list_detail/$1/$2';
$route['(:any)/(:any)/alasan/cetak'] = 'AlasanController/cetak/$1/$2';
$route['(:any)/(:any)/alasan/download-docx'] = 'AlasanController/download_docx/$1/$2';

// REKAP PERTANYAAN TAMBAHAN
$route['(:any)/(:any)/rekapitulasi-pertanyaan-tambahan'] = 'RekapitulasiPertanyaanTambahanController/index/$1/$2';
$route['(:any)/(:any)/rekapitulasi-pertanyaan-tambahan/download-docx'] = 'RekapitulasiPertanyaanTambahanController/download_docx/$1/$2';

// REKAP PERTANYAAN HARAPAN
$route['(:any)/(:any)/rekap-pertanyaan-harapan'] = 'RekapPertanyaanHarapanController/index/$1/$2';
$route['(:any)/(:any)/rekap-pertanyaan-harapan/ajax-list'] = 'RekapPertanyaanHarapanController/ajax_list/$1/$2';
$route['(:any)/(:any)/rekap-pertanyaan-harapan/(:num)'] = 'RekapPertanyaanHarapanController/detail/$1/$2';
$route['(:any)/(:any)/rekap-pertanyaan-harapan/ajax-list-detail/(:num)'] = 'RekapPertanyaanHarapanController/ajax_list_detail/$1/$2';
$route['(:any)/(:any)/rekap-pertanyaan-harapan/cetak'] = 'RekapPertanyaanHarapanController/cetak/$1/$2';
$route['(:any)/(:any)/rekap-pertanyaan-harapan/download-docx'] = 'RekapPertanyaanHarapanController/download_docx/$1/$2';

//REKAP INOVASI DAN SARAN
$route['(:any)/(:any)/inovasi-dan-saran'] = 'InovasiSaranController/index/$1/$2';
$route['(:any)/(:any)/inovasi-dan-saran/ajax-list'] = 'InovasiSaranController/ajax_list/$1/$2';
$route['(:any)/(:any)/inovasi-dan-saran/cetak'] = 'InovasiSaranController/cetak/$1/$2';
$route['(:any)/(:any)/inovasi-dan-saran/download-docx'] = 'InovasiSaranController/download_docx/$1/$2';

//LAPORAN
$route['(:any)/(:any)/laporan-survey'] = 'LaporanSurveyController/index/$1/$2';
$route['(:any)/(:any)/laporan-survey/insert'] = 'LaporanSurveyController/insert/$1/$2';
$route['(:any)/(:any)/laporan-survey/delete-profil'] = 'LaporanSurveyController/delete_profil/$1/$2';
$route['(:any)/(:any)/laporan-survey/download-profil'] = 'LaporanSurveyController/download_profil/$1/$2';

$route['(:any)/(:any)/laporan-survey/get'] = 'LaporanSurveyController/get/$1/$2';
$route['(:any)/(:any)/laporan-survey/cetak'] = 'LaporanSurveyController/cetak/$1/$2';

$route['(:any)/(:any)/laporan-survey/insert-struktur'] = 'LaporanSurveyController/insert_struktur/$1/$2';
$route['(:any)/(:any)/laporan-survey/delete-struktur'] = 'LaporanSurveyController/delete_struktur/$1/$2';
$route['(:any)/(:any)/laporan-survey/download-struktur'] = 'LaporanSurveyController/download_struktur/$1/$2';

$route['(:any)/(:any)/laporan-survey/download-laporan'] = 'LaporanSurveyController/download_laporan/$1/$2';
$route['(:any)/(:any)/laporan-survey/chart'] = 'LaporanSurveyController/chart/$1/$2';

$route['(:any)/(:any)/laporan-survey/download'] = 'ReportController/download/$1/$2';
$route['(:any)/(:any)/laporan-survey/download-docx'] = 'ReportController/download_docx/$1/$2';

// SCAN BARCODE
$route['(:any)/(:any)/scan-barcode'] = 'ScanBarcodeController/index/$1/$2';
$route['(:any)/(:any)/scan-barcode/do'] = 'ScanBarcodeController/process/$1/$2';
$route['(:any)/(:any)/scan-barcode/get'] = 'ScanBarcodeController/create_qrcode/$1/$2';
$route['(:any)/(:any)/scan-barcode/download'] = 'ScanBarcodeController/download/$1/$2';
$route['(:any)/(:any)/scan-barcode/clear-data'] = 'ScanBarcodeController/clear_data/$1/$2';

//REKAP JAWABAN KUALITATIF
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif'] = 'JawabanKualitatifController/index/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/ajax-list'] = 'JawabanKualitatifController/ajax_list/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/detail/(:num)'] = 'JawabanKualitatifController/detail/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/ajax-list-detail/(:num)'] = 'JawabanKualitatifController/ajax_list_detail/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/cetak'] = 'JawabanKualitatifController/cetak/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/edit/(:num)'] = 'JawabanKualitatifController/edit/$1/$2';
$route['(:any)/(:any)/jawaban-pertanyaan-kualitatif/download-docx'] = 'JawabanKualitatifController/download_docx/$1/$2';

// SELECT DROPDOWN
$route['get-menu'] = 'SelectDropdownController/getMenu';

//PEROLEHAN SURVEI
$route['(:any)/(:any)/data-perolehan-survei'] = 'DataPerolehanSurveiController/index/$1/$2';
$route['(:any)/(:any)/data-perolehan-survei/ajax-list'] = 'DataPerolehanSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/data-perolehan-survei/delete/(:num)'] = 'DataPerolehanSurveiController/delete/$1/$2';
$route['(:any)/(:any)/data-perolehan-survei/export'] = 'DataPerolehanSurveiController/export/$1/$2';
$route['(:any)/(:any)/data-perolehan-survei/export-all-pdf'] = 'DataPerolehanSurveiController/export_all_pdf/$1/$2';
$route['(:any)/(:any)/data-perolehan-survei/delete-by-checkbox'] = 'DataPerolehanSurveiController/delete_by_checkbox/$1/$2';

$route['(:any)/(:any)/e-sertifikat'] = 'SertifikatController/proses/$1/$2';
$route['(:any)/(:any)/e-sertifikat/cetak'] = 'SertifikatController/cetak/$1/$2';
$route['(:any)/(:any)/update-publikasi'] = 'SertifikatController/update_publikasi/$1/$2';
$route['(:any)/(:any)/template-pertanyaan'] = 'TemplatePertanyaanController/proses/$1/$2';

$route['(:any)/(:any)/(:any)/update_info'] = 'ManageSurveyController/update_info/$1/$2/$3';
$route['(:any)/(:any)/(:any)/update_logo'] = 'ManageSurveyController/update_logo/$1/$2/$3';


$route['(:any)/(:any)/update-publikasi-link-survei'] = 'ManageSurveyController/update_publikasi_link_survei/$1/$2';
$route['(:any)/(:any)/confirm-question'] = 'ManageSurveyController/confirm_question/$1/$2';
$route['(:any)/(:any)/do/change-privacy'] = 'ManageSurveyController/ubah_privasi/$1/$2';
$route['(:any)/(:any)/do/change-privacy/update'] = 'ManageSurveyController/update_privasi/$1/$2';

// SETTINGS
$route['(:any)/(:any)/settings-question'] = 'SettingSurveiController/settings_question/$1/$2';
$route['(:any)/(:any)/settings'] = 'SettingSurveiController/setting_general/$1/$2';
$route['(:any)/(:any)/setting-pertanyaan'] = 'SettingSurveiController/setting_pertanyaan/$1/$2';
$route['(:any)/(:any)/setting-survei/update-saran'] = 'SettingSurveiController/update_saran/$1/$2';
$route['(:any)/(:any)/setting-survei/update-display'] = 'SettingSurveiController/update_display/$1/$2';
$route['(:any)/(:any)/setting-survei/update-header'] = 'SettingSurveiController/update_header/$1/$2';
$route['(:any)/(:any)/settings/display'] = 'SettingSurveiController/display/$1/$2';
$route['(:any)/(:any)/settings/survey'] = 'SettingSurveiController/index/$1/$2';
$route['(:any)/(:any)/setting-survei/periode'] = 'SettingSurveiController/periode/$1/$2';
$route['(:any)/(:any)/setting-survei/tunda'] = 'SettingSurveiController/tunda/$1/$2';

$route['(:any)/(:any)/do'] = 'ManageSurveyController/repository/$1/$2';

$route['(:any)/overview'] = 'ManageSurveyController/profile/$1';
$route['(:any)/overview/list-survey'] = 'ManageSurveyController/get_data_survey/$1';
$route['(:any)/overview/list-activity'] = 'ManageSurveyController/get_data_activity/$1';
$route['(:any)/overview/list-campaign'] = 'ManageSurveyController/get_data_paket/$1';
$route['(:any)/overview/detail-packet'] = 'ManageSurveyController/get_detail_packet/$1';
$route['(:any)/overview/detail-survey'] = 'ManageSurveyController/get_detail_survey/$1';
$route['manage-survey/delete/(:any)'] = 'ManageSurveyController/delete_survey/$1';
$route['(:any)/(:any)/delete'] = 'ManageSurveyController/delete/$1/$2';
// $route['(:any)/(:any)'] = 'ManageSurveyController/repository/$1/$2';
$route['(:any)/(:any)/update-repository'] = 'ManageSurveyController/update_repository/$1/$2';
$route['(:any)'] = 'ManageSurveyController/profile/$1';
$route['(:any)/(:any)/do/(:any)'] = 'ManageSurveyController/draf_inject_survei/$1/$2';

// $route['(:any)/hasil-survei/(:any)'] = 'HasilSurveiController/index/$1';
$route['(:any)/hasil-survei/(:any)'] = 'HasilSurveiController/tcpdf/$1';

//PROFIL RESPONDEN SURVEI
$route['(:any)/(:any)/profil-responden-survei'] = 'ProfilRespondenSurveiController/index/$1/$2';
$route['(:any)/(:any)/profil-responden-survei/ajax-list'] = 'ProfilRespondenSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/profil-responden-survei/add-default'] = 'ProfilRespondenSurveiController/add_default/$1/$2';
$route['(:any)/(:any)/profil-responden-survei/add-custom'] = 'ProfilRespondenSurveiController/add_custom/$1/$2';
$route['(:any)/(:any)/profil-responden-survei/edit/(:num)'] = 'ProfilRespondenSurveiController/edit/$1/$2';
$route['(:any)/(:any)/profil-responden-survei/delete/(:num)'] = 'ProfilRespondenSurveiController/delete/$1/$2';

//ANALISA-SURVEI SURVEI
$route['(:any)/(:any)/analisa-survei'] = 'AnalisaSurveiController/index/$1/$2';
$route['(:any)/(:any)/analisa-survei/ajax-list'] = 'AnalisaSurveiController/ajax_list/$1/$2';
$route['(:any)/(:any)/analisa-survei/add'] = 'AnalisaSurveiController/add/$1/$2';
$route['(:any)/(:any)/analisa-survei/edit/(:num)'] = 'AnalisaSurveiController/edit/$1/$2';
$route['(:any)/(:any)/analisa-survei/delete/(:num)'] = 'AnalisaSurveiController/delete/$1/$2';
$route['(:any)/(:any)/update-executive-summary'] = 'AnalisaSurveiController/update_executive_summary/$1/$2';
$route['(:any)/(:any)/analisa-survei/detail-unsur/(:any)'] = 'AnalisaSurveiController/detail_unsur/$1/$2/$3';
$route['(:any)/(:any)/analisa-survei/ajax-list-analisa/(:any)'] = 'AnalisaSurveiController/ajax_list_analisa/$1/$2/$3';
$route['(:any)/(:any)/analisa-survei/ajax-list-analisa-harapan/(:any)'] = 'AnalisaSurveiController/ajax_list_analisa_harapan/$1/$2/$3';
$route['(:any)/(:any)/analisa-survei/tambah-analisa/(:any)'] = 'AnalisaSurveiController/tambah_analisa/$1/$2/$3';
$route['(:any)/(:any)/analisa-survei/update-analisa/(:any)'] = 'AnalisaSurveiController/update_analisa/$1/$2/$3';


//PENAYANGAN SURVEI
$route['(:any)/penayang-survei'] = 'PenayangSurveiController/index/$1';
$route['(:any)/penayang-survei/ajax-list'] = 'PenayangSurveiController/ajax_list/$1';
$route['(:any)/penayang-survei/add'] = 'PenayangSurveiController/add/$1';
$route['(:any)/penayang-survei/edit/(:num)'] = 'PenayangSurveiController/edit/$1/$2';
$route['(:any)/penayang-survei/delete/(:num)'] = 'PenayangSurveiController/delete/$1/$2';


$route['survei-list/(:any)'] = 'SurveiListController/index/$1';