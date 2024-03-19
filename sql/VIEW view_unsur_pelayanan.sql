DROP VIEW view_unsur_pelayanan

----------

CREATE VIEW view_unsur_pelayanan AS
select unsur_pelayanan.id AS id_unsur_pelayanan,unsur_pelayanan.nama_unsur_pelayanan AS nama_unsur_pelayanan,unsur_pelayanan.nomor_unsur AS nomor_unsur,jenis_pelayanan.nama_jenis_pelayanan_responden AS nama_jenis_pelayanan_responden,klasifikasi_survei.nama_klasifikasi_survei AS nama_klasifikasi_survei,unsur_pelayanan.id_parent AS id_parent,
(select up.nama_unsur_pelayanan from unsur_pelayanan up where unsur_pelayanan.id_parent = up.id) AS nama_unsur_relasi,
(select up.nomor_unsur from unsur_pelayanan up where unsur_pelayanan.id_parent = up.id) AS nomor_unsur_relasi
from ((unsur_pelayanan join jenis_pelayanan on(jenis_pelayanan.id = unsur_pelayanan.id_jenis_pelayanan)) join klasifikasi_survei on(klasifikasi_survei.id = jenis_pelayanan.id_klasifikasi_survei))