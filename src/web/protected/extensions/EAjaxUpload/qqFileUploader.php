<?php
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize())
        {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName()
    {
        return $_GET['qqfile'];
    }
    function getSize()
    {
        if(isset($_SERVER["CONTENT_LENGTH"]))
        {
            return (int)$_SERVER["CONTENT_LENGTH"];
        }
        else
        {
            throw new Exception('Conseguir longitud del contenido no es soportada.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path))
        {
            return false;
        }
        return true;
    }
    function getName()
    {
        return $_FILES['qqfile']['name'];
    }
    function getSize()
    {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader
{
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if(isset($_GET['qqfile']))
        {
            $this->file = new qqUploadedFileXhr();
        }
        elseif(isset($_FILES['qqfile']))
        {
            $this->file = new qqUploadedFileForm();
        }
        else
        {
            $this->file = false;
        }
    }

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit)
        {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'aumentar post_max_size y upload_max_filesize a $size'}");
        }
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last)
        {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile=false)
    {
        //puedo escribir en el directorio?
        if (!is_writable($uploadDirectory))
        {
            return array('error'=>"Error del servidor. Directorio de carga no se puede escribir.");
        }
        //Hay archivos?
        if (!$this->file)
        {
            return array('error'=>'No hay archivos subidos.');
        }
        //Asigno el tamaño
        $size = $this->file->getSize();
        //El tamaño esta vacio?
        if($size==0)
        {
            return array('error'=>'El archivo está vacío');
        }
        //Es muy grande el archivo
        if($size>$this->sizeLimit)
        {
            return array('error'=>'El archivo es demasiado grande');
        }
        //Direccion del archivo en el cliente
        $pathinfo = pathinfo($this->file->getName());

        //Nombre del archivo
        $filename = Reader::nombre($pathinfo['filename']);      

        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions))
        {
            $these = implode(', ', $this->allowedExtensions);
            return array('error'=>'El archivo tiene una extensión inválida, debería ser una de '.$these.'.');
        }
        $valor="";
        if(!$replaceOldFile)
        {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename .$valor. '.' . $ext)) {
                $valor+=1;
            }
            $filename.=$valor;
        }

        if ($this->file->save($uploadDirectory.$filename.'.'.$ext))
        {
            return array('success'=>true,'filename'=>$filename.'.'.$ext);
        }
        else
        {
            return array('error'=>'No se pudo guardar el  archivo.'.'La subida fue cancelada, o se encontró un error en el servidor');
        }
    }
}