<?php
class My_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('models', '', TRUE);
        $this->load->helper('cookie');
        ini_set('max_execution_time', 0);
    }

    function cek_akses()
    {
        $tipe = $this->input->cookie('cookie_invent_tipe');
        $controller = strtoupper($this->uri->segment(1));
        $method     = strtoupper($this->uri->segment(2));
        $gabung     = $controller . '/' . $method;
        $cek_menu_akses = $this->db->query("SELECT TOP 1 * FROM dbo.MenuAccess a
        INNER JOIN dbo.ModulePOS b
        ON a.MenuID = b.MenuID
        WHERE a.UserType ='" . $tipe . "' AND ([Path] ='" . $gabung . "' OR [Path] = '" . $controller . "')
        AND Flag in ('1','2')")->row();
        if (!$cek_menu_akses) {
            die("<script language='JavaScript'>alert('Akses dilarang!!!'); document.location='" . base_url() . "Dashboard'</script>");
        } else {
            return true;
        }
    }


    function ceklogin()
    {
        $this->Models->queryhandle("DELETE FROM t_login_log WHERE expired_time < CURRENT_TIMESTAMP()");
        $this->Models->queryhandle("UPDATE m_login SET LoginStatus = 0 
        WHERE username  IN (SELECT username FROM t_login_log) IS NOT TRUE");

        $username = $this->input->cookie('cookie_invent_user');
        $sesi = $this->input->cookie('cookie_invent_sesi');
        $jam = $this->db->query("SELECT (SELECT CURRENT_TIMESTAMP()) AS sekarang, (SELECT CURRENT_TIMESTAMP() + INTERVAL 8 HOUR) AS hangus")->row();

        $datacekuser = $this->Models->showsingle("SELECT count(*) total FROM t_login_log
                                                  WHERE username = '" . $username . "' AND sesi = '" . $sesi . "'");
        if ($datacekuser->total < 1) {
            delete_cookie('cookie_invent_user');
            delete_cookie('cookie_invent_tipe');
            $this->Models->queryhandle("UPDATE m_login SET LoginStatus = 0 WHERE username = '" . $username . "'");
            $this->Models->queryhandle("DELETE FROM t_login_log WHERE username = '" . $username . "'");

            die("<script language='JavaScript'>alert('Your time has expired, please re-login !'); document.location='" . base_url() . "logout'</script>");
            // $this->session->set_flashdata('msgLoginUlang', 'Your time has expired, please re-login !');
            // redirect(base_url() . "logout", "Refresh");
        }

        $this->Models->queryhandle("UPDATE t_login_log SET expired_time = '" . $jam->hangus . "' WHERE username = '" . $username . "' ");

        if ($username == NULL) {
            // $this->session->set_flashdata('message-failed', 'You are not logged in !');
            redirect(base_url('Logout'));
            // die("<script language='JavaScript'>alert('You are not logged in !'); document.location='" . base_url() . "logout'</script>");
            // $this->session->set_flashdata('msgLoginUlang', 'Your time has expired, please re-login !');
            // redirect(base_url() . "logout", "Refresh");
        }

        $vCuser     = $this->input->cookie('cookie_invent_user');
        $vCsesi     = $this->input->cookie('cookie_invent_sesi');
        $vCtipe     = $this->input->cookie('cookie_invent_tipe');

        $vcookie1 = array(
            'name'   => 'cookie_invent_user',
            'value'  => $vCuser,
            'expire' => '86400'
        );
        $this->input->set_cookie($vcookie1);

        $vcookie2 = array(
            'name'   => 'cookie_invent_sesi',
            'value'  => $vCsesi,
            'expire' => '86400'
        );
        $this->input->set_cookie($vcookie2);

        $vcookie3 = array(
            'name'   => 'cookie_invent_tipe',
            'value'  => $vCtipe,
            'expire' => '86400'
        );
        $this->input->set_cookie($vcookie3);
    }


    function menu_baru()
    {
        $type = $this->input->cookie('cookie_invent_tipe');
        $query = $this->db->query("SELECT b.* FROM t_menu_access a
        INNER JOIN m_menu b ON a.menu_id = b.menu_id
        WHERE b.menu_fl = 1 AND role_id = '".$type."' ORDER BY menu_no ASC")->result();

        $i = 1;
        foreach ($query as $hasil) {
            $number_child = $this->db->query("SELECT * FROM m_menu WHERE parent_menu_id = '" . $hasil->menu_id . "'")->num_rows();
            $s = "";
            $arrow = "";
            $link = base_url() . $hasil->menu_url;
            $hastag = "";
            $toggle = "";
            if (($hasil->menu_url == $this->parent_url($this->uri->segment(1))) || ($hasil->menu_url ==  $this->uri->segment(1))) {
                $s = "active";
            }

            if ($number_child > 0) {
                $link = "menu" . $i;
                $arrow = "<i class='menu-arrow'></i>";
                $hastag = "#";
                $toggle = "data-toggle='collapse'";
            }

            if ($hasil->parent_menu_id == 0) {
                echo "<li class=' nav-item " . $s . "'>";
                echo "<a class='nav-link' $toggle href='" . $hastag . "" . $link . "' aria-expanded='false' aria-controls='" . $link . "'>
                    <i class='typcn " . $hasil->menu_icon . " menu-icon'></i>
                    <span class='menu-title'>" . $hasil->menu_name . "</span>
                    $arrow
                </a>";

                $this->get_child($hasil->menu_id, $link);
                echo "</li>";
            }
            $i++;
        }
    }

    function get_child($kode, $link)
    {
        $type = $this->input->cookie('cookie_invent_tipe');
        $query = $this->db->query("SELECT b.* FROM t_menu_access a
        INNER JOIN m_menu b ON a.menu_id = b.menu_id
        WHERE b.menu_fl = 1 AND role_id = '".$type."' and parent_menu_id = '".$kode."' ORDER BY menu_no ASC");

        if ($query->num_rows() > 0) {
            echo "<div class='collapse' id='" . $link . "'><ul class='nav flex-column sub-menu'>";
            foreach ($query->result() as $hsl) {
                $ss = "";
                if ($hsl->menu_url ==  $this->uri->segment(1) . '/' . $this->uri->segment(2)) {
                    $ss = "active";
                }
                echo "<li class='nav-item " . $ss . "'> <a class='nav-link' href='" . base_url() . $hsl->menu_url . "'>" . $hsl->menu_name . "</a></li>";
            }
            echo "</ul></div>";
        }
    }

    // function get_second_child($kode)
    // {
    //     $type = $this->input->cookie('cookie_invent_tipe');
    //     $query = $this->db->query(" SELECT b.* FROM dbo.MenuAccess a
    //                                 INNER JOIN dbo.Module b ON a.MenuID = b.MenuID 
    //                                 WHERE b.Flag = 1 AND UserType='" . $type . "' AND Parents = '" . $kode . "' ORDER BY ModuleName ASC");

    //     if ($query->num_rows() > 0) {
    //         echo "<ul class='submenu''>";
    //         foreach ($query->result() as $hsl) {
    //             $sss = "";
    //             if ($hsl->Path ==  $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3)) {
    //                 $sss = "active";
    //             }
    //             echo "<li class = '" . $sss . "' >
    //             <a class='linknya' href='" . base_url() . $hsl->Path . "'> <i class='menu-icon fa fa-caret-right'></i> " . $hsl->ModuleName . "</a>
    //             <b class='arrow'></b>
    //             </li>";
    //         }
    //         echo "</ul>";
    //     }
    // }

    function parent_url($kode = '')
    {
        $query2 = $this->db->query("SELECT b.parent_menu_id AS PathParent FROM m_menu a 
        LEFT JOIN m_menu b on a.parent_menu_id = b.menu_id 
        WHERE b.menu_fl = 1 AND a.menu_url = '".$kode."'");

        if ($query2->num_rows() > 0) {
            return $query2->row()->parent_menu_id;
        } else {
            return false;
        }
    }
}
