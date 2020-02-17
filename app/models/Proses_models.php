<?php  

class Proses_models extends Controller
{	
	private $db;
	public function __construct()
	{
		$this->db = new Database;
	}

	/* ------------------------------> Tambah <--------------------------------- */

	public function addbuku($data)
	{
		$file_max_weight = 1000000; //limmit gambar

        $ok_ext = array('jpg','png','gif','jpeg'); // gambar yang diterima

        $destination = "img/daftar-buku/"; // simpen dmana nantik

        $file = $_FILES['gambar'];


        $filename = explode(".", $file["name"]); 


        $file_name = $file['name']; // nama asli gambar


        $file_name_no_ext = isset($filename[0]) ? $filename[0] : null; 

        $file_extension = $filename[count($filename)-1];

        $file_weight = $file['size'];

        $file_type = $file['type'];

        // jika tidak error
        if( $file['error'] == 0 )
        {
            // extensi bener ?
            if( in_array($file_extension, $ok_ext)):

                // ccek apa size melebihi kapasitas ? 
                if( $file_weight <= $file_max_weight ):


                        // rename biar di enkripsi
                        $fileNewName =  $file_name_no_ext[0].microtime().'.'.$file_extension ;


                        // pindahin ke folder baru
                        if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
                        // masukkan data ke database 
                          $query = "INSERT INTO tb_buku VALUES ('', :nama_buku, :pengarang, :id_kategori, :deskripsi, :gambar)";
							$this->db->query($query);
							$this->db->bind('nama_buku', $data['nama'] );
							$this->db->bind('pengarang', $data['pengarang'] );
							$this->db->bind('id_kategori', $data['kategori'] );
							$this->db->bind('deskripsi', $data['deskripsi'] );
							$this->db->bind('gambar', $fileNewName);

							$this->db->execute();
							return $this->db->rowCount();

                        else:

                            echo "Gagal Upload Gambar";

                        endif;


                else:

                   echo "File Melebihi Kapasitas";

                endif;


            else:

                echo "Extensi Gambar salah";

            endif;
        }

		
	}

	public function addkategori($data)
	{
		$query = "INSERT INTO tb_kategori VALUES ('', :kategori, :kode)";
		$this->db->query($query);
		$this->db->bind('kategori', $data['kategori'] );
		$this->db->bind('kode', $data['kode'] );

		$this->db->execute();
		return $this->db->rowCount();
	}

    public function addUser($data)
    {
        $query =  "INSERT INTO auth VALUES ('',:nama , :nis, :kelas, :username, :password, :id_level, :id_jurusan)";
        $this->db->query($query);
        $this->db->bind('nama',  $data['nama']);
        $this->db->bind('nis',  $data['nis']);
        $this->db->bind('kelas',  $data['kelas']);
        $this->db->bind('username',  $data['username']);
        $this->db->bind('password',  password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('id_level',  $data['level']);
        $this->db->bind('id_jurusan',  $data['jurusan']);

        $this->db->execute();
        return $this->db->rowCount();
    }
    
    /* ------------------------------> Hapus <--------------------------------- */

    public function hapus_buku($id)
    {
    	$query = "SELECT * FROM tb_buku WHERE id_buku = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $data = $this->db->single();
        $destination = "img/daftar-buku/";
        unlink($destination.$data['gambar']);



    	$query ="DELETE FROM tb_buku WHERE id_buku = :id";
    	$this->db->query($query);
    	$this->db->bind('id', $id);
    	
    	$this->db->execute();
    	return $this->db->rowCount();
    }

    public function hapus_user($id)
    {
        $query =  "DELETE FROM auth WHERE id_auth = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    /* ------------------------------> Edit <--------------------------------- */

    public function editbuku($data)
	{
		$query = "UPDATE tb_buku SET nama_buku = :nama_buku, pengarang = :pengarang, id_kategori = :id_kategori, deskripsi = :deskripsi WHERE id_buku = :id_buku";
		$this->db->query($query);
		$this->db->bind('nama_buku', $data['nama'] );
		$this->db->bind('pengarang', $data['pengarang'] );
		$this->db->bind('id_kategori', $data['kategori'] );
		$this->db->bind('deskripsi', $data['deskripsi'] );
		$this->db->bind('id_buku', $data['id']);

		$this->db->execute();
		return $this->db->rowCount();
	}

    public function edit_user($data)
    {
        $query = "UPDATE auth SET nama = :nama, nis = :nis, kelas = :kelas, id_jurusan = :id_jurusan WHERE id_auth = :id_auth";
        $this->db->query($query);
        $this->db->bind('nama', $data['nama'] );
        $this->db->bind('nis', $data['nis'] );
        $this->db->bind('kelas', $data['kelas'] );
        $this->db->bind('id_jurusan', $data['jurusan'] );
        $this->db->bind('id_auth', $data['id']);

        $this->db->execute();
        return $this->db->rowCount();
    }




}


        