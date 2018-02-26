<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_pendapatan_new extends CI_Model
{

    public function get_pendapatan_parent1($tahun){
        $query = $this->db->query("
        select
        id1,kode1,nama_parent1,
        sum(realisasi_n_3) as realisasi_n_3,
        sum(realisasi_n_2) as realisasi_n_2,
        sum(realisasi_n_1) as realisasi_n_1,
        sum(anggaran) as anggaran,
        sum(proyeksi) as proyeksi
        from (
        select
        	p.id,
        	j.id as id_jenis_pendapatan,".$tahun." as tahun,
        	j.parent,
        	j.depth,
        	j.weight,
        	j.kode,
        	j.nama,
        	j.kode2,
        	j.id2,
        	j.parent2,
        	j.nama_parent2,
        	j.kode1,
        	j.id1,
        	j.parent1,
        	j.nama_parent1,
        	p.realisasi_n_3,
        	p.realisasi_n_2,
        	p.realisasi_n_1,
        	p.anggaran,p.proyeksi
        from (
        	select j.*,j1.id as id1,j1.parent as parent1,j1.nama as nama_parent1,j1.kode as kode1 from (
        	select j.*,j2.id as id2,j2.parent as parent2,j2.nama as nama_parent2,j2.kode as kode2 from (
        		select * from m_jenis_pendapatan where depth = 3 order by parent asc,weight asc
        		) j
        		left join m_jenis_pendapatan j2
        		on j.parent = j2.id
        	) j left join m_jenis_pendapatan j1
        	on j.parent2 = j1.id
        ) j
        left join
        	t_pendapatan_new p
        on j.id = p.id_jenis_pendapatan
        and p.tahun = ".$tahun."
        ) p group by id1
        ");
        return $query->result();
    }

    public function get_pendapatan_parent2($tahun,$id){
        $query = $this->db->query("
        select
            id2,kode2,nama_parent2,
            sum(realisasi_n_3) as realisasi_n_3,
            sum(realisasi_n_2) as realisasi_n_2,
            sum(realisasi_n_1) as realisasi_n_1,
            sum(anggaran) as anggaran,
            sum(proyeksi) as proyeksi
            from (
            select
            	p.id,
            	j.id as id_jenis_pendapatan,".$tahun." as tahun,
            	j.parent,
            	j.depth,
            	j.weight,
            	j.kode,
            	j.nama,
            	j.kode2,
            	j.id2,
            	j.parent2,
            	j.nama_parent2,
            	j.kode1,
            	j.id1,
            	j.parent1,
            	j.nama_parent1,
            	p.realisasi_n_3,
            	p.realisasi_n_2,
            	p.realisasi_n_1,
            	p.anggaran,p.proyeksi
            from (
            	select j.*,j1.id as id1,j1.parent as parent1,j1.nama as nama_parent1,j1.kode as kode1 from (
            	select j.*,j2.id as id2,j2.parent as parent2,j2.nama as nama_parent2,j2.kode as kode2 from (
            		select * from m_jenis_pendapatan where depth = 3 order by parent asc,weight asc
            		) j
            		left join m_jenis_pendapatan j2
            		on j.parent = j2.id
            	) j left join m_jenis_pendapatan j1
            	on j.parent2 = j1.id
            ) j
            left join
            	t_pendapatan_new p
            on j.id = p.id_jenis_pendapatan
            and p.tahun = ".$tahun."

            ) p
        where parent2 = ".$id."
        group by id2
        ");
        return $query->result();
    }

    public function get_pendapatan_child($tahun,$id){
        $query = $this->db->query("
            select * from (
        		select
        			p.id,
        			j.id as id_jenis_pendapatan,".$tahun." as tahun,
        			j.parent,
        			j.depth,
        			j.weight,
        			j.kode,
        			j.nama,
        			p.realisasi_n_3,
        			p.realisasi_n_2,
        			p.realisasi_n_1,
        			p.anggaran,
                    p.proyeksi
        		from (
        			select * from m_jenis_pendapatan where depth = 3 order by parent asc,weight asc
        		) j
        		left join
        			t_pendapatan_new p
        		on j.id = p.id_jenis_pendapatan
        		and p.tahun = ".$tahun."
                ) p
    		where parent = ".$id."
        ");
        return $query->result();
    }

    public function get_pendapatan_row($tahun,$id){
        $query = $this->db->query("
            select * from (
        		select
        			p.id,
        			j.id as id_jenis_pendapatan,".$tahun." as tahun,
        			j.parent,
        			j.depth,
        			j.weight,
        			j.kode,
        			j.nama,
        			p.realisasi_n_3,
        			p.realisasi_n_2,
        			p.realisasi_n_1,
        			p.anggaran,
                    p.proyeksi
        		from (
        			select * from m_jenis_pendapatan where depth = 3 order by parent asc,weight asc
        		) j
        		left join
        			t_pendapatan_new p
        		on j.id = p.id_jenis_pendapatan
        		and p.tahun = ".$tahun."
                ) p
    		where id_jenis_pendapatan = ".$id."
        ");
        return $query->row();
    }

    public function generate_table_pendapatan($tahun){
        $html = "";
        $data_parent = $this->get_pendapatan_parent1($tahun);
        foreach ($data_parent as $data) {
            //var_dump($data);
            $d = "<tr bgcolor=\"#00FF33\"><td>";
            $d .= $data->kode1;
            $d .= "</td><td>";
            $d .= $data->nama_parent1;
            $d .= "</td><td>";
            $d .= $data->realisasi_n_3;
            $d .= "</td><td>";
            $d .= $data->realisasi_n_2;
            $d .= "</td><td>";
            $d .= $data->realisasi_n_1;
            $d .= "</td><td>";
            $d .= $data->anggaran;
            $d .= "</td><td>";
            $d .= $data->proyeksi;
            $d .= "</td><td>";
            $d .= "</td></tr>";
            $html .= $d;

            $data_parent2 =  $this->get_pendapatan_parent2($tahun,$data->id1);
            foreach ($data_parent2 as $data2) {
                # code...
                $d2 = "<tr bgcolor=\"#FF9933\"><td>";
                $d2 .= $data2->kode2;
                $d2 .= "</td><td>";
                $d2 .= $data2->nama_parent2;
                $d2 .= "</td><td>";
                $d2 .= $data2->realisasi_n_3;
                $d2 .= "</td><td>";
                $d2 .= $data2->realisasi_n_2;
                $d2 .= "</td><td>";
                $d2 .= $data2->realisasi_n_1;
                $d2 .= "</td><td>";
                $d2 .= $data2->anggaran;
                $d2 .= "</td><td>";
                $d2 .= $data2->proyeksi;
                $d2 .= "</td><td>";
                $d2 .= "</td></tr>";
                $html .= $d2;

                $data_child =  $this->get_pendapatan_child($tahun,$data2->id2);
                foreach ($data_child as $child) {
                    # code...
                    $d3 = "<tr><td>";
                    $d3 .= $child->kode;
                    $d3 .= "</td><td>";
                    $d3 .= $child->nama;
                    $d3 .= "</td><td>";
                    $d3 .= $child->realisasi_n_3;
                    $d3 .= "</td><td>";
                    $d3 .= $child->realisasi_n_2;
                    $d3 .= "</td><td>";
                    $d3 .= $child->realisasi_n_1;
                    $d3 .= "</td><td>";
                    $d3 .= $child->anggaran;
                    $d3 .= "</td><td>";
                    $d3 .= $child->proyeksi;
                    $d3 .= "</td><td>";
                    $d3 .= '<a href="javascript:void(0)" onclick="edit_pendapatan('. $child->id_jenis_pendapatan .')" class="icon2-page_white_edit" title="Edit Data Pendapatan"/>';

                    $d3 .= "</td></tr>";
                    $html .= $d3;

                }

            }
        }
        //var_dump($html);
        return $html;
    }

}
?>
