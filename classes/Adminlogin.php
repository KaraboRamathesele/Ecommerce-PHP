<?php
$filepath = realpath(dirname(__FILE__));
include($filepath . '/../lib/Session.php');
Session::checkLogin();
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/Format.php');

?>
<?php
/**
 * Adminlogin Class
 */
class Adminlogin
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function adminLogin($adminuser, $adminpass)
    {
        $adminuser = $this->fm->validation($adminuser);
        $adminpass = $this->fm->validation($adminpass);
        $adminuser = mysqli_real_escape_string($this->db->link, $adminuser);
        $adminpass = mysqli_real_escape_string($this->db->link, $adminpass);

        // database to login auth
        if (empty($adminuser) || empty($adminpass)) {
            $loginmsg = "Username or Password must not be empty!";
            return $loginmsg;
        } else {
            $query = "SELECT * FROM tbl_admin WHERE adminuser = '$adminuser' AND adminpass = '$adminpass'";
            $result = $this->db->select($query);
            if ($result != false) {
                $value = $result->fetch_assoc();
                Session::set("adminlogin", true);
                Session::set("adminid", $value['adminid']);
                Session::set("adminuser", $value['adminuser']);
                Session::set("adminname", $value['adminname']);
                Session::set("level", $value['level']);
                header("Location:dashboard.php");
            } else {
                $loginmsg = "Username or Password not match!";
                return $loginmsg;
            }
        }
    }
}
