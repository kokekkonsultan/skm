<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		// mkdir('./assets/tes');


		$no = 1;
		foreach($this->db->query("SELECT *
		FROM responden_cst1968
		JOIN survey_cst1968 ON responden_cst1968.id = survey_cst1968.id_responden
		WHERE is_submit = 1")->result() as $i => $row){


		foreach($this->db->query("SELECT * FROM jawaban_pertanyaan_terbuka_cst1968 WHERE id_responden = $row->id_responden")->result() as $val){

			$array1[$i][] = '<td>' . $val->jawaban . '</td>
						<td>' . $val->jawaban_lainnya . '</td>';
		}

			$html[] = '<tr>
							<td>' .$no++ . '</td>
							<td>' . $row->nama_lengkap . '</td>
							' . implode("", $array1[$i]) . '
						</tr>';
			
		}

		echo '<table>
			<tr>
				<td>No</td>
				<td>Nama</td>
				<td>T1</td>
				<td>T1</td>
				<td>T2</td>
				<td>T2 Lainnya</td>
				<td>T3</td>
				<td>T3</td>
				<td>T4</td>
				<td>T4</td>
				<td>T5</td>
				<td>T5</td>
				<td>T6</td>
				<td>T6</td>
			</tr>
			' . implode("", $html) . '
			</table>';




		// return view('welcome_message');
		// $this->load->view('welcome_message');
	}
}